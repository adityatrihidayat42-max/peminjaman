<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index', [
            'kategoris' => Kategori::withCount('alats')->orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kategori::create($this->validasi($request));

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $kategori->update($this->validasi($request, $kategori));

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        // Aturan: kategori yang masih dipakai alat tidak boleh dihapus.
        if ($kategori->alats()->exists()) {
            return back()->with('gagal', 'Kategori masih digunakan oleh alat. Pindahkan alat ke kategori lain terlebih dahulu.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil dihapus.');
    }

    private function validasi(Request $request, ?Kategori $kategori = null): array
    {
        return $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255', Rule::unique('kategoris', 'nama_kategori')->ignore($kategori?->id)],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);
    }
}
