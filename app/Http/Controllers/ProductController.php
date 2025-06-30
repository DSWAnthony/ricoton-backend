<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Utils\DeleteImage;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use DeleteImage;

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

    public function update(UpdateProductRequest $request, $id)
    {
        $request->validated();
        $product = Product::find($id);

        if ($request->hasFile('image')) {

            if ($product && isset($product->image_url)) {
                $this->deleteImageForUrl($product->image_url);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            
            $data = array_merge($request->all(), [
                'image_url' => Storage::disk('public')->url($imagePath)
            ]);

            $product->update($data);

        } else {
            $product->update($request->all());
        }
        
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
