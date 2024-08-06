<?php

namespace Modules\Category\Controllers;

use Illuminate\Support\Facades\Blade;
use Modules\Category\Components\CategoryPage;
use Modules\Category\Models\Category;

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
}
