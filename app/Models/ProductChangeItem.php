<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductChangeItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "product_change_items";

    public function document()
    {
        return $this->belongsTo(ProductChange::class, 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

  
}
