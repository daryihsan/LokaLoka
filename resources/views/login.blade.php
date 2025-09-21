<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loka Loka</title>
    <style>
        /* Import Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');

        /* Basic Styles */
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #e3d8c2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Main Container */
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            height: 550px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden; /* Ensures content respects the border-radius */
        }

        /* --- Info Panel (Left Side) --- */
        .info-panel {
            flex: 1;
            background-color: #5c6641;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

        .info-panel h1 {
            font-family: 'Roboto Slab', serif;
            font-size: 3em;
            margin: 0;
            letter-spacing: 2px;
        }

        .info-panel p {
            font-family: 'Roboto', sans-serif;
            font-size: 1.1em;
            margin-top: 15px;
            max-width: 300px;
            line-height: 1.6;
        }
        
        /* --- Form Panel (Right Side) --- */
        .form-panel {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-panel h2 {
            font-family: 'Roboto', sans-serif;
            font-size: 1.8em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .form-panel .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        
        /* Input Group with Icons */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px 12px 45px; /* Left padding for icon */
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #5c6641;
            box-shadow: 0 0 8px rgba(92, 102, 65, 0.3);
        }

        /* Login Button */
        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background-color: #5c6641;
            color: white;
            font-family: 'Roboto', sans-serif;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #4a5335;
            transform: translateY(-2px); /* Slight lift effect */
        }

        /* Footer Link */
        .footer-link {
            margin-top: 25px;
            text-align: center;
            font-size: 0.9em;
        }

        .footer-link a {
            color: #5c6641;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
        
        /* --- Responsive Design for Mobile --- */
        @media (max-width: 800px) {
            .login-wrapper {
                flex-direction: column;
                height: auto;
                max-width: 450px;
                margin: 20px;
            }
            .info-panel {
                padding: 30px;
            }
            .form-panel {
                padding: 30px 40px;
            }
        }

    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="info-panel">
            <h1>Loka Loka</h1>
            <p>Cinta Lokal Belanja Loka</p>
        </div>

        <div class="form-panel">
            <h2>Selamat Datang Kembali!</h2>
            <p class="subtitle">Silakan masuk ke akun Anda</p>
            
            <form action="proses_login.php" method="POST">
                <div class="input-group">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" name="username" placeholder="Username atau Email" required>
                </div>
                <div class="input-group">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">LOGIN</button>
            </form>

            <div class="footer-link">
                Belum punya akun? <a href="register.html">Daftar di sini</a>
            </div>
        </div>
    </div>

</body>
</html>