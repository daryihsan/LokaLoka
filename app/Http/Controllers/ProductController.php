<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();

        // Kata kunci dari 'q'
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%");
                if (Schema::hasColumn('products', 'description')) {
                    $sub->orWhere('description', 'like', "%{$q}%");
                }
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Filter lokasi
        if ($request->filled('location') && Schema::hasColumn('products', 'location')) {
            $query->where('location', 'like', '%' . $request->input('location') . '%');
        }

        // Filter rating minimal
        if ($request->filled('min_rating') && Schema::hasColumn('products', 'rating')) {
            $query->where('rating', '>=', (float) $request->input('min_rating'));
        }

        // Sorting
        switch ($request->input('sort')) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating_high':
                if (Schema::hasColumn('products', 'rating')) {
                    $query->orderBy('rating', 'desc');
                }
                break;
            default:
                // default order (tidak diubah)
                break;
        }

        $products = $query->with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->paginate(12)
            ->appends($request->query()); // pertahankan query-string saat paginate

        $categories = Categories::all();

        return view('searchfilter', compact('products', 'categories'));
    }
}