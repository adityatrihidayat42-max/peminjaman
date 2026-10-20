<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount('peminjamans')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%{$v}%")->orWhere('email', 'like', "%{$v}%")))
            ->orderBy('role')->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['role' => 'siswa'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->aturan(), $this->pesan());
        User::create($data); // password otomatis di-hash oleh cast 'hashed'

        return redirect()->route('admin.users.index')->with('sukses', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate($this->aturan($user), $this->pesan());

        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Cegah admin menurunkan role dirinya sendiri (menghindari kehilangan akses admin).
        if ($user->id === $request->user()->id) {
            $data['role'] = 'admin';
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('sukses', 'Akun berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('gagal', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }
        if ($user->peminjamans()->exists()) {
            return back()->with('gagal', 'Akun memiliki riwayat peminjaman dan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('sukses', 'Akun berhasil dihapus.');
    }

    private function aturan(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    private function pesan(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }
}
