<?php

use App\Models\StaticPage;
use App\Models\SystemPage;
use Illuminate\Support\Facades\Route;
use Modules\Category\Controllers\CategoryController;

function category_slug()
{
    $categoryPage = StaticPage::query()->where('id', SystemPage::query()->where('name', 'Category')->first()->page_id ?? 0)->first();

    return $categoryPage && $categoryPage->slug ? $categoryPage->slug : 'category';
}
Route::get(category_slug() . '/{category}', [CategoryController::class, 'category'])->name('category');
