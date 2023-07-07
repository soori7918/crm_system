<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletChange extends Model
{
    use HasFactory;

    protected $table= 'wallet_changes';
    protected $guarded =[];

    public function factors()
    {
        return $this->belongsTo(Factor::class , 'factor_id');
    }
    
    public function wallets()
    {
        return $this->belongsTo(Wallet::class , 'wallet_id');
    }
}
