<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;
use function GuzzleHttp\Psr7\uri_for;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier'=>(int)$product->id,
            'title'=>(string)$product->product_name,
            'details'=>(string)$product->product_description,
            'stock'=>(int)$product->product_quantity,
            'situation'=>(string)$product->product_status,
            'picture'=>url("images/".$product->product_image),
            'seller'=>(int)$product->seller_id,
            'creationDate'=>(string)$product->created_at,
            'lastChange'=>(string)$product->updated_at,
            'deletedDate'=>isset($product->delete_at) ?(string)$product->deleted_at :null,
            'link'=>[
                [
                    'rel'=>'self',
                    'href'=>route('products.show',$product->id),
                ],
                [
                    'rel'=>'product.buyers',
                    'href'=>route('products.buyers.index',$product->id),
                ],
                [
                    'rel'=>'product.categories',
                    'href'=>route('products.categories.index',$product->id),
                ],

                [
                    'rel'=>'product.transactions',
                    'href'=>route('products.transactions.index',$product->id),
                ],
                [
                    'rel'=>'seller',
                    'href'=>route('sellers.show',$product->seller_id),
                ],




            ],
        ];
    }
    public static  function originalAttribute($index)
    {
        $attribute= [
            'identifier'=>"id",
            'title'=>'product_name',
            'details'=>'product_description',
            'stock'=>'product_quantity',
            'situation'=>'product_status',
            'picture'=>'product_image',
            'seller'=>'seller_id',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deletedDate'=>'deleted_at',

        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
