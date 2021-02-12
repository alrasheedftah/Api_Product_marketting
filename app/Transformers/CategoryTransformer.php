<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier'=>(int)$category->id,
            'title'=>(string)$category->category_name,
            'details'=>(string)$category->category_description,
            'creationDate'=>(string)$category->created_at,
            'lastChange'=>(string)$category->updated_at,
            'deletedDate'=>isset($category->delete_at) ?(string)$category->deleted_at :null,
            'link'=>[
              [
                  'rel'=>'self',
                  'href'=>route('categories.show',$category->id),
              ],
                [
                    'rel'=>'category.buyers',
                    'href'=>route('categories.buyers.index',$category->id),
                ],
                [
                'rel'=>'category.products',
                'href'=>route('categories.products.index',$category->id),
            ],
                [
                    'rel'=>'category.sellers',
                    'href'=>route('categories.sellers.index',$category->id),
                ],
                [
                    'rel'=>'category.transactions',
                    'href'=>route('categories.transactions.index',$category->id),
                ],




            ],
        ];
    }


    public static  function originalAttribute($index)
    {
        $attribute= [
            'identifier'=>"id",
            'title'=>'category_name',
            'details'=>'category_description',
            'creationDate'=>'created_at',
            'lastChange'=>'updated_at',
            'deletedDate'=>'deleted_at',
        ];

        return isset($attribute[$index])?$attribute[$index]:null;
    }
}
