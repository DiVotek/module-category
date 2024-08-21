<?php

namespace Modules\Category\Components\Microdata;

use App\View\Components\Microdata;
use Closure;
use Illuminate\Contracts\View\View;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class ProductCategory extends Microdata
{
    public function __construct(Category $entity)
    {
        $properties = $this->buildData($entity);
        parent::__construct('ItemList', $properties);
    }

    public function render(): View|Closure|string
    {
        return '<x-microdata :type="$type" :properties="$properties" />';
    }

    public function buildData(Category $entity): array
    {
        $currency = setting('currency')->code;
        $brand = setting('company_name');
        $stockStatus = 'http://schema.org/InStock';
        $data = [
            'name' => $entity->name,
            'description' => $entity->meta_description,
            'itemListOrder' => 'http://schema.org/ItemListOrderAscending',
            'numberOfItems' => $entity->products->count(),
        ];
        $values = [];
        $i = 1;

        foreach ($entity->products as $product) {
            $values[] = (object) [
                '@type' => 'ListItem',
                'position' => $i++,
                'url' => Product::route(),
                'item' => (object) [
                    '@type' => 'Product',
                    'name' => $product->name ?? '',
                    'image' => asset('/storage/' . $product->image[0] ?? ''),
                    'description' => $product->meta_description ?? '',
                    'sku' => $product->sku ?? '',
                    'brand' => (object) [
                        '@type' => 'Brand',
                        'name' => $brand,
                    ],
                    'offers' => (object) [
                        '@type' => 'Offer',
                        'url' => Product::route(),
                        'priceCurrency' => $currency,
                        'price' => $product->price,
                        'priceValidUntil' => $product->updated_at->format('Y-m-d'),
                        'itemCondition' => 'http://schema.org/NewCondition',
                        'availability' => $stockStatus,
                        'seller' => (object) [
                            '@type' => 'Organization',
                            'name' => setting('company_name'),
                        ],
                    ],
                ],
            ];
        }

        $data['itemListElement'] = $values;

        return $data;
    }
}
