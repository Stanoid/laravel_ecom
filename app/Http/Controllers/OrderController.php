<?php

namespace App\Http\Controllers;
use Telegram\Bot\Keyboard\Keyboard;
use App\Telegram\Commands\StartCommand;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\city;
use App\Models\Payment;
use App\Models\Product;
use GuzzleHttp\Psr7\Request;
use NotificationChannels\Telegram\TelegramUpdates;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Actions;
//use App\TelegramC\Commands\StartCommand;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

use NotificationChannels\Telegram\TelegramMessage;
use Telegram\Bot\Laravel\Facades\Telegram;

use Telegram\Bot\Api;
use Illuminate\Notifications\Notification;

class OrderController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $orders = Order::where('user_id', Auth::user()->id)->with(['items', 'items.product', 'items.product.category', 'payment'])->orderBy('created_at', 'desc')->paginate(6);

        return response()->json([
            'data' => $orders,
            // 'token'=>$user->createToken('tkn')->plainTextToken
        ]);
    }


    public function adminOrders()
    {

        if (Auth::user()->role == 'admin') {
            $orders = Order::orderBy('created_at', 'desc')->with(['user', 'city', 'items', 'items.product', 'payment', 'items.product.category'])
                ->where('status', '!=', 'deleted')
                ->orWhere('status', '!=', 'archived')
                ->paginate(10);

            return response()->json([
                'data' => $orders,

            ], 200);
        } else {
            return response()->json([
                'message' => "unauthorised",

            ], 401);
        }

        //  $orders = Order::where('user_id', Auth::user()->id)->with(['user','items','items.product','items.product.category'])->orderBy('created_at','desc')->paginate(6);

        // return response()->json([
        //     'data'=>$orders,
        //    // 'token'=>$user->createToken('tkn')->plainTextToken
        //     ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function archivedOrders()
    {


        $orders = Order::with(['items', 'items.product', 'items.product.category', 'payment'])
        ->where('status','=', 'archived')
        ->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'data' => $orders,
        ], 200);
    }


    public function deletedOrders()
    {


        $orders = Order::with(['items', 'items.product', 'items.product.category', 'payment'])
        ->where('status','=', 'deleted')
        ->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'data' => $orders,
        ], 200);
    }



    public function revenue()
    {
        //total revenue
        //revenue per city
        //revnue per category
        //top selling products
        //avrage order value total revenue over number of orders

        $total_revenue = Order::where('status', 'paid')->sum('total_price');
        $order_number = Order::where('status', 'paid')->count();

        return response()->json([
            'revenue' => $total_revenue,
            'orders_number' => $order_number

        ], 200);
    }

    public function topSelling()
    {

        $orders = Order::where('status', '=', 'paid')
            ->orWhere('status', '=', 'delivered')
            ->orWhere('status', '=', 'archived')

            ->select('id', "total_price")->with('items.product')->get();

        $categoryTotals = [];
        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $categoryId = $item['product']['id'];

                $price = $item['price'] * $item['qty'];

                $categoryTotals[$categoryId] = isset($categoryTotals[$categoryId]) ? $categoryTotals[$categoryId] : 0;

                $categoryTotals[$categoryId] += $price;
            }
        }

        $new = [];
        foreach ($categoryTotals as $key => $value) {
            $category = Product::find($key)->name;
            array_push($new, ['id' => $key, 'name' => $category, 'revenue' => $value]);
        }


        return response()->json([
            'data' => $new,
        ], 200);
    }


    public function revenuePerCategory()
    {

        $orders = Order::where('status', '=', 'paid')
            ->orWhere('status', '=', 'delivered')
            ->orWhere('status', '=', 'archived')
            ->select('id', "total_price")->with('items.product')->get();

        $categoryTotals = [];
        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $categoryId = $item['product']['category_id'];
                $price = $order['total_price'];

                $categoryTotals[$categoryId] = isset($categoryTotals[$categoryId]) ? $categoryTotals[$categoryId] : 0;

                $categoryTotals[$categoryId] += $price;
            }
        }

        $new = [];
        foreach ($categoryTotals as $key => $value) {
            $category = Category::find($key)->name;
            array_push($new, ['id' => $key, 'name' => $category, 'revenue' => $value]);
        }


        return response()->json([
            'data' => $new,
        ], 200);
    }



    public function revenuePerCity()
    {

        $orders = Order::where('status', '=', 'paid')
            ->orWhere('status', '=', 'delivered')
            ->orWhere('status', '=', 'archived')
            ->select('id', "city_id", "total_price")->get();

        $categoryTotals = [];
        foreach ($orders as $order) {

            $categoryId = $order['city_id'];
            $price = $order['total_price'];

            $categoryTotals[$categoryId] = isset($categoryTotals[$categoryId]) ? $categoryTotals[$categoryId] : 0;

            $categoryTotals[$categoryId] += $price;
        }

        $new = [];
        foreach ($categoryTotals as $key => $value) {
            $category = city::find($key)->name;
            array_push($new, ['id' => $key, 'name' => $category, 'revenue' => $value]);
        }


        return response()->json([
            'data' => $new,
        ], 200);
    }



    public function totalRevenuPeriod($start, $end)
    {

        // return $start.$end;
        //total revenue for a period

        $orders = Order::where('status', '=', 'paid')
            ->orWhere('status', '=', 'delivered')
            ->orWhere('status', '=', 'archived')

            ->whereBetween(
                'created_at',
                [$start, $end]
            )->get();

        $total_revenue =  $orders->sum('total_price');
        $count = $orders->count();
        return response()->json([
            'revenue' => $total_revenue,
            'orders_number' => $count,
            'start' => $start,
            'end' => $end

        ], 200);
    }


    public function ordersPerUser($id)
    {
        //dd($id);
        $orders = Order::where('user_id', $id)->with(['user', 'city', 'items', 'items.product', 'payment', 'items.product.category'])
            ->orderBy('created_at', 'desc')->paginate(6);
        //dd($orders);
        return response()->json([
            'data' => $orders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {

        //dd($request);


        $orderItems = [];
        $ordernum = uniqid();
        $user_id = Auth::user()->id;
        $total_price = 0;



        $patho = Storage::disk('public')->put('imgs', $request->file('img'));

        $payment =   Payment::create([
            'fullname' => $request->fullName,
            'phone' => $request->paymentphone,
            'img' => $patho,
        ]);


        $order =  Auth::user()->orders()->create(
            [
                'order_number' => $ordernum,
                'address' => $request->address,
                'phone' => $request->phone,
                'city_id' => $request->city_id,
                'payment_id' => $payment->id,
            ]
        );


        foreach ($request->cart as $cartItem) {
            $obj = (object) $cartItem;
            $id = $obj->id;
            $product = Product::find($id);



            if ($product->stock <= 0) {
                return response()->json([
                    'message' => $product->name . "is out of stock",
                    "error_code" => "out_of_stock"
                ], 404);
            } else {



                $price = $product->price;
                $qty = $obj->qty;
                $total_price += $price * $qty;
                $orderitem = $order->items()->create(
                    [
                        'product_id' => $id,
                        'qty' => $qty,
                        'price' => $price,
                    ]
                );

                //  array_push($orderItems, $orderitem);


            };
        }



        $delivery_price = city::find($request->city_id)->price;
        $total_price += $delivery_price;

        $Ord = Order::find($order->id);

        $Ord->update(['total_price' => $total_price, 'status' => 'initiated']);


        return response()->json([
            'data' => $Ord,

        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)

    {

        $order = Order::with(['user', 'items', 'items.product'])->findOrFail($id);
        if ($order->user->id == Auth::user()->id) {
            return response()->json([
                'data' =>  $order,

            ], 200);
        } else {
            return response()->json([
                'data' =>  "unauthorised",
                "error_code" => "out_of_stock"
            ], 401);
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }


    public function paid($id)
    {

        try {
            $order = Order::with('items', 'items.product')->findOrFail($id);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'error' =>  $e->getMessage(),
            ], 404);
        }

        $order->update(['status' => "paid"]);


        foreach ($order->items as $item) {

            try {
                $product = Product::findOrFail($item->product_id);
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' =>  $e->getMessage(),
                ], 404);
            }

            $product->update(['stock' => $product->stock - $item->qty]);
        }


        return response()->json([
            'message' =>  "order paid",
            'data' =>  $order,

        ], 200);
    }


    public function delivered($id)
    {

        try {
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'error' =>  $e->getMessage(),
            ], 404);
        }

        $order->update(['status' => "delivered"]);


        return response()->json([
            'message' =>  "order delivered",
            'data' =>  $order,

        ], 200);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'error' =>  $e->getMessage(),
            ], 404);
        }

        if ($order->status == "initiated") {
            $order->update(['status' => "deleted"]);
            return response()->json([
                'message' =>  "order deleted",
                'data' =>  $order,

            ], 200);
        } else {
            return response()->json([
                'message' =>  "Cant delete completed orders, use archive instead",
                'data' =>  $order,

            ], 403);

        }


    }

    public function archive($id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'error' =>  $e->getMessage(),
            ], 404);
        }

        if ($order->status == "initiated") {

            return response()->json([
                'message' =>  "Cant archive incompleted orders, use delete instead",
                'data' =>  $order,

            ], 403);


        } else {
            //$order->update(['status' => "archived"]);
            $order->update(['status' => "archived"]);
            return response()->json([
                'message' =>  "order archived",
                'data' =>  $order,

            ], 200);


        }


    }


