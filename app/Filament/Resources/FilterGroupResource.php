<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FilterGroupResource\Pages\CreateFilterGroup;
use App\Filament\Resources\FilterGroupResource\Pages\EditFilterGroup;
use App\Filament\Resources\FilterGroupResource\Pages\ListFilterGroups;
use App\Models\FilterGroup;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class FilterGroupResource extends Resource
{
    protected static ?string $model = FilterGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFunnel;

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $shouldRegisterNavigation = false; // Registered manually in sidebar navigation

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Group Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(FilterGroup::class, 'slug', ignoreRecord: true),
                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Associated Categories')
                    ->description('Select which categories this filter group applies to.')
                    ->schema([
                        CheckboxList::make('categories')
                            ->relationship('categories', 'name')
                            ->columns(3)
                            ->required()
                    ]),

                Section::make('Filter Values (Tags)')
                    ->description('Define the options for this filter (e.g. Active black, Aurora purple, Blue, XL, XXL).')
                    ->schema([
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
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('display_order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),
                TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categories'),
                TextColumn::make('values_count')
                    ->counts('values')
                    ->label('Values'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relations are inline now
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFilterGroups::route('/'),
            'create' => CreateFilterGroup::route('/create'),
            'edit' => EditFilterGroup::route('/{record}/edit'),
        ];
    }
}
