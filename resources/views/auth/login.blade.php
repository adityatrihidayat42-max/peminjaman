<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SIPERAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/siperal.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="brand"><i class="bi bi-pc-display me-1"></i> SIPERAL</div>
            <h1 class="h5 mt-2 mb-1">Masuk Akun</h1>
            <p class="text-secondary small mb-0">Silakan login untuk meminjam alat lab RPL</p>
        </div>

        @if (session('sukses'))
            <div class="alert alert-success py-2 small">{{ session('sukses') }}</div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus autocomplete="username"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-sp w-100">Masuk</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('beranda') }}" class="small">&larr; Kembali ke beranda</a>
        </div>
    </div>
</div>
</body>
</html>
