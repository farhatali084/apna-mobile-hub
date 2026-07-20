<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\FilterGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FilterGroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'filterGroups';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('display_order')
                    ->numeric()
                    ->default(0),
                Repeater::make('values')
                    ->relationship('values')
                    ->schema([
                        TextInput::make('value')
                            ->required()
                            ->label('Tag Value'),
                        ColorPicker::make('color_hex')
                            ->label('Color Hex (Optional)'),
                    ])
                    ->collapsible()
                    ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug'),
                TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('values_count')
                    ->counts('values')
                    ->label('Values'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
            ]);
    }
}
