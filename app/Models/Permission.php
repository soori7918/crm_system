<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Model;

class Permission extends SpatiePermission
{
    protected $table = 'permissions';
    protected $guarded = [];
    public function group()
    {
        return $this->belongsTo('App\Models\PermissionGroup','group_id');
    }
}
