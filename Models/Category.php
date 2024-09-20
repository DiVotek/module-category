<?php

namespace Modules\Category\Models;

use App\Traits\HasBreadcrumbs;
use App\Traits\HasProducts;
use App\Traits\HasRoute;
use App\Traits\HasSlug;
use App\Traits\HasSorting;
use App\Traits\HasStatus;
use App\Traits\HasTags;
use App\Traits\HasTemplate;
use App\Traits\HasTimestamps;
use App\Traits\HasTranslate;
use App\Traits\HasViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Seo\Traits\HasSeo;

class Category extends Model
{
    use HasBreadcrumbs;
    use HasFactory;
    use HasProducts;
    use HasRoute;
    use HasSeo;
    use HasSlug;
    use HasSorting;
    use HasStatus;
    use HasTimestamps;
    use HasTranslate;
    use HasViews;
    use HasTemplate;
    use HasTags;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'sorting',
        'status',
        'parent_id',
        'views',
        'custom'
    ];

    protected $casts = ['custom' => 'array'];

    public static function getDb(): string
    {
        return 'categories';
    }

    public function route(): string
    {
        return tRoute('slug', ['slug' => $this->slug]);
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
}
