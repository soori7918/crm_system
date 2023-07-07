<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "products";

    public function getImage()
    {
        return $this->image ?: 'images/placeholder.png';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }


    public function product_changes()
    {
        return $this->hasMany(ProductChange::class, 'product_id');
    }


    public function product_change_items()
    {
        return $this->hasMany(ProductChangeItem::class, 'product_id');
    }

    public function factors()
    {
        return $this->belongsTo(Factor::class);
    }
    public function factor_item()
    {
        return $this->belongsTo(FactorItem::class , 'product_id');
    }
}
