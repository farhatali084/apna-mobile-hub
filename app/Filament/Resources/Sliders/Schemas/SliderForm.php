<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                TextInput::make('subtitle'),
                TextInput::make('button_text'),
                TextInput::make('button_link'),
                FileUpload::make('image_path')
                    ->disk('public')
                    ->label('Hero Image (Transparent PNG)')
                    ->directory('sliders')
                    ->image()
                    ->maxSize(5120)
                    ->saveUploadedFileUsing(fn ($file) => \App\Services\ImageOptimizer::optimize($file, 'sliders', 'public'))
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
