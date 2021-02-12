<?php

namespace App\Transformers;

use App\Buyer;
use App\Seller;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier'=>(int)$buyer->id,
            'name'=>(string)$buyer->name,
            'email'=>(string)$buyer->email,
            'isVerified'=>(int)$buyer->verified,
            'creationDate'=>(string)$buyer->created_at,
            'lastChange'=>(string)$buyer->updated_at,
            'deletedDate'=>isset($buyer->delete_at) ?(string)$buyer->deleted_at :null,
            'link'=>[
                [
                    'rel'=>'self',
                    'href'=>route('buyers.show',$buyer->id),
                ],
                [
                    'rel'=>'buyers.sellers',
                    'href'=>route('buyers.sellers.index',$buyer->id),
                ],
                [
                    'rel'=>'buyer.categories',
                    'href'=>route('buyers.categories.index',$buyer->id),
                ],

                [
                    'rel'=>'buyer.transactions',
                    'href'=>route('buyers.transactions.index',$buyer->id),
                ],
                [
                    'rel'=>'buyer.products',
                    'href'=>route('buyers.products.index',$buyer->id),
                ],

            ]
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


    public static  function transformAttribute($index)
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
