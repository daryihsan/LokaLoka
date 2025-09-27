<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\CartItem;
use App\Models\Products;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Check if user is logged in
     */
    private function checkAuth()
    {
        return Session::has('logged_in') && Session::get('logged_in') === true;
    }

    /**
     * Get or create cart for user
     */
    private function getUserCart()
    {
        $userId = Session::get('user_id');
        return Carts::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $product = Products::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $cart = Carts::firstOrCreate([
            'user_id' => Session::get('user_id')
        ]);

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id
            ],
            [
                'qty' => DB::raw('qty + ' . $request->quantity)
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart'
        ]);
    }

    /**
     * Show cart
     */
    public function showCart()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('login');
        }

        $user = Users::find(Session::get('user_id'));
        $cart = $this->getUserCart();
        $cartItems = CartItem::where('cart_id', $cart->id)
                            ->with('product.category')
                            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->qty;
        });

        return view('cart-blade-complete', compact('cartItems', 'total', 'user'));
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        $cart = $this->getUserCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('id', $id)
                           ->with('product')
                           ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item tidak ditemukan'], 404);
        }

        $quantity = $request->quantity;

        if ($cartItem->product->stock < $quantity) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 400);
        }

        $cartItem->update(['qty' => $quantity]);

        return response()->json([
            'success' => 'Kuantitas berhasil diupdate',
            'subtotal' => $cartItem->product->price * $quantity
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        $cart = Carts::where('user_id', Session::get('user_id'))->first();
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $deleted = CartItem::where('cart_id', $cart->id)
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Item not found'], 404);
    }

    /**
     * Get cart count for header
     */
    public function getCartCount()
    {
        if (!$this->checkAuth()) {
            return response()->json(['count' => 0]);
        }

        $cart = $this->getUserCart();
        $count = CartItem::where('cart_id', $cart->id)->sum('qty');

        return response()->json(['count' => $count]);
    }

    /**
     * Clear cart (after successful checkout)
     */
    public function clearCart()
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getUserCart();
        CartItem::where('cart_id', $cart->id)->delete();

        return response()->json(['success' => 'Keranjang berhasil dikosongkan']);
    }

    /**
     * Get cart items for checkout
     */
    public function getCartItems()
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getUserCart();
        $cartItems = CartItem::where('cart_id', $cart->id)
                            ->with('product')
                            ->get();

        $items = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->qty,
                'subtotal' => $item->product->price * $item->qty,
                'image_url' => $item->product->image_url,
                'stock' => $item->product->stock
            ];
        });

        $total = $items->sum('subtotal');

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => $items->sum('quantity')
        ]);
    }

    /**
     * API endpoint to get cart items with full details
     */
    public function getCartItems()
    {
        if (!$this->checkAuth()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cart = $this->getUserCart();
        $cartItems = CartItem::where('cart_id', $cart->id)
                            ->with('product.category')
                            ->get();

        $items = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->qty,
                'subtotal' => $item->product->price * $item->qty,
                'image_url' => $item->product->image_url,
                'stock' => $item->product->stock,
                'category' => $item->product->category->name ?? null
            ];
        });

        $total = $items->sum('subtotal');
        $totalQuantity = $items->sum('quantity');

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => $totalQuantity,
            'success' => true
        ]);
    }

    public function show()
    {
        $cart = Carts::with(['cartItems.product'])
            ->where('user_id', Session::get('user_id'))
            ->first();

        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Products::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return back()->withErrors(['stock' => 'Insufficient stock']);
        }

        $cart = Carts::firstOrCreate([
            'user_id' => Session::get('user_id')
        ]);

        CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $request->product_id
            ],
            [
                'qty' => DB::raw('qty + ' . $request->quantity)
            ]
        );

        return back()->with('success', 'Product added to cart');
    }

    public function checkout(Request $request)
    {
        $cart = Carts::with(['cartItems.product'])
            ->where('user_id', Session::get('user_id'))
            ->first();

        $order = DB::transaction(function() use ($cart, $request) {
            $total = $cart->cartItems->sum(function($item) {
                return $item->qty * $item->product->price;
            });

            $order = Orders::create([
                'user_id' => Session::get('user_id'),
                'total' => $total,
                'status' => 'diproses',
                'payment_method' => $request->payment_method,
                'address_text' => Session::get('alamat_penerima')
            ]);

            foreach ($cart->cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->product->price,
                    'subtotal' => $item->qty * $item->product->price
                ]);

                // Update stock
                $item->product->decrement('stock', $item->qty);
            }

            // Clear cart
            $cart->cartItems()->delete();
            $cart->delete();

            return $order;
        });

        if ($request->payment_method === 'qris') {
            return redirect()->route('payment.qris', $order->id);
        }

        return redirect()->route('orders')->with('success', 'Order placed successfully');
    }
}