<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $table = "return_items";
    protected $guarded = [];

    public function product_change()
    {
        return $this->belongsTo(ProductChange::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

 
}
