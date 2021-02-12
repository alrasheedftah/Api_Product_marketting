<?php

namespace App\Http\Controllers\User;

use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Parent_;

class UserController extends ApiController
{

    public function __construct()
    {
//        Parent::__construct();

        $this->middleware('client.credentials')->only(['store','resent']);
        $this->middleware('auth:api')->except(['store','verify','resent']);
        $this->middleware('transform.input:'.UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user=User::all();
       return $this->showAll($user);
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
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|confirmed',

        ];

        $this->validate($request,$role);

        $data=$request->all();

        $data['password']=bcrypt($request->password);
        $data['verified']=User::UNVERIFIED_USER;
        $data['verification_token']=User::generateVerificationCode();
        $data['admin']=User::REGULAR_USER;


        $user=User::create($data);

         return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
//        $user=User::findOrFail($id);
     return $this->showOne($user);
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
    public function update(Request $request, User $user)
    {
//        $user=User::findOrFail($id);

            $role=[
            'email'=>'email|unique:users,email,'.$user->id,
            'password'=>'min:8|confirmed',
            'admin'=>'in:'.User::ADMIN_USER.','.User::REGULAR_USER,

        ];

        $this->validate($request,$role);

        if($request->has('name'))
            $user->name=$request->name;


        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified=User::UNVERIFIED_USER;
            $user->verification_token=User::generateVerificationCode();
            $user->email=$request->email;
        }


        if($request->has('password'))
        {
            $user->password=bcrypt($request->password);

        }

        if($request->has('admin'))
        {
            if(!$user->isVerified()){


            return $this->errorResponse(' only Verified users can be modefied admin field',409);
            }

            else
                $user->admin=$request->admin;

        }

        if(!$user->isDirty())
        {
            return $this->errorResponse('you need to different values to updates ',422);
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
//        $user=User::findOrFail($id);
        $user->delete();

         return $this->showOne($user);

    }


    public  function  verify($token){
        $user=User::where('verification_token')->firstOrFail();

        $user->verified=User::VERIFIED_USER;
        $user->verification_token=null;

        $user->save();
        return $this->showOne($user);
    }


    public function resent(User $user){
        if($user->isVerified())
            return $this->errorResponse('the email is already verified ',409);

        Mail::to($user)->send(new UserCreated($user));


        return $this->showMessage('the verification email has been resented ');
    }
}
