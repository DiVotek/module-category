<?php

namespace Modules\Category\Admin\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Category\Admin\CategoryResource;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ViewAction::make()->url(fn($record) => $record->route())->openUrlInNewTab(true),
        ];
    }
}
