<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;
use Storage;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service
    ) {}
    public function index()
    {
        $categories = $this->service->getActiveCategories();

        return response()->json($categories, 200);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
             $data = array_merge($request->all(), [
                'image_url' => Storage::disk('public')->url($imagePath)
            ]);
        }

        $category = $this->service->createCategory($data);
        
        if (!$category)
            return response()->json(['message' => 'Error al crear la categoría'], 500);
        
        return response()->json(['message' => 'Categoría creada', 'data' => $category], 201);
    }

    public function show($id)
    {
        $category = $this->service->findCategoryById($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        return response()->json($category, 200);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->service->updateCategory($id, $request->validated());

        if (!$category)
            return response()->json(['message' => 'Error al actualizar la categoría'], 500);

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
