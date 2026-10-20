<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PengembalianController extends Controller
{
    public function index()
    {
        Peminjaman::tandaiTerlambat();

        $items = Peminjaman::with('user', 'details.alat')
            ->whereIn('status', Peminjaman::STATUS_BELUM_KEMBALI)
            ->orderBy('tanggal_rencana_kembali')->paginate(10);

        return view('admin.pengembalian.index', compact('items'));
    }

    public function form(Peminjaman $peminjaman)
    {
        if (! in_array($peminjaman->status, Peminjaman::STATUS_BELUM_KEMBALI, true)) {
            return redirect()->route('admin.pengembalian.index')->with('gagal', 'Transaksi ini tidak sedang dipinjam.');
        }

        $peminjaman->load('user', 'details.alat');

        return view('admin.pengembalian.form', [
            'peminjaman' => $peminjaman,
            'kondisis' => DetailPeminjaman::KONDISI_KEMBALI,
        ]);
    }

    public function proses(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if (! in_array($peminjaman->status, Peminjaman::STATUS_BELUM_KEMBALI, true)) {
            return redirect()->route('admin.pengembalian.index')->with('gagal', 'Transaksi ini sudah diproses atau belum diserahkan.');
        }

        $data = $request->validate([
            'tanggal_kembali_aktual' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:' . $peminjaman->tanggal_pinjam->toDateString()],
            'kondisi' => ['required', 'array'],
            'kondisi.*' => ['required', Rule::in(DetailPeminjaman::KONDISI_KEMBALI)],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal_kembali_aktual.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_kembali_aktual.before_or_equal' => 'Tanggal pengembalian tidak boleh di masa depan.',
            'tanggal_kembali_aktual.after_or_equal' => 'Tanggal pengembalian tidak boleh sebelum tanggal pinjam.',
            'kondisi.*.required' => 'Kondisi setiap alat wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $peminjaman, $data) {
            $peminjaman->load('details.alat');

            foreach ($peminjaman->details as $d) {
                $kondisi = $data['kondisi'][$d->id] ?? 'Baik';
                $d->update(['kondisi_saat_kembali' => $kondisi]);

                // Unit hilang / rusak berat tidak kembali ke stok: kurangi jumlah total inventaris.
                if (in_array($kondisi, ['Hilang', 'Rusak Berat'], true)) {
                    $alat = $d->alat;
                    $alat->update(['jumlah_total' => max(0, $alat->jumlah_total - $d->jumlah)]);
                }
            }

            $peminjaman->update(['tanggal_kembali_aktual' => $data['tanggal_kembali_aktual']]);
            // Status Dikembalikan otomatis melepaskan stok (tersedia dihitung dari status).
            $peminjaman->ubahStatus('Dikembalikan', $request->user()->id, $data['catatan'] ?? 'Alat dikembalikan.');
        });

        return redirect()->route('admin.pengembalian.index')->with('sukses', 'Pengembalian berhasil dicatat dan stok diperbarui.');
    }
}
