<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\Parent_;

class BuyerCategoriesController extends ApiController
{

    public  function  __construct()
    {
        Parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
       $categories=$buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
       ->collapse()
       ->unique('id')
       ->values();


       return $this->showAll($categories);
    }


}
