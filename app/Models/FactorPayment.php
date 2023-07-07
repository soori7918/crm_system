<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactorPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    static $types =[
        'naghdi' => 'نقدی',
        'check' => 'چک',
        'aghsat' => 'اقساط'
    ];


    public static function staticGettypeTitle(string $type)
    {
        try
        {
            return Self::$types[$type];
        }
        catch(Exception $e)
        {
            return 'نامشخص';
        }
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

   

    public function factor()
    {
        return $this->belongsTo(Factor::class , 'factor_id' , 'id');
    }

}
