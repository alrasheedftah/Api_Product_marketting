<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends ApiController
{

    public function __construct()
    {
//        Parent::__construct();
            // $this->middleware('client.credentials')->only(['index','show']);
            // $this->middleware('auth:api')->except(['store','update']);
            // $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store','update']);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=   Category::all();
       return $this->showAll($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role=[
            'category_name'  =>'required',
            'category_description'=>'required',
        ];

        $this->validate($request,$role);

        $category=Category::create($request->all());

        return $this->showOne($category,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
//        if($request->has('category_name')){
//            $category->category_name=$request->category_name;
//        }
//
//        if($request->has('category_description')){
//            $category->category_description=$request->category_description;
//        }

        $category->fill($request->only([
            'category_name',
            'category_description'
        ]));

        if($category->isClean())
            return $this->errorResponse('Sory u should specific diffrent Values ',422);

        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
