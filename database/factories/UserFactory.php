<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10).'',
        'verified'=>$verified=$faker->randomElement([User::UNVERIFIED_USER,User::VERIFIED_USER]),
        'verification_token'=>$verified==User::VERIFIED_USER ? null: User::generateVerificationCode(),
        'admin'=>$verified=$faker->randomElement([User::ADMIN_USER,User::REGULAR_USER]),
    ];
});



$factory->define(Category::class, function (Faker $faker) {
    return [
        'category_name' => $faker->word,
        'category_description' => $faker->paragraph(1),

    ];
});



$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_name' => $faker->word,
        'product_description' => $faker->paragraph(1),
        'product_quantity'=>$faker->numberBetween(10,20),
        'product_status'=>$faker->randomElement([Product::AVAILABLE,Product::UNAVAILABLE]),
        'product_image'=>$faker->randomElement(['p1.jpg','p2.jpg','p3.jpg','p4.jpg','p5.jpg']),
        'seller_id'=>User::all()->random()->id,

    ];
});

    

$factory->define(Transaction::class, function (Faker $faker) {
    $seller=Seller::has('products')->get()->random();
    $buyer=User::all()->except($seller->id)->random();
    return [
        'quantity' => $faker->numberBetween(5,10),
        'buyer_id' => $buyer,
        'product_id'=>$seller->products->random()->id,


    ];
});
