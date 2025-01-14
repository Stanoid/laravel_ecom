<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use App\Models\city;
use App\Models\Order;
use App\Models\Fact;
use App\Models\OrderItems;
use App\Models\Recipe;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     User::factory(1)->create([
        'name'=>'admin',
        'password'=>'admin',
        'role'=>'admin',
         'email'=>'admin@ecom.com'
     ]);


     User::factory(1)->create([
        'name'=>'user',
        'password'=>'user',
        'role'=>'user',
         'email'=>'user@ecom.com'
     ]);


         Category::factory(10)->create([

         ]);

         Category::factory(30)->create([]);
         Brand::factory(3)->create([]);
         Fact::factory(40)->create();

    Product::factory(100)->create();
    city::factory(20)->create();

    Recipe::factory(100)->create();



        // Order::factory()->create([
        //     'user_id' => 1,
        //     'order_number'=>"222345656"

        // ]);

        // OrderItems::factory()->create([
        //     'order_id' => 1,
        //     'product_id' => 1,

        // ]);





    }
}
