<?php

namespace Modules\Category\Controllers;

use Illuminate\Support\Facades\Blade;
use Modules\Category\Components\CategoryPage;
use Modules\Category\Models\Category;
use Modules\Product\Components\ProductPage;

class CategoryController
{
    public function category(string $slug)
    {
        $category = Category::query()->where('slug', $slug)->first();
        if ($category) {
            return Blade::renderComponent(new CategoryPage($category));
        }
        abort(404);
    }

    public function product(string $categorySlug, string $productSlug)
    {
        $category = Category::query()->where('slug', $categorySlug)->first();
        if ($category) {
            $product = $category->products()->where('slug', $productSlug)->first();
            if ($product) {
                return Blade::renderComponent(new ProductPage($product));
            }
        }
        abort(404);
    }
}
