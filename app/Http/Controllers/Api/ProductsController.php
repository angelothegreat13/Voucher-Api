<?php

namespace App\Http\Controllers\Api;

use App\Product;

use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    
    public function index()
    {
        $products = auth()->user()->products;

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }
    
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer'
        ]);
            
        $product = new Product;
        $product->name = request()->name;
        $product->description = request()->description;
        $product->price = request()->price;
 
        if (auth()->user()->products()->save($product)) {
            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product could not be added'
        ]);
    }
   
    public function show($id)
    {
        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return static::messageProductNotFound($id);
        }
 
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function update($id)
    {
        $product = auth()->user()->products()->find($id);

        if (!$product) {
            return static::messageProductNotFound($id);
        }

        $validatedData = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer'
        ]);

        $updatedProduct = $product->update($validatedData);
        
        if ($updatedProduct) {
            return response()->json([
                'success' => true,
                'message' => "Product with id {$id} successfully updated"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product could not be updated'
        ]);
    }

    public function destroy($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return static::messageProductNotFound($id);
        }
        
        if ($product->delete()) {
            return response()->json([
                'success' => true,
                'message' => "Product with id {$id} successfully deleted"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product could not be deleted'
        ]);
    }

    protected static function messageProductNotFound($id)
    {
        return response()->json([
            'success' => false,
            'message' => "Product with id {$id} not found"
        ]);
    }

}
