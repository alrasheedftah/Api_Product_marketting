<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier'=>(int)$transaction->id,
            'quantity'=>(int)$transaction->quantity,
            'buyer'=>(int)$transaction->buyer_id,
            'product'=>(int)$transaction->product_id,
            'creationDate'=>(string)$transaction->created_at,
            'lastChange'=>(string)$transaction->updated_at,
            'deletedDate'=>isset($transaction->delete_at) ?(string)$transaction->deleted_at :null,
            'link'=>[
                [
                    'rel'=>'self',
                    'href'=>route('transactions.show',$transaction->id),
                ],
                [
                    'rel'=>'buyer',
                    'href'=>route('buyers.transactions.index',$transaction->buyer_id),
                ],
                [
                    'rel'=>'transaction.categories',
                    'href'=>route('transactions.category.index',$transaction->id),
                ],

                [
                    'rel'=>'product',
                    'href'=>route('products.transactions.index',$transaction->product_id),
                ],
                [
                    'rel'=>'transaction.seller',
                    'href'=>route('transactions.sellers.index',$transaction->id),
                ],
                ],

            ];
    }

    public static  function originalAttribute($index)
    {
        $attribute= [
            'identifier'=>'id',
            'quantity'=>'quantity',
            'buyer'=>'buyer_id',
            'product'=>'product_id',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deletedDate'=>'deleted_at'
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