public function telegram($mes)
{




    // $response = Telegram::bot('mybot')->getMe();

    // return $response;




// Response is an array of updates.

//$telegram = new Api('8190318819:AAHakYcO1xkRvofKbxSQxvtGWp4DD-NsuXU');



// $updates = TelegramUpdates::create()
//     // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
//      ->latest()

//     // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).


//     // (Optional). Add more params to the request.
//     ->options([
//         'timeout' => 0,
//     ])
//     ->get();


    // Chat ID
    // $chatId = $updates['result'][0]['message']['chat']['id'];




//$file = InputFile::create('https://res.cloudinary.com/strapimedia/image/upload/c_limit,w_640/f_auto/q_auto/v1/minimoon/nxwuws5t37hcdiwvb835?_a=BAVCyODW0', 'jjj.jpg');
 $order = Product::findOrFail($mes);


//   $message= Telegram::sendMessage([
//     'chat_id' => '259457916',
//     'text' => json_decode($order->img)[0],
// ]);


$file = InputFile::create(json_decode($order->img)[0], $order->name.'jpg');

$message = Telegram::sendPhoto([
    'chat_id' => '259457916',
	'photo' => $file ,
	'caption' => "Product name:  ".(string)$order->name."\n". "price: " .$order->price." SDG \nStock: ".$order->stock
]);




// $response = Telegram::sendPhoto([
// 	'chat_id' => '259457916',
// 	'photo' => $file ,
// 	'caption' => 'Some caption'
// ]);

// Telegram::sendChatAction([
// 	'chat_id' => '259457916',
// 	'action' => Actions::FIND_LOCATION
// ]);



// $response =Telegram::getUserProfilePhotos([ 'user_id' => '259457916']);
// $response = Telegram::sendInvoice([
//     'chat_id' => '259457916',
//     'title' => 'My Awesome Product',
//     'description' => 'A short description of the product',
//     'payload' => 'my_awesome_product', // Unique payload for this invoice
//     'provider_token' => env('YOUR_PAYMENT_PROVIDER_TOKEN'),
//     'payment_provider_token' => ,
//     'currency' => 'USD', // Currency code
//     'prices' => [
//         ['label' => 'Product Price', 'amount' => 1000], // 1000 cents = 10 USD
//     ],
//     'start_parameter' => 'my-awesome-product',
//     'photo_url' => 'https://example.com/product_image.jpg', // Optional: URL of product photo
//     'photo_size' => 512, // Optional: Photo size in bytes
//     'photo_width' => 512, // Optional: Photo width
//     'photo_height' => 512, // Optional: Photo height

// ]);

// $response = Telegram::getUpdates();



$reply_markup = Keyboard::make()
		->setResizeKeyboard(true)
		->setOneTimeKeyboard(true)
		->row([
			Keyboard::button('/start'),
			Keyboard::button('2'),
			Keyboard::button('3'),
		]);
//

//$reply_markup = Keyboard::forceReply(['selective' => false]);


// $response = Telegram::sendVenue([
//     'chat_id' => '259457916',
//     'latitude' => 37.7749,
//     'title' => 'San Francisco',
//     'address' => 'San Francisco',
//     'longitude' => -122.4194,
// ]);

// $response = Telegram::sendPoll([
// 'question' => 'to be or not to be',
// 'options'=>[
//     'text'=>'sss'
// ]
// ]);

//🏀”, “⚽”, “🎳”, or “🎰”. Dice can have values 1-6 for “🎲”, “🎯” and “🎳”, values 1-5 for “🏀” and “⚽”, and values 1-64 for “🎰”. Defaults to “🎲”

// $response = Telegram::sendDice([
//     'chat_id' => '259457916',
//     'emoji' => '⚽',
// ]);

// $response = Telegram::getFile([
//     'chat_id' => '259457916',
//      'file_id'=> $file,
// ]);

    return response()->json([
         $message,
    ],200);

}



