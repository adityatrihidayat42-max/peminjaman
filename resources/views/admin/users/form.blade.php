@extends('layouts.app')

@php($edit = $user->exists)
@section('title', $edit ? 'Edit Akun' : 'Tambah Akun')

@section('content')
<h1 class="page-title mb-3">{{ $edit ? 'Edit Akun' : 'Tambah Akun' }}</h1>
<div class="card-sp" style="max-width:640px"><div class="card-pad">
    <form method="POST" action="{{ $edit ? route('admin.users.update', $user) : route('admin.users.store') }}" novalidate>
        @csrf
        @if ($edit) @method('PUT') @endif
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" @disabled($edit && $user->id === auth()->id())>
                @foreach (\App\Models\User::ROLES as $r)<option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ ucfirst($r) }}</option>@endforeach
            </select>
            @if ($edit && $user->id === auth()->id())<input type="hidden" name="role" value="admin">@endif
            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password {{ $edit ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-sp">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
    </form>
</div></div>
@endsection
