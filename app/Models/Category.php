<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "categories";


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function hasParent($parent_id)
    {
        if ($parent_id == null) {
            return false;
        }

        if ($this->parent_id == null) {
            return false;
        }
        if ($this->parent_id == $parent_id) {
            return true;
        }
        return $this->parent->hasParent($parent_id);
    }

    public function getLevelAttribute()
    {
        $category = $this;
        $level = 0;
        while ($category->parent) {
            $level++;
            $category = $category->parent;
        }
        return $level;
    }
    public function getParent()
    {
        if ($this->parent_id == null) {
            return 'ندارد';
        } else {
            return $this->parent->name;
        }
    }

    public function getRoute()
    {
        return '#';
        // return route('categories.show', $this->slug);
    }

    public function products()
    {
        return $this->hasMany(Category::class, 'category_product', 'product_id', 'category_id');
    }
}
