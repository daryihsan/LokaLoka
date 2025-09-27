<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loka Loka - Detail Produk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;500;700&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-roboto-slab { font-family: 'Roboto Slab', serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-open-sans { font-family: 'Open Sans', sans-serif; }
        
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

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
        <h2 class="text-3xl font-roboto-slab font-bold text-green-darker mb-6">Detail Produk</h2>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Gambar Produk</label>
            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="rounded-lg shadow-md w-full max-w-md mx-auto">
        </div>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Nama Produk</label>
            <p class="text-gray-700 font-medium text-lg">{{ $product->name }}</p>
        </div>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Harga</label>
            <p class="text-gray-700 font-medium text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Stok</label>
            <p class="text-gray-700 font-medium text-lg">{{ $product->stock }}</p>
        </div>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Kategori</label>
            <p class="text-gray-700 font-medium text-lg">{{ $product->kategori }}</p>
        </div>
        
        <div class="mb-6">
            <label class="block font-semibold text-green-darker mb-2">Deskripsi</label>
            <p class="text-gray-700 font-medium leading-relaxed">{{ $product->description }}</p>
        </div>
    </main>
    
    <footer class="bg-primary text-white p-8 mt-12">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                    <span class="text-green-darker font-bold">L</span>
                </div>
                <h3 class="font-roboto-slab text-xl font-bold">Loka Loka</h3>
            </div>
            <p class="font-open-sans text-sm opacity-80">
                Marketplace produk lokal terpercaya untuk mendukung UMKM Indonesia
            </p>
        </div>
    </footer>
</body>
</html>