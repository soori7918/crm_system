<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactorHistory extends Model
{
    use HasFactory;

    protected $table = 'factors_history';

    protected $guarded = [];
}
