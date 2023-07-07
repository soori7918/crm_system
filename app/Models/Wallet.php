<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
    protected $guarded = [];

    public static $types =[
        'naghdi' => 'نقدی',
        'check' => 'چک',
        'aghsat' => 'اقساط'
    ];

    public function getAmount()
    {
        return $this->amount ? number_format($this->amount) . 'تومان' : '---';
    }

    public function getTypeTitle()
    {
        try
        {
            return Self::$types[$this->type];
        }
        catch(Exception $e)
        {
            return 'نامشخص';
        }
    }

    public function factors()
    {
        return $this->belongsTo(Factor::class);
    }
    
    public function walletChanges()
    {
        return $this->hasMany(walletChange::class , 'wallet_id');
    }

    public function todayAmountIncrease()
    {
        return $this->walletchanges->where('type' , 'input')
            ->whereBetween('created_at', [Carbon::today()->addDays(-1), Carbon::today()->addDays(1)])->sum('price');
    }

    
    public function weekAmountIncrease()
    {
        return $this->walletchanges->where('type' , 'input')
            ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)])->sum('price');
    }
    public function monthAmountIncrease()
    {
        return $this->walletchanges->where('type' , 'input')
            ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)])->sum('price');
    }
    
    public function todayAmountDecrease()
    {
        return $this->walletchanges->where('type' , 'output')
            ->whereBetween('created_at', [Carbon::today()->addDays(-1), Carbon::today()->addDays(1)])->sum('price');
    }

    public function weekAmountDecrease()
    {
        return $this->walletchanges->where('type' , 'output')
            ->whereBetween('created_at', [Carbon::today()->addDays(-7), Carbon::today()->addDays(7)])->sum('price');
    }

    public function monthAmountDecrease()
    {
        return $this->walletchanges->where('type' , 'output')
            ->whereBetween('created_at', [Carbon::today()->addDays(-30), Carbon::today()->addDays(30)])->sum('price');
    }
}
