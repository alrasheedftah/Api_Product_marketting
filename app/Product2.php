<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product2 extends Model
{
    //

    protected $fillable=['prd_name','tt',
''];

function categories(){
    return $this->belongsToMany(Category::class);
}

function seller(){
    return $this->belongsTo(Seller::class);
}



}
