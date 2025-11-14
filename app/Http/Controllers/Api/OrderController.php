<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Helper\ResponseBuilder;
use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Validator;
use Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->pagination ?? 10;
        $data = Order::where('user_id', Auth::user()->id)->with(['items', 'store'])->paginate($paginate);
        $this->response = new OrderCollection($data);
        return ResponseBuilder::successWithPagination($data, $this->response, trans('global.all_orders'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validSet =[
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $validSet );

        if($validator->fails()) {
            return ResponseBuilder::error($validator->errors()->first(), 422);
        }

        $user = auth()->user();
        $total = 0;

        DB::transaction(function () use ($request, $user, &$total) {
            $order = Order::create([
                'store_details_id' => $user->store->id,
                'user_id' => $user->id,
                'total_price' => 0,
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $total += $product->price * $item['quantity'];
            }

            $order->update(['total_price' => $total]);
            OrderPlaced::dispatch($order);
        });

        return ResponseBuilder::successMessage(trans('global.order_placed'),201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // try {
        $order = Order::findOrFail($id);

        return ResponseBuilder::success(trans('global.order_detail'),200,new OrderResource($order));
    }
}
