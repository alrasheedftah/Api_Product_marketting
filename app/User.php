<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens,Notifiable,SoftDeletes;
    const  VERIFIED_USER='1';
    const UNVERIFIED_USER='0';
    const ADMIN_USER='true';
    const REGULAR_USER='false';
    protected $table='users';

    public $transformer=UserTransformer::class;


    /**
     * The attributes that are mass asisgnable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verified','verification_token','admin',
    ];

    protected  $dates=['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',//'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isAdmin(){
        return $this->admin==User::ADMIN_USER;
    }

    public function isVerified(){
        return $this->verified==User::VERIFIED_USER;
    }

    public static function generateVerificationCode(){
        return str_random(40);
    }





}
