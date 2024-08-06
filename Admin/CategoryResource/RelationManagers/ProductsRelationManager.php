<?php

namespace Modules\Category\Admin\CategoryResource\RelationManagers;

use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Product\Admin\ProductResource;

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
                TextInput::make('sorting')
                    ->numeric()
                    ->default(0)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->url(fn ($record): string => ProductResource::getUrl('edit', [
                        'record' => $record->id
                    ])),
                TableSchema::getStatus()
                    ->label(__('Status'))
                    ->updateStateUsing(function ($record, $state) {
                        $category_id = $record->pivot_category_id;
                        $product_id = $record->pivot_product_id;
                        $status = $state;
                        DB::table('category_products')->where('category_id', $category_id)->where('product_id', $product_id)->update(['status' => $status]);
                    }),
                TableSchema::getSku(),
                TableSchema::getPrice()
            ])
            ->filters([
                //
            ])
            ->reorderable('category_products.sorting')
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('name'))
                    ->recordSelectSearchColumns(['name'])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make('edit')
                    ->label(__('Edit'))
                    ->modalHeading(__('Edit Record'))
                    ->modalWidth('lg')
                    ->form([
                        Schema::getSku(),
                        Schema::getPrice()
                    ])
                    ->action(function ($record, $data) {
                        $record->update($data);
                    }),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
