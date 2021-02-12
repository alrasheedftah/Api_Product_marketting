<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\Transformers\BuyerTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        Parent::__construct();
        $this->middleware('transform.input:'.BuyerTransformer::class)->only(['store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,User $buyer)
    {
        $role=[
            'quantity'=>'required|integer|min:1'
        ];

        
        $this->validate($request,$role);

        if($buyer->id == $product->seller_id)
            return  $this->errorResponse('the buyer must be different from seller ',409);

        if(!$buyer->isVerified())
            return $this->errorResponse('the buyer must be a verified users',409);

        if(!$product->seller->isVerified())
            return $this->errorResponse('the seller must be a verified user',409);

        if(!$product->isAvailable())
            return $this->errorResponse('the product is not available ',409);
        if($product->product_quantity < $request->quantity)
            return $this->errorResponse('the product does not have enough units for this transaction',409);

        return DB::transaction(function ()use ($request,$product,$buyer){
            $product->product_quantity -=$request->quantity;
            $product->save();

          $transaction= Transaction::create([
                'quantity'=>$request->quantity,
                'buyer_id'=>$buyer->id,
                'product_id'=>$product->id,
            ]);

          return $this->showOne($transaction);
        });



    }

}
