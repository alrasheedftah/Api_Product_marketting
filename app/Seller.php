<?php

namespace App;

use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Seller extends User
{


    public $transformer=SellerTransformer::class;

    public function products(){
        return $this->hasMany(Product::class);
    }


}
