@extends('layouts.app')

@section('title', 'Ubah Alamat - Loka Loka')

@push('head')
<style>
    :root { --primary-color:#5c6641; --background-color:#e3d8c2; --surface-color:#ffffff; --text-primary:#333; --border-color:#e0e0e0; }
    .page-container { width:100%; max-width:700px; margin: 0 auto; }
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
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <a class="btn btn-secondary" href="{{ url('/checkout') }}">← Kembali</a>
        <h1>Ubah Alamat Pengiriman</h1>
        <div></div>
    </div>
    <div class="card">
        <form id="update-address-form" action="{{ route('alamat.update') }}" method="POST">
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
        </form>

        <div class="form-actions">
            <div class="left-actions">
                <a href="{{ url('/checkout') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" form="update-address-form">Simpan Perubahan</button>
            </div>

            <form id="delete-address-form" action="{{ route('alamat.delete') }}" method="POST" onsubmit="return confirm('Hapus alamat pengiriman?')">
                @csrf
                <button type="submit" class="btn btn-danger">Hapus Alamat</button>
            </form>
        </div>
    </div>
</div>
@endsection