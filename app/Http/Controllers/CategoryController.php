<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category = Category::create($validated);
        return response()->json(['message' => 'Categoría creada', 'data' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        $category->update($request->all());
        return response()->json(['message' => 'Categoría actualizada', 'data' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada'], 200);
    }
}
