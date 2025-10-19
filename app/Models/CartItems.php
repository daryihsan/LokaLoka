<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'qty',
    ];

    /**
     * Get the cart that owns the cart item.
     */
    public function cart()
    {
        return $this->belongsTo(Carts::class);
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}