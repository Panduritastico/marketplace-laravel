<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Services\OrderService;


class OrderController extends Controller
{

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        try {
            
            $orders = $this->orderService->index();

            return response()->json(
                $orders,
                200
            );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        try {

            $result = $this->orderService->store($request->validated());

            return response()->json(
                $result,
                201
            );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        try {
            
            $result = $this->orderService->show($id);
            
            return response()->json(
                $result,
                200
            );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try
        {
            $result = $this->orderService->destroy($id);

            return response()->json(
                $result,
                200
            );
        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    public function topProducts()
    {
        try {
            $result = $this->orderService->topProducts();

            return response()->json(
                $result,
                200
            );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    public function userOrders($id)
    {
        try {

            $result = $this->orderService->userOrders($id);

            return response()->json(
                $result,
                200
            );

        } catch (\Exception $e) {

            return response()->json([
            'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}
