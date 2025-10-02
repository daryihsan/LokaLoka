<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Alamat - Loka Loka</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');
        :root {
            --primary-color: #5c6641;
            --background-color: #e3d8c2;
            --surface-color: #ffffff;
            --text-primary: #333333;
            --border-color: #e0e0e0;
        }
        body {
            font-family: 'Open Sans', sans-serif; background-color: var(--background-color); margin: 0;
            padding: 40px 20px; display: flex; justify-content: center; align-items: flex-start;
        }
        .page-container { width: 100%; max-width: 700px; }
        .page-header h1 { font-family: 'Roboto Slab', serif; color: var(--primary-color); font-size: 2.5em; text-align: center; }
        .card { background-color: var(--surface-color); border-radius: 15px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input, .form-group textarea {
            width: 100%; padding: 12px 15px; border: 1px solid var(--border-color);
            border-radius: 8px; box-sizing: border-box; font-size: 1em;
            font-family: 'Open Sans', sans-serif;
        }
        .form-actions {
            margin-top: 30px; display: flex; justify-content: flex-end;
            align-items: center; gap: 15px;
        }
        .btn {
            padding: 12px 25px; border: none; border-radius: 8px;
            font-family: 'Roboto', sans-serif; font-weight: bold; cursor: pointer;
            text-decoration: none; font-size: 1em; transition: all 0.3s ease;
        }
        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: #4a5335; transform: translateY(-2px); }
        .btn-secondary { background-color: #e0e0e0; color: var(--text-primary); }
        .btn-secondary:hover { background-color: #c7c7c7; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <h1>Ubah Alamat Pengiriman</h1>
        </div>
        <div class="card">
            <form action="{{ route('alamat.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fullName">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName" value="{{ session('nama_penerima', 'John Doe') }}" required>
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Nomor Telepon</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ session('telepon_penerima', '(+62) 812-3456-7890') }}" required>
                </div>
                <div class="form-group">
                    <label for="streetAddress">Alamat Lengkap</label>
                    <textarea id="streetAddress" name="streetAddress" rows="3" required>{{ session('alamat_penerima', 'Jl. Pahlawan No. 123, Mugassari, Kec. Semarang Sel.') }}</textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelButton">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
                
            </form>
        </div>
    </div>

    <script>
        document.getElementById('cancelButton').addEventListener('click', function() {
            window.location.href = "{{ url('/checkout') }}";
        });
    </script>
</body>
</html>