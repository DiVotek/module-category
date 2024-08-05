<?php

namespace Modules\Category\Controllers;

use Illuminate\Support\Facades\Blade;
use Modules\Blog\Components\BlogArticlePage;
use Modules\Blog\Components\BlogCategoryPage;
use Modules\Category\Models\Category;

class CategoryController
{
   public function category(string $slug)
   {
      $category = Category::query()->where('slug', $slug)->first();
      if ($category) {
         return Blade::renderComponent(new BlogCategoryPage($category));
      }
      abort(404);
   }

   public function product(string $categorySlug, string $productSlug)
   {
       $category = Category::query()->where('slug', $categorySlug)->first();
      if ($category) {
         $product = $category->products()->where('slug', $productSlug)->first();
         if ($product) {
            return Blade::renderComponent(new BlogArticlePage($product));
         }
      }
      abort(404);
   }
}
