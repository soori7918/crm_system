<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "customers";

    public function product_changes()
    {
        return $this->hasMany(ProductChange::class, 'customer_id');
    }

    
}
