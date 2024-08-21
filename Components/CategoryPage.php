<?php

namespace Modules\Category\Components;

use App\View\Components\PageComponent;
use Modules\Category\Models\Category;

class CategoryPage extends PageComponent
{
    public function __construct(Category $entity)
    {
        $defaultTemplate = setting(config('settings.category.template'), []);
        parent::__construct($entity, 'category::category-component', defaultTemplate: $defaultTemplate);
    }
}
