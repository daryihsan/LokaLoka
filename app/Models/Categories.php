<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = "categories";
    protected $fillable = [
        'name',
    ];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'category_id');
    }
}