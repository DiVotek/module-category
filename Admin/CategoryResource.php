<?php

namespace Modules\Category\Admin;

use App\Filament\Resources\TranslateResource\RelationManagers\TranslatableRelationManager;
use App\Models\Setting;
use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Category\Admin\CategoryResource\Pages;
use Modules\Category\Admin\CategoryResource\RelationManagers\ProductsRelationManager;
use Modules\Category\Models\Category;
use Modules\Seo\Admin\SeoResource\Pages\SeoRelationManager;
use Nwidart\Modules\Facades\Module;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Catalog');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getModelLabel(): string
    {
        return __('Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Categories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Schema::getReactiveName(),
                        Schema::getSlug(),
                        Schema::getSorting(),
                        Schema::getStatus(),
                        Schema::getSelect('parent_id', Category::query()
                            ->where('id', '!=', $form->model->id ?? 0)
                            ->pluck('name', 'id')
                            ->toArray()
                        )->native(false)->searchable(),
                        Schema::getImage(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns1 = [
            TableSchema::getName(),
            TextColumn::make('parent_id')
                ->label(__('Parent'))
                ->formatStateUsing(function ($record) {
                    return $record->parent->name;
                }),
        ];
        $columns2 = [];
        if (file_exists(base_path('Modules/Product/Models/Product.php'))) {
            $columns2 = [
                TextColumn::make('products')
                    ->label(__('Products'))
                    ->badge()
                    ->toggleable()
                    ->numeric()
                    ->formatStateUsing(function ($record) {
                        return $record->products->count();
                    }),
            ];
        }
        $columns3 = [
            TableSchema::getViews(),
            TableSchema::getStatus(),
            TableSchema::getSorting(),
            TableSchema::getUpdatedAt(),
        ];
        $columns = array_merge($columns1, $columns2, $columns3);

        return $table
            ->columns($columns)
            ->headerActions([
                Action::make(__('Help'))
                    ->iconButton()
                    ->icon('heroicon-o-question-mark-circle')
                    ->modalDescription(__('Here you can manage blog categories. Blog categories are used to group blog articles. You can create, edit and delete blog categories as you want. Blog category will be displayed on the blog page or inside slider(modules section). If you want to disable it, you can do it by changing the status of the blog category.'))
                    ->modalFooterActions([]),

            ])
            ->reorderable('sorting')
            ->filters([
                TableSchema::getFilterParentId(),
                TableSchema::getFilterStatus(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('View')
                    ->label(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(function ($record) {
                        return $record->route();
                    })->openUrlInNewTab(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Template')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->fillForm(function (): array {
                        return [
                            'template' => setting(config('settings.blog.category.template'), []),
                            'design' => setting(config('settings.blog.category.design'), 'Zero'),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.blog.category.template') => $data['template'],
                            config('settings.blog.category.design') => $data['design'],
                        ]);
                        Setting::updatedSettings();
                    })
                    ->form(function ($form) {
                        return $form
                            ->schema([
                                Schema::getModuleTemplateSelect('Pages/Category'),
                                Section::make('')->schema([
                                    Schema::getTemplateBuilder()->label(__('Template')),
                                ]),
                            ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        $relations = [
            TranslatableRelationManager::class,
            SeoRelationManager::class,
        ];
        if (Module::find('Product') && Module::find('Product')->isEnabled()) {
            $relations[] = ProductsRelationManager::class;
        }

        return [
            RelationGroup::make('Seo and translates', $relations),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategory::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
