<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Seller;
class SellerController extends ApiController
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
    public function index()
    {
       $seller=Seller::has('products')->get();


       return $this->showAll($seller);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $seller=Seller::has('products')->findOrFail($id);

       return $this->showOne($seller);
    }


}
