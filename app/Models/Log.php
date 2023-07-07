<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table= "logs";
    protected $guarded = [];

    public function logable()
    {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class,'created_by');
    }

    public function getCreatorName() {
        try {
            if($this->created_by) {
                if($this->user) {
                    return $this->user->name ?: $this->user->mobile;
                }
                return 'کاربر یافت نشد';
            }
            return 'سیستمی';
        } catch( Exception $e) {
            return 'نامشخص';
        }
    }
}
