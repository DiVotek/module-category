<?php

namespace Modules\Category\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class CategoryComponent extends Component
{
    use WithPagination;

    public $categories;
    public string $url;
    public $perPage = 12;
    public $loadMorePerClick = 12;
    public $sort = 'sorting';
    public $direction = 'asc';
    #[Url(keep:true,as:'categories')]
    public $activeCategories = [];
    #[Url(as:'min')]
    public $min_price;
    #[Url(as:'max')]
    public $max_price;

    public Category $page;

    public function mount(Category $entity)
    {
        $this->url = url()->current();
        $this->categories = Category::query()->get();
        $this->activeCategories = request()->query('activeCategories', [
            $entity->id
        ]);
        $this->min_price = request()->query('min_price',  Product::query()->min('price'));
        $this->max_price = request()->query('max_price', Product::query()->max('price'));
        $this->page = $entity;
    }

    public function loadMore()
    {
        $this->perPage += $this->loadMorePerClick;
    }

    public function addToCart($productId)
    {
        if (module_enabled('Order')) {
            $this->dispatch('addToCart', $productId);
        }
    }
    public function render()
    {
        return view('category::livewire.category-component', [
            'products' => Product::query()
                ->when($this->activeCategories, function ($query) {
                    $query->whereHas('categories', function ($query) {
                        $query->whereIn('category_id', $this->activeCategories);
                    });
                })
                ->when($this->min_price, function ($query) {
                    $query->where('price', '>=', $this->min_price);
                })
                ->when($this->max_price, function ($query) {
                    $query->where('price', '<=', $this->max_price);
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->perPage),
        ]);
    }
    public function filterCategory($category)
    {
        if (in_array($category, $this->activeCategories)) {
            $this->activeCategories = array_diff($this->activeCategories, [$category]);
        } else {
            $this->activeCategories[] = $category;
        }
    }
    public function filter(){
        $this->resetPage();
    }
}
