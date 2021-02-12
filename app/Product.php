<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use  SoftDeletes;
    const AVAILABLE='available';
    const UNAVAILABLE='unavailable';


    protected  $dates=['deleted_at'];

    protected $fillable=[
        'product_name',
        'product_description',
//        'product_price',
        'product_image',
        'product_status',
        'seller_id',
        'product_quantity',
    ];

    protected $hidden=[
        'pivot'
    ];

    public $transformer=ProductTransformer::class;

    public function isAvailable(){
        return $this->product_status ==Product::AVAILABLE;
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return  $this->hasMany(Transaction::class);
    }

}
