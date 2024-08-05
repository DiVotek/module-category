<?php

namespace Modules\Category\Models;

use App\Traits\HasBreadcrumbs;
use App\Traits\HasRoute;
use App\Traits\HasSlug;
use App\Traits\HasSorting;
use App\Traits\HasStatus;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;
use App\Traits\HasViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Filter\Models\Attribute;
use Modules\Product\Models\Product;
use Modules\Seo\Traits\HasSeo;

class Category extends Model
{
    use HasFactory;
    use HasSorting;
    use HasStatus;
    use HasTimestamps;
    use HasSlug;
    use HasSeo;
    use HasTranslate;
    use HasRoute;
    use HasBreadcrumbs;
    use HasViews;

    public const TABLE = 'categories';

    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'banner',
        'sorting',
        'status',
        'parent_id',
        'filters',
        'recommended',
        'options',
        'faq_id',
        'views',
        'template',
        'attributes',
        'dynamic',
        'parameters'
    ];

    protected $casts = [
        'filters' => 'json',
        'recommended' => 'json',
        'options' => 'json',
        'image' => 'array',
        'template' => 'array',
        'attributes' => 'array',
        'parameters' => 'array'
    ];

    public static function getDb(): string
    {
        return 'categories';
    }

    public function route(): string
    {
        return tRoute('category', ['category' => $this->slug]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->name => $this->route(),
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_products', 'category_id', 'product_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_categories', 'attribute_id', 'category_id');
    }
}
