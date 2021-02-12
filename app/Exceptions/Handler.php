<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Traits\ApiResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{

    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException)
        {
            return  $this->convertValidationExceptionToResponse($exception,$request);
        }

        if($exception instanceof ModelNotFoundException)
        {
            $model=$exception->getModel();
            return $this->errorResponse('Does not exists any '.$model.' with specific identificator ',404);
        }

        if($exception instanceof  AuthenticationException){
            return  $this->unauthenticated($request,$exception);
        }

        if($exception instanceof  AuthorizationException)
        {
            return $this->errorResponse($exception->getMessage(),403);
        }

        if($exception instanceof NotFoundHttpException)
        {
            return  $this->errorResponse('the specific Url can not be Found ',404);
        }

        if($exception instanceof  MethodNotAllowedException)
        {
            return  $this->errorResponse('the specific method for that Url Request in invalid  ',405);
        }

        if($exception instanceof  QueryException)
        {
            $errorCode=$exception->errorInfo[1];
            if($errorCode==1451)
            return   $this->errorResponse('cannot remove data . its related with other resource  ',409);
        }

        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $error=$e->validator->errors()->getMessages();

        if($this->isFrontend($request)){
            return $request->ajax()? response()->json($error,422):redirect()->back()->withInput($request->input())->withError($error);
        }

        return $this->errorResponse($error,422);

     //   return parent::convertValidationExceptionToResponse($e, $request); // TODO: Change the autogenerated stub
    }




    protected function unauthenticated($request,AuthenticationException $exception)
    {
        if($this->isFrontend($request)){
            return redirect()->guest('login');
        }
        return $this->errorResponse('unauthenticated  ',401);

    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}