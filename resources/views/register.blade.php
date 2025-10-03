@extends('layouts.app')

@section('title', 'Daftar - Loka Loka')

@push('head')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');
    body { background-color: #e3d8c2; }
    .register-wrapper { display: flex; width: 100%; max-width: 1000px; min-height: 650px; background-color: #ffffff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; margin: 0 auto; }
    .info-panel { flex: 1; background-color: #5c6641; color: #ffffff; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; text-align: center; }
    .info-panel h1 { font-family: 'Roboto Slab', serif; font-size: 3em; margin: 0; letter-spacing: 2px; }
    .info-panel p { font-family: 'Roboto', sans-serif; font-size: 1.1em; margin-top: 15px; max-width: 300px; line-height: 1.6; }
    .form-panel { flex: 1; padding: 50px; display: flex; flex-direction: column; justify-content: center; overflow-y: auto; }
    .form-panel h2 { font-family: 'Roboto', sans-serif; font-size: 1.8em; color: #333; margin-bottom: 10px; font-weight: 700; }
    .form-panel .subtitle { color: #666; margin-bottom: 30px; }
    .error-message { background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-size: 0.9em; }
    .success-message { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-size: 0.9em; }
    .input-group { position: relative; margin-bottom: 20px; }
    .input-group .icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
    .input-group input { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-family: 'Open Sans', sans-serif; font-size: 1em; transition: border-color 0.3s, box-shadow 0.3s; }
    .input-group input:focus { outline: none; border-color: #5c6641; box-shadow: 0 0 8px rgba(92, 102, 65, 0.3); }
    .input-group input.error { border-color: #dc3545; }
    button { width: 100%; padding: 14px; border: none; border-radius: 8px; background-color: #5c6641; color: white; font-family: 'Roboto', sans-serif; font-size: 1.1em; font-weight: bold; cursor: pointer; transition: background-color 0.3s, transform 0.2s; margin-top: 10px; }
    button:hover { background-color: #4a5335; transform: translateY(-2px); }
    button:disabled { background-color: #ccc; cursor: not-allowed; transform: none; }
    .footer-link { margin-top: 25px; text-align: center; font-size: 0.9em; }
    .footer-link a { color: #5c6641; text-decoration: none; font-weight: bold; }
    .footer-link a:hover { text-decoration: underline; }
    .form-row { display: flex; gap: 15px; }
    .form-row .input-group { flex: 1; }
    @media (max-width: 800px) {
        .register-wrapper { flex-direction: column; height: auto; max-width: 450px; margin: 20px auto; }
        .info-panel { padding: 30px; }
        .form-panel { padding: 30px 40px; }
        .form-row { flex-direction: column; gap: 0; }
    }
</style>
@endpush

@section('content')
<div class="register-wrapper">
    <div class="info-panel">
        <h1>Loka Loka</h1>
        <p>Bergabunglah dengan komunitas yang mendukung produk lokal Indonesia</p>
    </div>

    <div class="form-panel">
        <h2>Daftar Sekarang</h2>
        <p class="subtitle">Buat akun baru untuk memulai berbelanja</p>

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            <div class="input-group">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            </div>

            <div class="input-group">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </span>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>

            <div class="input-group">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </span>
                <input type="tel" name="phone_number" placeholder="Nomor Telepon" value="{{ old('phone_number') }}" required>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" name="password" placeholder="Password" required minlength="6">
                </div>
                <div class="input-group">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required minlength="6">
                </div>
            </div>

            <button type="submit">DAFTAR</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        if (password !== confirmPassword) { e.preventDefault(); alert('Password dan konfirmasi password tidak cocok!'); return false; }
        if (password.length < 6) { e.preventDefault(); alert('Password minimal 6 karakter!'); return false; }
    });

    const passwordField = document.querySelector('input[name="password"]');
    const confirmPasswordField = document.querySelector('input[name="password_confirmation"]');
    function checkPasswordMatch() {
        if (confirmPasswordField.value && passwordField.value !== confirmPasswordField.value) confirmPasswordField.style.borderColor = '#dc3545';
        else confirmPasswordField.style.borderColor = '#ccc';
    }
    passwordField.addEventListener('input', checkPasswordMatch);
    confirmPasswordField.addEventListener('input', checkPasswordMatch);
</script>
@endpush