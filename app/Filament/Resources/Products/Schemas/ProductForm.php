<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique(Product::class, 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
                FileUpload::make('image_path')
                    ->disk('public')
                    ->label('Product Image')
                    ->directory('products')
                    ->image()
                    ->maxSize(5120)
                    ->saveUploadedFileUsing(fn ($file) => \App\Services\ImageOptimizer::optimize($file, 'products', 'public'))
                    ->required(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $category = Category::find($state);
                            if ($category) {
                                foreach ($category->filterGroups as $group) {
                                    $set("filter_group_{$group->id}", []);
                                }
                            }
                        }
                    }),
                Group::make()
                    ->schema(function ($get, ?Model $record = null) {
                        $categoryId = $get('category_id');
                        if (!$categoryId) return [];
                        $category = Category::find($categoryId);
                        if (!$category) return [];

                        return $category->filterGroups->map(function ($group) use ($record, $category) {
                            $options = $category->filterValues()
                                ->where('filter_group_id', $group->id)
                                ->pluck('value', 'id');

                            return CheckboxList::make("filter_group_{$group->id}")
                                ->label($group->name)
                                ->options($options)
                                ->columns(2)
                                ->hidden(fn () => $options->isEmpty())
                                ->default(function () use ($record, $group) {
                                    if (!$record) return [];
                                    return $record->filterValues()
                                        ->where('filter_group_id', $group->id)
                                        ->pluck('filter_values.id')
                                        ->toArray();
                                });
                        })->toArray();
                    })
                    ->live(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
