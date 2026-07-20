<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(\App\Models\Brand::class, 'slug', ignoreRecord: true),
                FileUpload::make('logo')
                    ->disk('public')
                    ->label('Brand Logo')
                    ->directory('brands')
                    ->image()
                    ->maxSize(5120)
                    ->saveUploadedFileUsing(fn ($file) => \App\Services\ImageOptimizer::optimize($file, 'brands', 'public'))
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
