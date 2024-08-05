<?php

namespace Modules\Category\Admin\CategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Category\Admin\CategoryResource;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
