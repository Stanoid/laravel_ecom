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
        'email'=> "usee@gil.com",
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


test('Can place order', function () {
    $user = User::factory()->create([
        'role'=>'user'
    ]);

    class Item {
    public $id;
    public $qty;
    };

    $Item = new Item();
    $Item->id = 1;
    $Item->qty = 1;

    $items = array($Item);

    Storage::fake('public');

    $file = UploadedFile::fake()->image('payment.jpg');


    $response = $this->actingAs($user)->postJson('/api/order/place', [
        'img'=> $file,
        "city_id"=>1,
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




