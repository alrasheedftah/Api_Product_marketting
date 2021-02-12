<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Product::updated(function ($product){
            if($product->product_quantity==0 && $product->isisAvailable())
                $product->product_status=Product::UNAVAILABLE;

            $product->save();

        });

        User::created (function ($user){
           Mail::to($user)->send(new UserCreated($user));
        });

        User::updated(function ($user){
            if($user->isDirty('email'))
            Mail::to($user)->send(new UserMailChanged($user));
        });
    }
}
