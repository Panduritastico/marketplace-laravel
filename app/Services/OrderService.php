<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessOrderJob;

class OrderService
{
    public function index()
    {
        return Order::select([
            'id',
            'serie',
            'correlative',
            'status',
            'total',
            'user_id',
            'created_at'
        ])
        ->latest()
        ->get();
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'products'
        ])->find($id);

        if (!$order) {
            throw new \Exception(
                'Order not found'
            );
        }

        return $order;
    } 

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $total = 0;

            $pivotData = [];

            $serie = 1;

            $lastOrder = Order::latest()->first();

            $correlative = $lastOrder 
            ? $lastOrder->correlative + 1 
            : 1;

            foreach($data['products'] as $item)
            {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity'])
                {
                    throw new \Exception(
                        "The product '{$product->name}' does not have the required stock"
                    );
                }

                $subtotal = $product->price * $item['quantity'];

                $total += $subtotal;

                $product->stock -= $item['quantity'];
                
                //Guarda las modificaciones realizadas al producto, en este caso, retira stock y actualiza la información
                $product->save();

                $pivotData[$product->id] = [
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }

            //Creamos la orden en la bdd
            $order = Order::create([
                'user_id' => $data['user_id'],
                'serie' => 1,
                'correlative' => $correlative,
                'status' => 'pending',
                'total' => $total,
            ]);

            //La data de las columnas pivote de la tabla order_product se llenan de esta forma
            $order->products()->attach($pivotData);

            //Ejecutamos el JOB para crear un log de la orden
            ProcessOrderJob::dispatch($order);

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'total' => $order->total,
                'order' => $order
            ]);

            //load nos sirve para traer los datos de la tabla pivote, y asi brindar más información
            //products es la relación, los datos guardados dentro se mostrarán en el controller
            return [
                'message' => 'Order created successfully',
                'order' => $order->load('user:id,name,email', 'products:id,name,price,stock') 
            ];

        });
    }

    public function destroy(int $id) 
    {
        $order = Order::find($id);
        
        if (!$order) {
            throw new \Exception('Order not found');
        }

        $order->delete();

        Log::info('Order deleted successfully', [
            'order_id' => $order->id,
            'serie' => $order->serie,
            'correlative' => $order->correlative
        ]);
    }

    public function topProducts()
    {
        return OrderProduct::with('product:id,name,price')
        ->select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold')
        )
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();
    }

    public function userOrders(int $id)
    {
        $user = User::with([
            'orders'
        ])->find($id);

        if (!$user) {
            throw new \Exception(
                'User not found',
                404
            );
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'orders' => $user->orders,
                'accumulated_total' => $user->orders->sum('total')
            ],
    ];
    }
}