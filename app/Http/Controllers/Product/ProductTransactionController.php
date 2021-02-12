<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductTransactionController extends ApiController
{

    public  function  __construct()
    {
        // Parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
    
       $transactions=$product->transactions;
    //    foreach($transactions as $t){
    //    if($t->id==559)
    //     return response()->json($t);
    //    }
       return  $this->showAll($transactions);
    // return response()->json(array());
    }


}
