<?php

namespace Modules\Category\Components;

use App\View\Components\PageComponent;
use Modules\Category\Models\Category;

class CategoryPage extends PageComponent
{
    public function __construct(Category $entity)
    {
        if (empty($entity->template)) {
            $entity->template = setting(config('settings.category.template'), []);
        }
        parent::__construct($entity, 'category::category-component');
    }
}
