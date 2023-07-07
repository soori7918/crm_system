<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Factor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'factors';
    
    static $types = [
        'input' => 'درآمد ',
        'output' => 'هزینه'
    ];


    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by' );
    }

    public function editor()
    {
        return $this->belongsTo(User::class , 'updated_by' );
    }

    public function getTypeTitle()
    {
        try{
            return Self::$types[$this->type];
        } catch(Exception $e)
        {
            return 'نامشخص';
        }
    }

   
    public function wallet()
    {
        return $this->belongsTo(Wallet::class , 'wallet_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(FactorItem::class , 'factor_id');
    }


    public function payments()
    {
        return $this->hasMany(FactorPayment::class , 'factor_id');
    }

    public function getCustomerName()
    {
        try {
            return $this->customer->name;
        } catch(Exception $e) {
            return 'نامشخص';
        }
    }

    public function getCreatorName()
    {
        try {
            return $this->creator->name;
        } catch(Exception $e) {
            return 'نامشخص';
        }
    }
    public function getUserUpdatedName()
    {
        try {
            return $this->editor->name;
        } catch(Exception $e) {
            return 'نامشخص';
        }
 
    }

    public function getTotalPrice()
    {
        return $this->items()->sum('price');
    }

    public function getPaidAmount()
    {
        return $this->payments()->where('is_done',true)->sum('price');
    }
    public function getNotPaidAmount()
    {
        return $this->payments()->where('is_done',false)->sum('price') ;
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'logable');
    }

   

}
