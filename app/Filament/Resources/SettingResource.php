<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages\EditSetting;
use App\Filament\Resources\SettingResource\Pages\ListSettings;
use App\Models\Setting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'label';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->disabled()
                    ->required(),
                
                TextInput::make('value')
                    ->label('Value')
                    ->required()
                    ->numeric()
                    ->visible(fn ($record) => $record && $record->type === 'number')
                    ->helperText('Manage this numeric setting.'),
                    
                TextInput::make('value')
                    ->label('Value')
                    ->required()
                    ->visible(fn ($record) => $record && $record->type === 'text')
                    ->helperText('Manage this text setting.'),
                    
                Textarea::make('value')
                    ->label('Value')
                    ->required()
                    ->visible(fn ($record) => $record && $record->type === 'textarea')
                    ->rows(6)
                    ->helperText('Manage this detailed description setting.'),
                    
                FileUpload::make('value')
                    ->label('Value')
                    ->required()
                    ->visible(fn ($record) => $record && $record->type === 'image')
                    ->disk('public')
                    ->directory('about')
                    ->image()
                    ->maxSize(5120)
                    ->helperText('Upload a high quality image for the About Us store view.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // No bulk actions to prevent deleting settings
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSettings::route('/'),
            'edit' => EditSetting::route('/{record}/edit'),
        ];
    }
}
