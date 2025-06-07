<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:150',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $product = Product::create($validated);
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
