<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


            $orders = Order::where('user_id', Auth::user()->id)->with(['user','items','items.product','items.product.category'])->orderBy('created_at','desc')->paginate(6);

            return response()->json([
                'data'=>$orders,
               // 'token'=>$user->createToken('tkn')->plainTextToken
                ]);


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





        $order =  Auth::user()-> orders()->create(
            [
                'order_number' => $ordernum,
                'address'=> $request->address,
                'phone'=> $request->phone,
            ]
           );


        foreach ($request->cart as $cartItem) {
            $obj = (object) $cartItem;
            $id = $obj->id;
            $product = Product::find($id);



            if($product->stock<=0){
                return response()->json([
                    'message'=> $product->name . "is out of stock",
                    "error_code"=> "out_of_stock"
                    ],200);
            }else{



                $price= $product->price;
                $qty = $obj->qty;
                $total_price += $total_price + $price*$qty;




                $old_stock = $product->getOriginal('stock');
                $product->update(['stock' => $old_stock-$qty]);

               $orderitem = $order->items()->create(
                    [
                        'product_id' => $id,
                        'qty'=>$qty,
                        'price'=>$price,
                    ]
                   );

             //  array_push($orderItems, $orderitem);


            };
        }



        $Ord = Order::find($order->id);
        $Ord->update(['total_price' => $total_price,'status'=>'paid']);


        return response()->json([
            'data'=>$Ord,

            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)

    {

        $order = Order::with(['user','items','items.product'])->findOrFail($id);
        if($order->user->id ==Auth::user()->id){
            return response()->json([
                'data'=>  $order,

                ],200);
        }else{
            return response()->json([
                'data'=>  "unauthorised",
                "error_code"=> "out_of_stock"
                ],401);
        };


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
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
