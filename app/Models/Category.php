<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 'category_parent_id',
    ];

    public function books()
    {
        return $this->hasMany('App\Models\Book', 'category_id');
    }
    
    public function categoriesChildren()
    {
        return $this->hasMany('App\Models\Category', 'category_parent_id');
    }

    public function setCategoryParentIdAttribute($parentId) {
        return $this->attributes['category_parent_id'] = $parentId ? $parentId : null;
    }

    public function delete()
    {
        try {
            parent::delete();
            $this->books()->delete();

            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
}
