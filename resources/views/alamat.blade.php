<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Alamat - Loka Loka</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');
        :root { --primary-color:#5c6641; --background-color:#e3d8c2; --surface-color:#ffffff; --text-primary:#333; --border-color:#e0e0e0; }
        body { font-family:'Open Sans',sans-serif; background:var(--background-color); margin:0; padding:40px 20px; display:flex; justify-content:center; align-items:flex-start; }
        .page-container { width:100%; max-width:700px; }
        .page-header { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px; }
        .page-header h1 { font-family:'Roboto Slab',serif; color:var(--primary-color); font-size:2.0em; margin:0; }
        .btn { padding:10px 18px; border:none; border-radius:8px; font-family:'Roboto',sans-serif; font-weight:bold; cursor:pointer; text-decoration:none; font-size:.95em; transition:all .3s ease; }
        .btn-primary { background:var(--primary-color); color:#fff; }
        .btn-primary:hover { background:#4a5335; transform:translateY(-2px); }
        .btn-secondary { background:#e0e0e0; color:var(--text-primary); }
        .btn-secondary:hover { background:#c7c7c7; transform:translateY(-2px); }
        .btn-danger { background:#dc2626; color:#fff; }
        .btn-danger:hover { background:#b91c1c; transform:translateY(-2px); }
        .card { background:var(--surface-color); border-radius:15px; padding:40px; box-shadow:0 4px 20px rgba(0,0,0,.08); }
        .form-group { margin-bottom:20px; }
        .form-group label { display:block; margin-bottom:8px; font-weight:bold; }
        .form-group input,.form-group textarea { width:100%; padding:12px 15px; border:1px solid var(--border-color); border-radius:8px; box-sizing:border-box; font-size:1em; font-family:'Open Sans',sans-serif; }
        .form-actions { margin-top:30px; display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }
        .left-actions { display:flex; gap:10px; align-items:center; }
        .right-actions { display:flex; gap:10px; align-items:center; margin-left:auto; }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <button class="btn btn-secondary"
                onclick="history.length > 1 ? history.back() : window.location.href = '{{ url('/checkout') }}'">
                Kembali
            </button>
            <h1 class="font-roboto-slab text-3xl font-bold text-green-darker">Ubah Alamat Pengiriman</h1>
            <div></div>
        </div>
        <div class="card">
            <form action="{{ route('alamat.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="fullName">Nama Lengkap</label>
                    {{-- PERBAIKAN: Gunakan $defaultNama --}}
                    <input type="text" id="fullName" name="fullName" value="{{ $defaultNama }}" required>
                </div>

                <div class="form-group">
                    <label for="phoneNumber">Nomor Telepon</label>
                    {{-- PERBAIKAN: Gunakan $defaultTelepon --}}
                    <input type="tel" id="phoneNumber" name="phoneNumber" value="{{ $defaultTelepon }}" required>
                </div>

                <div class="form-group">
                    <label for="streetAddress">Alamat Lengkap</label>
                    {{-- PERBAIKAN: Gunakan $defaultAlamat --}}
                    <textarea id="streetAddress" name="streetAddress" rows="3" required>{{ $defaultAlamat }}</textarea>
                </div>

                
                <div class="form-actions">
                    <div class="left-actions">
                        <button type="button" class="btn btn-secondary" id="cancelButton">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                    <form action="{{ route('alamat.delete') }}" method="POST" onsubmit="return confirm('Hapus alamat pengiriman?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">Hapus Alamat</button>
                    </form>
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