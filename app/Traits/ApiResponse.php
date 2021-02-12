<?php


namespace App\Traits;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

trait ApiResponse
{
	private function successResponse($data,$code)
	{
		return response()->json($data,$code);
	}


	protected function errorResponse($message,$code)
	{
		return response()->json(['error'=>$message,'code'=>$code],$code);
	}

	protected function showAll(Collection $collection,$code=200)
	{
        if($collection->isEmpty()){
            return $this->successResponse($collection,$code);
        }

	    $transformer=$collection->first()->transformer;

        $collection=$this->filterData($collection,$transformer);
        $collection=$this->sortData($collection,$transformer);
        $collection=$this->paginate($collection);

 	   $collection= $this->transformData($collection,$transformer);
         $collection= $this->cacheResponse($collection);

		return $this->successResponse($collection,$code);
	}


	protected function showOne(Model $instance,$code=200)
	{
        $transformer=$instance->transformer;

        $instance=$this->transformData($instance,$transformer);


		return $this->successResponse($instance,$code);
	}

	protected function showMessage($message,$code=200)
    	{
    		return $this->successResponse($message,$code);
    	}


    	protected function transformData($data ,$transformer){

    	$trasformation=fractal($data,new $transformer);

    	return $trasformation->toArray();

    	}

    	protected function sortData(Collection $collection,$transformer){
    	    if(request()->has('sort_by')){
    	        $attribute=$transformer::originalAttribute(request()->sort_by);
    	        $collection=$collection->sortBy->{$attribute};

    	    }

    	    return $collection;

    	}

    	protected function filterData(Collection $collection,$transformer)
    	{
    	    foreach(request()->query() as $query =>$value){
    	        $attribute=$transformer::originalAttribute($query);

    	        if(isset($attribute,$value)){
    	            $collection=$collection->where($attribute,$value);
    	        }
    	    }

    	    return $collection;
    	}


     protected function paginate(Collection $collection){

        $role=[
        'pre_page'=>'integer|min:2|max:50',
        ];

        Validator::validate(request()->all(),$role);


        $page=LengthAwarePaginator::resolveCurrentPage();

        $prePage=15;
        if(request()->has('pre_page')){
            $prePage=(int) request()->pre_page;
        }
        $result=$collection->slice(($page-1)*$prePage,$prePage)->values();

        $paginated=new LengthAwarePaginator($result,$collection->count(),$prePage,$page,[
        'path'=>LengthAwarePaginator::resolveCurrentPage(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;

     }

    public function cacheResponse($data){
        $url=request()->url();
        $queryParams=request()->query();

        ksort($queryParams);

        $queryString=http_build_query($queryParams);
        $fullUrl="{$url}?{$queryString}";
        return Cache::remember($fullUrl,30/60,function () use ($data){
                return $data;
                });
    }

}






?>
