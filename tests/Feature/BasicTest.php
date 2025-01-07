<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;




it('Gets the products', function () {

    $response = $this->get('/api/products');

    $response->assertStatus(200);
});


test('Register a new account', function () {



    $response = $this->postJson('/api/user/register', [
        'name'=> "test user registeration",
        'phone'=>'1111111',
        'address'=>'aaaaaaaa',
        'email'=> uniqid()."@ecom.com",
        'password'=>"test123",
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'token'
             ]);

    $this->assertDatabaseHas('users', ['name' => 'test user registeration ']);
});

test('Login a user', function () {



    $response = $this->postJson('/api/user/login', [

        'email'=> "user@ecom.com",
        'password'=>"user",
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'token'
             ]);

    $this->assertDatabaseHas('users', ['name' => 'user']);
});




test('Only admin can add the product', function () {
    $user = User::factory()->create([
        'role'=>'user'
    ]);

    $response = $this->actingAs($user)->postJson('/api/products/add', [
        'name'=>"test",
        'price'=>1,
        'stock'=>1,
        'category'=> 1,
        'description'=>"test",
    ]);
    $response->assertStatus(403);



});



test(' admin Can add product', function () {
    $user = User::factory()->create([
        'role'=>'admin'
    ]);


    Storage::fake('public');

    $file = UploadedFile::fake()->image('payment.jpg');


    $response = $this->actingAs($user)->postJson('/api/products/add', [


        'name'=>"test",
        'price'=>1,
        'fact_id'=>1,
        'origin_country'=>'test',
        'discount'=>1,
        'size'=>'test',

        'expiration_date'=>'2021-12-12',
        'stock'=>100,
        'category'=>1,
        'brand_id'=>1,
        'description'=>"test",
        'image'=> $file,





    ]);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'data' => ['id', 'name', 'stock'],
             ]);

            // Storage::disk('public')->assertExists($user->avatar);

    $this->assertDatabaseHas('products', ['name' => 'test']);
});



test(' admin Can add category', function () {
    $user = User::factory()->create([
        'role'=>'admin'
    ]);


    Storage::fake('public');

    $file = UploadedFile::fake()->image('payment.jpg');

    $response = $this->actingAs($user)->postJson('/api/category/add', [


        'name'=>"test",
        'img'=> $file,
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'data' => ['id', 'name','img'],
             ]);

            // Storage::disk('public')->assertExists($user->avatar);

    $this->assertDatabaseHas('products', ['name' => 'test']);
});





test('Can place order', function () {
    $user = User::factory()->create([
        'role'=>'user'
    ]);

    class Item {
    public $id;
    public $qty;
    };

    $Item = new Item();
    $Item->id = 5;
    $Item->qty = 1;

    $items = array($Item);

    Storage::fake('public');

    $file = UploadedFile::fake()->image('payment.jpg');


    $response = $this->actingAs($user)->postJson('/api/order/place', [
        'img'=> $file,
        "city_id"=>3,
        "cart"=> $items,
        "phone"=>"090909",
        "paymentphone"=> "090909090",
        'fullName'=>"test full name",
        "address"=>"test adress",

    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => ['id', 'order_number', 'phone'],
             ]);

            // Storage::disk('public')->assertExists($user->avatar);

    $this->assertDatabaseHas('orders', ['address' => 'test adress ']);
});




test('admin can list orders', function () {
    $user = User::factory()->create([
        'role'=>'admin'
    ]);

    $response = $this->actingAs($user)->get('/api/orders/list');
    $response->assertStatus(200);



});





test('gets categories', function () {
    $response = $this->get('/api/categories');
    $response->assertStatus(200)
             ->assertJsonStructure([
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'created_at',
                        'updated_at',
                        'name',
                    ],
                ],
            ],
        ]);

    $this->assertDatabaseHas('users', ['name' => 'user']);
});

test('gets brands', function () {
    $response = $this->get('/api/brand/list');
    $response->assertStatus(200)
             ->assertJsonStructure([
            'data' => [


                    '*' => [
                        'id',
                        'created_at',
                        'updated_at',
                        'name',
                    ],

            ],
        ]);

    $this->assertDatabaseHas('users', ['name' => 'user']);
});


test('gets product details', function () {
    $response = $this->get('/api/product/1');
    $response->assertStatus(200)
             ->assertJsonStructure([
            'data' => [



                        'id',
                        'created_at',
                        'updated_at',
                        'name',


            ],
        ]);

    $this->assertDatabaseHas('users', ['name' => 'user']);
});



test('gets recipes', function () {
    $response = $this->get('/api/recipes');
    $response->assertStatus(200)
             ->assertJsonStructure([
                'recipes' => [

                    'data' => [
                        '*' => [
                            'id',
                            'img',
                            'serving',
                            'timeInMinutes',
                            'name',
                        ],
                    ],
                ],
        ]);





    $this->assertDatabaseHas('users', ['name' => 'user']);
});


test('gets a recipe', function () {
    $response = $this->get('/api/recipe/1');
    $response->assertStatus(200)
             ->assertJsonStructure([

                    'data' => [

                            'id',
                            'img',
                            'serving',
                            'timeInMinutes',
                            'name',

                    ],

        ]);





    $this->assertDatabaseHas('users', ['name' => 'user']);
});


test(' admin Can add recipe', function () {
    $user = User::factory()->create([
        'role'=>'admin'
    ]);


    Storage::fake('public');

    $file = UploadedFile::fake()->image('payment.jpg');


    $response = $this->actingAs($user)->postJson('/api/recipe/create', [


        'name'=>"test",
        'description'=>"test",
        'serving'=>23,
        'product_id'=>1,
        'timeInMinutes'=>122,
        'instructions'=>'aas ddd ff ggg hhh ',
        'img'=> $file,





    ]);

    $response->assertStatus(201);


            // Storage::disk('public')->assertExists($user->avatar);

    $this->assertDatabaseHas('recipes', ['name' => 'test']);
});

