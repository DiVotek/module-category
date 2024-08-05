<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Service\MultiLang;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sorting')->numeric()->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->leftJoin(Product::TRANSLATE_TABLE, Product::TRANSLATE_TABLE . '.product_id', Product::TABLE . '.id')
                    ->select(Product::TABLE . '.*', Product::TRANSLATE_TABLE . '.name', Product::CATEGORIES_RELATION_TABLE . '.sorting')
                    ->where('language_id', MultiLang::getCurrentLanguageId())->orderBy(Product::CATEGORIES_RELATION_TABLE . '.sorting');
            })
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->url(fn ($record): string => ProductResource::getUrl('edit', [
                    'record' => $record->id,
                ])),
                TextInputColumn::make('sorting')->type('number')->label(__('Sort'))->default(0)->updateStateUsing(function ($record, $state) {
                    $category_id = $record->pivot_category_id;
                    $product_id = $record->pivot_product_id;
                    $sorting = $state;
                    DB::table(Product::CATEGORIES_RELATION_TABLE)->where('category_id', $category_id)->where('product_id', $product_id)->update(['sorting' => $sorting]);
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->leftJoin(Product::TRANSLATE_TABLE, Product::TRANSLATE_TABLE . '.product_id', Product::TABLE . '.id')
                        ->select(Product::TABLE . '.*', Product::TRANSLATE_TABLE . '.name')
                        ->where('language_id', MultiLang::getCurrentLanguageId())->orderBy(Product::TRANSLATE_TABLE . '.name'))
                    ->recordSelectSearchColumns([Product::TRANSLATE_TABLE . '.name'])
                    ->multiple(),
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
