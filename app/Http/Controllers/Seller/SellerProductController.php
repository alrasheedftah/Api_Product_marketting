<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transformers\SellerTransformer;
use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{

    public function __construct()
    {
        // Parent::__construct();
        // $this->middleware('transform.input:'.SellerTransformer::class)->only(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products=$seller->products;

        return $this->showAll($products);


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller)
    {
        $role=[
            'product_name'=>'required',
            'product_description'=>'required',
            'product_image'=>'required|image',
            'product_quantity'=>'required|integer|min:1'
        ];

        $this->validate($request,$role);
 
        $data=$request->all();
        $data['product_status']=Product::UNAVAILABLE;
        $data['product_image']=$request->product_image->store('');
        $data['seller_id']=$seller->id;

        $product=Product::create($data);
 
        return $this->showOne($product);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Product $product)
    {
        $role=[
            'product_quantity'=>'integer|min:1',
            'product_status'=>'in:'.Product::UNAVAILABLE.','.Product::AVAILABLE,
            'image'=>'image',
        ];

        $this->validate($request,$role);

        $this->checkSeller($seller,$product);

        $product->fill($request->only(
            'product_description',
            'product_name',
            'product_quantity'
        ));

        if($request->has('product_status'))
        {
            $product->product_status=$request->product_status;

            if($product->isAvailable() && $product->categories()->count()==0)
            {
                return $this->errorResponse('an active product must have at least one category ',409);

            }


        }

        if($request->hasFile($request->product_image)){
            Storage::delete($product->product_image);
            $product->product_image=$request->product_image->store('');
        }

        if($product->isClean())
            return  $this->errorResponse('you need to specify a different value to update ',422);

        $product->save();
        return $this->showOne($product);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Product $product)
    {
        $this->checkSeller($seller,$product);

        $product->delete();
        Storage::delete($product->product_image);

        return $this->showOne($product);

    }

    private function checkSeller(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id)
            throw new HttpException(422,'the specific seller is not actual seller of the product');
    }
}