public function webh($wh) {

  //  $telegram = new Api('8190318819:AAHakYcO1xkRvofKbxSQxvtGWp4DD-NsuXU');

    $response = Telegram::setWebhook(['url' => 'https://darkcyan-cobra-904565.hostingersite.com/api/8190318819:AAHakYcO1xkRvofKbxSQxvtGWp4DD-NsuXU/webhook']);

return response()->json([
    $response,
    ],200);

}

public function incomming()
    {



      //  $telegram = new Api('8190318819:AAHakYcO1xkRvofKbxSQxvtGWp4DD-NsuXU');
        $updates = Telegram::getWebhookUpdate();
$updates->getMessage();
if(is_numeric($updates->getMessage()->getText())){


 $order = Product::findOrFail((int)$updates->getMessage()->getText());

$img = json_decode($order->img);

//  $message= Telegram::sendMessage([
//     // 'chat_id' => '259457916',
//       'chat_id' => $updates->getChat()->getId(),
//  //'text' => "Product name:  ".(string)$order->name."\n". "price: " .$order->price." SDG \nStock: ".$order->stock ,
//     'text'=> $img
// ]);

// $file = InputFile::create(json_decode($order->img)[0], 'jjj.jpg');

// $response = Telegram::sendPhoto([
// 	'chat_id' => $updates->getChat()->getId(),
// 	'photo' => $file ,
// 	'caption' => "Product name:  ".(string)$order->name."\n". "price: " .$order->price." SDG \nStock: ".$order->stock
// ]);

Telegram::sendChatAction([
	'chat_id' => $updates->getChat()->getId(),
	'action' => Actions::UPLOAD_PHOTO
]);

$file = InputFile::create(json_decode($order->img)[0], $order->name.'jpg');
//$file = InputFile::create('https://res.cloudinary.com/strapimedia/image/upload/c_limit,w_640/f_auto/q_auto/v1/minimoon/nxwuws5t37hcdiwvb835?_a=BAVCyODW0', 'jjj.jpg');



$message = Telegram::sendPhoto([
    'chat_id' => $updates->getChat()->getId(),
	'photo' => $file ,
	'caption' => "Product name:  ".(string)$order->name."\n". "price: " .$order->price." SDG \nStock: ".$order->stock
]);



}else{


 $message= Telegram::sendMessage([
   // 'chat_id' => '259457916',
      'chat_id' => $updates->getChat()->getId(),
 // 'text' => "Product name:  ".(string)$order->name."\n". "price: " .$order->price." SDG \nStock: ".$order->stock ,
  'text'=> 'Try again with an id (int)'
]);

}






    }


    public function commands() {


$res =Telegram::addCommand(StartCommand::class);
return $res;


    }


}


