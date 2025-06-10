<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function store(StoreProductRequest $request)
    {
        
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');

            $data = array_merge($request->all(), [
                'image_url' => Storage::disk('public')->url($imagePath)
            ]);
        }

        $product = Product::create($data);
        return response()->json(['message' => 'Producto creado', 'data' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product)
            return response()->json(['message' => 'Producto no encontrado'], 404);

        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product)
            return response()->json(['message' => 'Producto no encontrado'], 404);

        $product->update($request->all());
        return response()->json(['message' => 'Producto actualizado', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product)
            return response()->json(['message' => 'Producto no encontrado'], 404);

        $product->delete();
        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
