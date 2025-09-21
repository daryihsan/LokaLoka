<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Loka Loka</title>
    <link rel="stylesheet" href="daftar.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="info-panel">
            <h1>Loka Loka</h1>
            <p>Cinta Lokal Belanja Loka</p>
        </div>

        <div class="form-panel">
            <h2>Buat Akun Baru</h2>
            <p class="subtitle">Silakan daftar untuk mulai berbelanja</p>
            
            <form action="proses_register.php" method="POST">
                <div class="input-group">
                    <span class="icon">
                        <!-- Username Icon (User) -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <span class="icon">
                        <!-- Email Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="icon">
                        <!-- Password Icon (Lock) -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">DAFTAR</button>
            </form>

            <div class="footer-link">
                Sudah punya akun? <a href="login.html">Login di sini</a>
            </div>
        </div>
    </div>
</body>
</html>