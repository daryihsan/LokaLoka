<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Users;
use App\Models\Orders;
use Illuminate\Support\Facades\Session;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dan mengambil semua data dari database.
     */
    public function dashboard(Request $request)
    {
        $section = $request->query('section', 'overview');

        // *************** 1. STATISTIK UTAMA ***************
        $stats = [
            'total_products' => Products::count(),
            'total_orders' => Orders::count(),
            'total_customers' => Users::where('role', 'customer')->count(),
            'unapproved_users' => Users::where('approved', '0')->count(), 
            'total_revenue' => Orders::where('status', 'selesai')->sum('total'), 
            'pending_orders' => Orders::where('status', 'diproses')->count(),
        ];
        
        // *************** 2. DATA TABEL & VIEW ***************
        
        $products = Products::with('category')->paginate(10)->appends($request->all());
        $categories = Categories::withCount('products')->paginate(10)->appends($request->all());
        $orders = Orders::with('user')->latest()->paginate(10)->appends($request->all());
        
        // Mengambil semua user (termasuk admin)
        $users = Users::paginate(10)->appends($request->all()); 
        $recentOrders = Orders::with('user')->latest()->take(5)->get(); 

        // *************** 3. DATA CHART ***************
        $salesByCategory = Orders::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.qty * order_items.price) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->get();
        
        // ** BARU: Data Registrasi Pengguna Baru per Bulan **
        $newUsersMonthly = Users::select(
            DB::raw('COUNT(id) as total_users'),
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month_year') // Mengelompokkan per bulan
        )
        ->where('role', 'customer') // Hanya hitung customer
        ->groupBy('month_year')
        ->orderBy('month_year', 'asc')
        ->get();

        $pendingUsers = Users::where('approved', '0')->latest()->take(5)->get(); // PERBAIKAN: Ambil data pending

        // *************** 4. KIRIM DATA KE VIEW ***************
        return view('admin.dashboard', compact(
            'section',
            'stats',
            'products',
            'categories',
            'users',
            'orders',
            'recentOrders',
            'salesByCategory',
            'newUsersMonthly',
            'pendingUsers'
        ));
    }


    // =========================================================
    // CRUD PRODUCTS (Tidak berubah)
    // =========================================================

    public function storeProduct(Request $request)
    {
        // ... (kode storeProduct tetap sama)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048', 
        ]);
        Products::create($validated);
        return Redirect::route('admin.dashboard', ['section' => 'products'])->with('success', 'Produk berhasil ditambahkan!');
    }

    public function updateProduct(Request $request, $id)
    {
        // ... (kode updateProduct tetap sama)
        $product = Products::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048', 
            'is_active' => 'nullable|boolean',
        ]);
        $data = $validated;
        $data['is_active'] = $request->input('is_active', 0); 
        $product->update($data);
        return Redirect::route('admin.dashboard', ['section' => 'products'])->with('success', 'Produk berhasil diperbarui!');
    }

    public function deleteProduct($id)
    {
        // ... (kode deleteProduct tetap sama)
        $product = Products::findOrFail($id);
        $product->delete();
        return Redirect::route('admin.dashboard', ['section' => 'products'])->with('success', 'Produk berhasil dihapus.');
    }


    // =========================================================
    // CRUD CATEGORIES (Tidak berubah)
    // =========================================================

    public function storeCategory(Request $request)
    {
        // ... (kode storeCategory tetap sama)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name', 
        ]);
        Categories::create($validated);
        return Redirect::route('admin.dashboard', ['section' => 'categories'])->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, $id)
    {
        // ... (kode updateCategory tetap sama)
        $category = Categories::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)], 
        ]);
        $category->update($validated);
        return Redirect::route('admin.dashboard', ['section' => 'categories'])->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteCategory($id)
    {
        // ... (kode deleteCategory tetap sama)
        $category = Categories::findOrFail($id);
        $category->delete(); 
        return Redirect::route('admin.dashboard', ['section' => 'categories'])->with('success', 'Kategori berhasil dihapus.');
    }


    // =========================================================
    // CRUD USERS (Fungsi Update diperkuat)
    // =========================================================

    public function updateUser(Request $request, $id)
    {
        $user = Users::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Pastikan role dikirimkan dari form (admin/customer)
            'role' => ['required', 'string', Rule::in(['admin', 'customer'])], 
            'status' => ['nullable', 'string', Rule::in(['pending', 'approved'])], // Tambahkan status
        ]);

        $user->update($validated);

        return Redirect::route('admin.dashboard', ['section' => 'users'])->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function deleteUser($id)
    {
        // ... (kode deleteUser tetap sama)
        $user = Users::findOrFail($id);
        $user->delete();
        return Redirect::route('admin.dashboard', ['section' => 'users'])->with('success', 'Pengguna berhasil dihapus.');
    }

    public function approveUser($id)
    {
        // Pengecekan otorisasi admin (asumsi session check di awal file ini)
        if (Session::get('user_role') !== 'admin') {
            return redirect()->route('homepage')->withErrors(['auth' => 'Unauthorized access.']);
        }
        
        $user = Users::findOrFail($id);
        
        // PERBAIKAN: Menggunakan update dan mengembalikan hasil boolean dari update
        $updated = $user->update([
            'approved' => 1, 
            'role' => 'customer'
        ]); 

        if ($updated) {
            return Redirect::route('admin.dashboard', ['section' => 'users'])
                           ->with('success', "Pengguna berhasil disetujui (Approved) sebagai Customer.");
        } else {
            return Redirect::route('admin.dashboard', ['section' => 'users'])
                           ->with('error', "Gagal memperbarui database. Pastikan kolom 'status' dan 'approved' ada di \$fillable Model Users.");
        }
    }

    // =========================================================
    // ORDER MANAGEMENT (Tidak berubah)
    // =========================================================

    public function updateOrderStatus(Request $request, $id)
    {
        // ... (kode updateOrderStatus tetap sama)
        $order = Orders::findOrFail($id);
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['diproses', 'dikirim', 'selesai', 'dibatalkan'])], 
        ]);
        $order->update($validated);
        return Redirect::route('admin.dashboard', ['section' => 'orders'])->with('success', "Status pesanan #{$id} berhasil diubah menjadi {$validated['status']}.");
    }
}