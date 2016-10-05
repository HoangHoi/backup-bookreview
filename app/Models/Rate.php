<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
    protected $fillable = [
        'user_id', 'book_id', 'point',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book', 'book_id');
    }
}
