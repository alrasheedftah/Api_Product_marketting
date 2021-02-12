<?php

namespace App;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    // protected $table=""
 protected $fillable=[
     'category_name',
     'category_description',
 ];

 public  $transformer=CategoryTransformer::class;

protected $hidden=[
  'pivot'
];
    protected  $dates=['deleted_at'];

 public function products(){
     return $this->belongsToMany(Product::class);
 }


}
