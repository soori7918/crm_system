<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactorItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=[];

    static $models = [
        'App\Product' => 'محصولات'
    ];

    public function factor()
    {
        return $this->belongsTo(Factor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
