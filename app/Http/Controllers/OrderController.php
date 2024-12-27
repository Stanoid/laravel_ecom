<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\city;
use App\Models\Payment;
use App\Models\Product;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

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
            $orders = Order::orderBy('created_at', 'desc')->with(['user', 'city', 'items', 'items.product', 'payment', 'items.product.category'])->paginate(10);

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {



        $orderItems = [];
        $ordernum = uniqid();
        $user_id = Auth::user()->id;
        $total_price = 0;



       // $patho = Storage::disk('public')->put('imgs', $request->file('img'));

        $payment =   Payment::create([
            'fullname' => $request->fullName,
            'phone' => $request->paymentphone,
            'img' => "11111111111111",
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
                ], 200);
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
    public function destroy(Order $order)
    {
        //
    }
}
