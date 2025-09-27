<html>
    <head>
        <title>Edit Produk</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
    <div class="container mt-5">
        <h2>Edit Produk</h2>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Gambar Produk</label><br>
                <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="img-thumbnail mb-3" style="max-width: 300px;">
                <input type="file" name="image" class="form-control-file mt-2">
            </div>
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="form-group">
                <label for="price">Harga Produk</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>
            <div class="form-group">
                <label for="stock">Stok Produk</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori Produk</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="Elektronik" {{ old('kategori', $product->kategori) == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="Fashion" {{ old('kategori', $product->kategori) == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                    <option value="Rumah Tangga" {{ old('kategori', $product->kategori) == 'Rumah Tangga' ? 'selected' : '' }}>Rumah Tangga</option>
                    <option value="Kesehatan" {{ old('kategori', $product->kategori) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="Olahraga" {{ old('kategori', $product->kategori) == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="Makanan & Minuman" {{ old('kategori', $product->kategori) == 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman</option>
                    <option value="Buku" {{ old('kategori', $product->kategori) == 'Buku' ? 'selected' : '' }}>Buku</option>
                    <option value="Otomotif" {{ old('kategori', $product->kategori) == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Produk</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Produk</button>
            <button type="reset" class="btn btn-secondary">Edit Produk</button>
            <button type="button" class="btn btn-danger">Hapus Produk</button>
        </form>
    </div>
    </body>
</html>