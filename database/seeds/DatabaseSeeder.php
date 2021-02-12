<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
       Product::truncate();
       Category::truncate();
       Transaction::truncate();

       DB::table('category_product')->truncate();

       User::flushEventListeners();
        Transaction::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();

       factory(User::class,200)->create();
        factory(Category::class,40)->create();
        factory(Product::class,1000)->create();
       factory(Product::class,1000)->create()->each(function($product) {
           $categories= Category::all()->random(mt_rand(1, 5))->pluck('id');
           $product->categories()->attach($categories);

       });

        factory(Transaction::class,1000)->create();


    }
}
