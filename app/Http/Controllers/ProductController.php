<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;


class ProductController extends Controller
{

    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {

            $result = $this->productService->index($request->all());

            return response()->json(
            $result,
            200
        );

        } catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        
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
    public function store(StoreProductRequest $request)
    {
        try {

            $result = $this->productService->store($request->validated());

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
    public function show(string $id)
    {
        try {

            $result = $this->productService->show($id);

            return response()->json(
                $result,
                200
            );

        }catch (\Exception $e) {

            //Manejo de errores
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {

            $result = $this->productService->update($id, $request->validated());

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->productService->destroy($id);

        return response()->json(
            $result,
            200
        );
    }
}
