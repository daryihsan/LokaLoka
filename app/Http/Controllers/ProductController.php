<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Users;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Menampilkan homepage dengan produk terlaris dan rekomendasi (diambil dari DB).
     */
    public function index(Request $request)
    {
        // Ambil kategori untuk filter/sidebar di homepage
        $categories = Categories::all();

        // Ambil Produk Terlaris (Produk aktif & stok > 0)
        $hotProducts = Products::select('products.*', DB::raw('SUM(order_items.qty) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('is_active', true) // Produk aktif saja
            ->where('stock', '>', 0)   // Produk dengan stok > 0
            ->groupBy('products.id', 'products.name', 'products.price', 
                'products.description', 'products.image_url', 'products.weight', 'products.stock',
                'products.category_id', 'products.is_active', 'products.created_at', 'products.updated_at')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        // Ambil Produk Rekomendasi Terbaru (Produk aktif & stok > 0)
        $recommendedProducts = Products::with('category')
            ->where('is_active', true) // Produk aktif saja
            ->where('stock', '>', 0)   // Produk dengan stok > 0
            ->latest()
            ->take(12)
            ->get();

        // Ambil data user yang sedang login untuk ditampilkan di header
        $user = null;
        if (Session::has('user_id')) {
            $user = Users::find(Session::get('user_id'));
        }

        return view('homepage', compact('categories', 'hotProducts', 'recommendedProducts', 'user'));
    }

    /**
     * Menangani pencarian dan filter produk.
     */
    public function searchfilter(Request $request)
    {
        $query = Products::query()->with('category');

        // Filter: Hanya produk aktif dan stok > 0
        $query->where('is_active', true)->where('stock', '>', 0);

        // 1. Filter Keyword (q)
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 3. Filter Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // 4. Filter Lokasi (ASUMSI: Anda memiliki kolom 'location' di tabel products)
        if ($request->filled('location')) {
            // Jika kolom location tidak ada, ini akan menimbulkan error.
            // Asumsi: products.location ada
            $query->where('location', 'like', '%' . $request->location . '%'); 
        }
        
        // 5. Filter Rating (ASUMSI: Anda memiliki kolom 'rating' di tabel products atau relasi rating)
        if ($request->filled('min_rating')) {
             // Asumsi: products.rating ada
            $query->where('rating', '>=', $request->min_rating); 
        }

        // Sorting
        switch ($request->sort) {
            case 'newest':
                $query->latest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_desc':
                 // Asumsi: products.rating ada
                $query->orderBy('rating', 'desc');
                break;
            default:
                // Urutkan berdasarkan relevansi (pencocokan nama) atau default terbaru
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->appends($request->query());
        $categories = Categories::all();
        $total = $products->total();

        // Ambil data user yang sedang login untuk ditampilkan di header
        $user = null;
        if (Session::has('user_id')) {
            $user = Users::find(Session::get('user_id'));
        }

        // List Lokasi dummy (Ganti ini dengan data DB jika ada tabel lokasi)
        $locations = ['Semarang', 'Yogyakarta', 'Bandung', 'Jakarta']; 

        return view('searchfilter', compact('products', 'categories', 'user', 'total', 'locations'));
    }

    /**
     * Menampilkan halaman detail produk.
     */
    public function show($id)
    {
        $product = Products::with('category')->findOrFail($id);
        
        // Ambil data user yang sedang login untuk ditampilkan di header/button
        $user = null;
        if (Session::has('user_id')) {
            $user = Users::find(Session::get('user_id'));
        }
        
        return view('product', compact('product', 'user'));
    }
}