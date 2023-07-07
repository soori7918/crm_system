<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductChange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $table = "product_changes";

    public static $types = [
        'enter' => 'ورود کالا',
        'exit' => 'خروج کالا',
        'return' => 'خروج موقت کالا',
    ];

    public function getTypeTitle()
    {
        try {
            return Self::$types[$this->type];
        } catch (Exception $e) {
            return 'نامشخص';
        }
    }




    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function items()
    {
        return $this->hasMany(ProductChangeItem::class, 'doc_id', 'id');
    }

    public function getCreatorName()
    {
        try{
            return $this->creator->name;
        }catch(Exception $e){
            return 'نامشخص';
        }
    }
    public function getEditorName()
    {
        try{
            return $this->editor->name;
        }catch(Exception $e){
            return 'نامشخص';
        }
    }
    public function getCustomerName()
    {
        try{
            return $this->customer->name;
        }catch(Exception $e){

            return 'نامشخص';
        }
        
        
    }

  
    public function logs()
    {
        return $this->morphMany(Log::class, 'logable');
    }


    public function return_items()
    {
        return $this->hasMany(ReturnItem::class , 'doc_id', 'id');
    }

    public function getShowRoute()
    {
        return route('panel.inventory.productChanges.'.$this->type.'.show',$this);
    }
    public function getEditRoute()
    {
        return route('panel.inventory.productChanges.'.$this->type.'.edit',$this);
    }
}
