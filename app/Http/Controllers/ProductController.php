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
}