<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Alamat | Loka Loka</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #5c6641;
            --background-color: #e3d8c2;
            --surface-color: #ffffff;
            --text-primary: #333D29;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--background-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .page-container {
            width: 100%;
            max-width: 700px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-family: 'Roboto Slab', serif;
            color: var(--primary-color);
            font-size: 2.0em;
            margin: 0;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.95em;
            transition: all .3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background: #4a5335;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: #c7c7c7;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc2626;
            color: #fff;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        .card {
            background: var(--surface-color);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1em;
            font-family: 'Open Sans', sans-serif;
            resize: vertical;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .left-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="page-container">

        <div class="page-header">
            {{-- Tombol kembali --}}
            <button class="btn btn-secondary" onclick="window.location.href = '{{ route('checkout.show') }}'">
                Kembali
            </button>
            <h1 class="font-roboto-slab text-3xl font-bold text-green-darker">Ubah Alamat Pengiriman</h1>
            <div></div>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">Terdapat kesalahan pada input form.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <form action="{{ route('alamat.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fullName">Nama Lengkap</label>
                    <input type="text" id="fullName" name="fullName" value="{{ old('fullName', $defaultNama) }}" required>
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Nomor Telepon</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber', $defaultTelepon) }}" required>
                </div>
                <div class="form-group">
                    <label for="streetAddress">Alamat Lengkap</label>
                    <textarea id="streetAddress" name="streetAddress" rows="3" required>{{ old('streetAddress', $defaultAlamat) }}</textarea>
                </div>

                <div class="form-actions">
                    <div class="left-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href = '{{ route('checkout.show') }}'">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                    
                    {{-- Form Hapus Alamat --}}
                    <form action="{{ route('alamat.delete') }}" method="POST" onsubmit="return confirm('Hapus alamat pengiriman? Ini akan menghapus data di sesi, Anda harus mengisi ulang di checkout.')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Hapus Alamat</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
