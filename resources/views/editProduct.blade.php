<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Loka - Edit Produk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }

        /* Custom colors */
        .bg-primary { background-color: #5c6641; }
        .bg-primary-dark { background-color: #414833; }
        .bg-accent { background-color: #A6A604; }
        .text-green-darker { color: #333D29; }
        .text-green-dark { color: #656D4A; }
        .text-green-olive { color: #A4AC86; }
        .border-green-light { border-color: #C2C5AA; }
    </style>
</head>
<body class="min-h-screen text-green-darker font-open-sans bg-white">
    <header class="sticky top-0 z-50 bg-primary text-white p-4 shadow-lg">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold text-lg">L</span>
                </div>
                <h1 class="font-roboto-slab text-3xl font-bold tracking-wide">Loka Loka</h1>
            </div>
            <a href="{{ route('products.index') }}" class="bg-accent text-green-darker px-5 py-2 rounded-lg font-semibold shadow-lg hover:bg-accent/90">Kembali</a>
        </div>
    </header>
    
    <main class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-3xl font-roboto-slab font-bold text-green-darker mb-6">Edit Produk</h2>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-green-darker mb-2">Gambar Produk</label>
                <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="rounded-lg shadow-md w-full max-w-md mb-3">
                <input type="file" name="image" class="block w-full text-sm text-green-dark border border-green-light rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-accent">
            </div>

            <div>
                <label for="name" class="block font-semibold text-green-darker mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" class="w-full p-3 border border-green-light rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" value="{{ old('name', $product->name) }}" required>
            </div>

            <div>
                <label for="price" class="block font-semibold text-green-darker mb-2">Harga Produk</label>
                <input type="number" name="price" id="price" class="w-full p-3 border border-green-light rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" value="{{ old('price', $product->price) }}" required>
            </div>

            <div>
                <label for="stock" class="block font-semibold text-green-darker mb-2">Stok Produk</label>
                <input type="number" name="stock" id="stock" class="w-full p-3 border border-green-light rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" value="{{ old('stock', $product->stock) }}" required>
            </div>

            <div>
                <label for="kategori" class="block font-semibold text-green-darker mb-2">Kategori Produk</label>
                <select name="kategori" id="kategori" class="w-full p-3 border border-green-light rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
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

            <div>
                <label for="description" class="block font-semibold text-green-darker mb-2">Deskripsi Produk</label>
                <textarea name="description" id="description" class="w-full p-3 border border-green-light rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="bg-primary text-white px-5 py-2 rounded-lg font-semibold shadow-lg hover:bg-primary-dark">Tambah Produk</button>
                <button type="submit" class="bg-gray-200 text-green-darker px-5 py-2 rounded-lg font-semibold shadow-lg hover:bg-gray-300">Ubah Produk</button>
                <button type="button" class="bg-red-500 text-white px-5 py-2 rounded-lg font-semibold shadow-lg hover:bg-red-600">Hapus Produk</button>
            </div>
        </form>
    </main>
</body>
</html>