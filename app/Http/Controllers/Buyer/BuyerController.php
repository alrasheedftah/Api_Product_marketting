<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
class BuyerController extends ApiController
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
        $buyer=Buyer::has('transactions')->get();

        return $this->showAll($buyer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
      //  $buyer=Buyer::has('transactions')->findOrFail($id);

        return $this->showOne($buyer);
    }

}
