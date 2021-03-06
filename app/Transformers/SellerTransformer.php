<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier'=>(int)$seller->id,
            'name'=>(string)$seller->name,
            'email'=>(string)$seller->email,
            'isVerified'=>(int)$seller->verified,
            'creationDate'=>(string)$seller->created_at,
            'lastChange'=>(string)$seller->updated_at,
            'deletedDate'=>isset($seller->delete_at) ?(string)$seller->deleted_at :null,
            'link'=>[
                [
                    'rel'=>'self',
                    'href'=>route('sellers.show',$seller->id),
                ],
                [
                    'rel'=>'seller.buyers',
                    'href'=>route('sellers.buyers.index',$seller->id),
                ],
                [
                    'rel'=>'seller.categories',
                    'href'=>route('sellers.categories.index',$seller->id),
                ],

                [
                    'rel'=>'seller.transactions',
                    'href'=>route('sellers.transactions.index',$seller->id),
                ],
                [
                    'rel'=>'seller.products',
                    'href'=>route('sellers.products.index',$seller->id),
                ],

            ],
        ];
    }

    public static  function originalAttribute($index)
    {
        $attribute= [
            'identifier'=>'id',
            'name'=>'name',
            'email'=>'email',
            'isVerified'=>'verified',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deletedDate'=>'deleted_at' ,
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
