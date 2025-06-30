<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;


class CategoryService
{
    public function getActiveCategories(): Collection
    {
        return Category::where('is_active', true)->with('products')->get();
    }

    public function findCategoryById($id): ?Category
    {
        return Category::find($id)->first();
    }

    // Otros métodos según sea necesario
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }
    public function updateCategory($id, array $data): Category|null
    {
        $category = $this->findCategoryById($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }
    public function deleteCategory($id): bool
    {
        $category = $this->findCategoryById($id);
        if ($category) {
            $category->delete();
            return true;
        }
        return false;
    }
}