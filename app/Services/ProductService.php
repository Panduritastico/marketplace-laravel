<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class ProductService
{

    public function index(array $filters = [])
    {
        $query = Product::query();

        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['order_by_stock'])) {
            $query->orderBy('stock', $filters['order_by_stock']);
        }

        return [
            'products' => $query->get()
        ];
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            throw new \Exception(
                'Product not found'
            );
        }

        return [
            'product' => $product
        ];
    }

    public function store(array $data)
    {
        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
        ]);

        Log::info('Product created successfully', [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock ?? 0
        ]);

        return [
            'message' => 'Product created successfully',
            'product' => $product
        ];
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);

        if (!$product) {
            throw new \Exception(
                'Product not found'
            );
        }

        $product->update([
            'name' => $data['name'] ?? $product->name,
            'price' => $data['price'] ?? $product->price,
            'stock' => $data['stock'] ?? $product->stock,
        ]);

        return [
            'message' => 'Product updated successfully',
            'product' => $product
        ];
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        Log::info('Product deleted successfully', [
            'product_id' => $product->id,
            'name' => $product->name,
        ]);

        return [
            'message' => 'Product deleted successfully',
            'name' => $product->name
        ];
    }

}
