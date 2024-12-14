<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItems;
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

         Category::factory(10)->create([]);

    Product::factory(100)->create();



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
