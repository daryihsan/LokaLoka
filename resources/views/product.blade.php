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
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
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
            
            <a href="{{ route('homepage') }}" class="bg-accent text-green-darker px-5 py-2 rounded-lg font-semibold shadow-lg hover:bg-accent/90">Kembali ke Beranda</a>
        </div>
    </header>
    
    
    
    <main class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
        
        <button onclick="history.back()" class="mb-4 text-sm text-gray-600 hover:text-green-darker font-medium">&larr; Kembali ke halaman sebelumnya</button>

        <h2 class="text-3xl font-roboto-slab font-bold text-green-darker mb-6">Detail Produk</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="mb-6">
                    <label class="block font-semibold text-green-darker mb-2">Gambar Produk</label>
                    <img src="{{ $product->image_url }}" 
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}';" 
                         alt="Gambar Produk" 
                         class="rounded-lg shadow-md w-full object-cover">
                </div>
                
                <form id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="flex items-center gap-4 mb-4">
                        <label for="quantity" class="text-lg font-semibold text-green-darker">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                               class="w-20 text-center p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent">
                        <span class="text-sm text-gray-500">Stok: {{ $product->stock }}</span>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-lg font-medium transition-colors 
                            {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                        {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <h1 class="text-4xl font-roboto-slab font-bold text-green-darker mb-4">{{ $product->name }}</h1>

                <div class="pt-4 border-t border-green-light">
                    <label class="block font-semibold text-green-darker mb-2 text-xl">Harga</label>
                    <p class="text-5xl font-bold text-red-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="block font-semibold text-green-darker mb-2">Kategori</label>
                    <p class="text-gray-700 font-medium text-lg">{{ $product->category->name ?? 'Tidak Berkategori' }}</p>
                </div>
                
                <div>
                    <label class="block font-semibold text-green-darker mb-2">Berat</label>
                    <p class="text-gray-700 font-medium text-lg">{{ number_format($product->weight ?? 0, 2) }} kg</p>
                </div>
                
                <div>
                    <label class="block font-semibold text-green-darker mb-2">Deskripsi</label>
                    <p class="text-gray-700 font-medium leading-relaxed whitespace-pre-wrap">{{ $product->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </div>

            </div>
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

    <div id="toast-notification" class="fixed top-5 right-5 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
        <div id="toast-message" class="font-medium text-green-darker"></div>
    </div>
    
    <script>
        // Fungsi Tambah ke Keranjang (AJAX)
        document.getElementById('add-to-cart-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Mengirim data menggunakan AJAX
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Produk berhasil ditambahkan ke keranjang!');
                } else if (data.error) {
                    showToast('error', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Terjadi kesalahan saat menghubungi server.');
            });
        });
        
        // Fungsi Pop-up Notifikasi
        function showToast(type, message) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            if (type === 'success') {
                toast.style.backgroundColor = '#d1fae5';
                toastMessage.style.color = '#059669';
            } else if (type === 'error') {
                toast.style.backgroundColor = '#fee2e2';
                toastMessage.style.color = '#dc2626';
            }
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }
    </script>
</body>
</html>