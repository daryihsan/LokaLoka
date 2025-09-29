<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->with('category')
                         ->where('is_active', true)
                         ->where('stock', '>', 0)
                         ->paginate(12);

        $categories = Categories::all();

        return view('searchfilter', compact('products', 'categories'));
    }
    public function searchfilter(Request $request)
    {
        $query = Products::with('category');

        // Parameter pencarian dari form
        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%");
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // if ($request->location) {
        //     $query->where('location', 'like', "%{$request->location}%");
        // }

        // if ($request->min_rating) {
        //     $query->where('rating', '>=', $request->min_rating);
        // }

        // Pengurutan berdasarkan sort
        $sort = $request->sort;
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            // case 'rating_desc':
            //     $query->orderBy('rating', 'desc');
            //     break;
            default:
                $query->orderBy('created_at', 'desc'); // Default: terbaru
                break;
        }

        $products = $query->paginate(12);
        $categories = Categories::all();
        $total = $products->total(); // Untuk tampilkan jumlah hasil di blade

        return view('searchfilter', compact('products', 'categories', 'total'));
    }

    public function show($id)
    {
        $product = Products::with('category')->findOrFail($id);
        return view('product_detail', compact('product'));
    }
}