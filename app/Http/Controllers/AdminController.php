<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Orders::count(),
            'total_revenue' => Orders::where('status', 'selesai')->sum('total'),
            'total_products' => Products::count(),
            'total_customers' => Users::where('role', 'customer')->count()
        ];

        $recentOrders = Orders::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('categories.name')
            ->get();

        return view('adminDash', compact('stats', 'recentOrders', 'salesByCategory'));
    }

    public function products()
    {
        $products = Products::with('category')->paginate(10);
        $categories = Categories::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        $validated['image_url'] = $imagePath;

        Products::create($validated);
        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated');
    }
}