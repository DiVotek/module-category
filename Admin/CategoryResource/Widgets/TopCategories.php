<?php

namespace Modules\Category\Admin\CategoryResource\Widgets;

use App\Services\TableSchema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Modules\Category\Models\Category;

class TopCategories extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Top categories');
    }

    public function table(Table $table): Table
    {
        $currentModel = Category::class;

        return $table
            ->searchable(false)
            ->query(function () use ($currentModel) {
                return $currentModel::query()->orderBy('views', 'desc')->take(5);
            })
            ->columns([
                TableSchema::getName(),
                TableSchema::getViews(),
            ])->actions([
                Action::make('View')
                    ->label(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(function ($record) {
                        $category = Category::find($record->parent_id);
                        if ($category) {
                            return '/' . $category->slug . '/' . $record->slug;
                        }

                        return '/' . $record->slug;
                    }),
            ])->paginated(false)->defaultPaginationPageOption(5);
    }
}
