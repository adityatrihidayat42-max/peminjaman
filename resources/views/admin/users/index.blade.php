@extends('layouts.app')

@section('title', 'Pengelolaan Akun')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h1 class="page-title">Pengelolaan Akun</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sp"><i class="bi bi-plus-lg me-1"></i> Tambah Akun</a>
</div>
<div class="card-sp">
    <div class="card-pad border-bottom">
        <form method="GET" class="d-flex gap-2"><input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama / email..."><button class="btn btn-sp">Cari</button></form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sp">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th class="text-end">Peminjaman</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @foreach ($users as $u)
                <tr>
                    <td>{{ $u->name }}</td><td>{{ $u->email }}</td>
                    <td><span class="sp-badge {{ $u->role === 'admin' ? 'sp-badge-danger' : ($u->role === 'guru' ? 'sp-badge-info' : 'sp-badge-primary') }}">{{ ucfirst($u->role) }}</span></td>
                    <td class="text-end">{{ $u->peminjamans_count }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" @disabled($u->id === auth()->id())>Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-pad">{{ $users->links() }}</div>
</div>
@endsection
