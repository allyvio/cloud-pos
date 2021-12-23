<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_name', 'price', 'id', 'stock', 'created_by'];

    public $incrementing = true;

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
    public function toko()
    {
        return $this->belongsToMany('App\Toko');
    }
}
