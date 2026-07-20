<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSeoResource\Pages;
use App\Models\PageSeo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;

class PageSeoResource extends Resource
{
    protected static ?string $model = PageSeo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'SEO Manager';

    protected static ?string $modelLabel = 'Page SEO';

    protected static ?string $pluralModelLabel = 'Page SEO';

    protected static ?string $recordTitleAttribute = 'page_name';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Identification')
                    ->description('Identify which page this SEO configuration applies to.')
                    ->schema([
                        TextInput::make('page_identifier')
                            ->label('Page Identifier')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. home, about, contact')
                            ->helperText('Unique key for this page. Use: home, about, contact, etc.')
                            ->maxLength(255),
                        TextInput::make('page_name')
                            ->label('Page Name (Admin Label)')
                            ->required()
                            ->placeholder('e.g. Home Page, About Us')
                            ->helperText('Friendly name shown in admin panel.')
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Disable to use default hardcoded SEO instead.'),
                    ])->columns(2),

                Section::make('Basic Meta Tags')
                    ->description('Title, description and keywords that appear in search engine results.')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->placeholder('Page Title - Brand Name')
                            ->helperText('Recommended: 50-60 characters. This appears as the clickable headline in Google results.')
                            ->maxLength(255)
                            ->suffixAction(
                                \Filament\Forms\Components\Actions\Action::make('countTitle')
                                    ->icon(Heroicon::OutlinedInformationCircle)
                                    ->tooltip('Ideal: 50-60 characters')
                            ),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->placeholder('A compelling description of this page...')
                            ->helperText('Recommended: 150-160 characters. This appears below the title in search results.')
                            ->rows(3)
                            ->maxLength(500),
                        Textarea::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->placeholder('keyword1, keyword2, keyword3')
                            ->helperText('Comma-separated keywords. Less important for modern SEO but still used by some engines.')
                            ->rows(2)
                            ->maxLength(500),
                    ]),

                Section::make('Open Graph / Social Media')
                    ->description('Controls how this page appears when shared on WhatsApp, Facebook, Twitter, etc.')
                    ->schema([
                        TextInput::make('og_title')
                            ->label('OG Title')
                            ->placeholder('Title for social media sharing')
                            ->helperText('If empty, Meta Title will be used.')
                            ->maxLength(255),
                        Textarea::make('og_description')
                            ->label('OG Description')
                            ->placeholder('Description for social media sharing')
                            ->helperText('If empty, Meta Description will be used.')
                            ->rows(3)
                            ->maxLength(500),
                        FileUpload::make('og_image')
                            ->label('OG Image')
                            ->disk('public')
                            ->directory('seo')
                            ->image()
                            ->maxSize(2048)
                            ->helperText('Recommended: 1200x630px. Shown when page is shared on social media.'),
                    ]),

                Section::make('Advanced Settings')
                    ->description('Robots directives, canonical URL, and structured data.')
                    ->collapsed()
                    ->schema([
                        Select::make('robots')
                            ->label('Robots Directive')
                            ->options([
                                'index, follow' => 'Index, Follow (Default — Allow everything)',
                                'noindex, follow' => 'No Index, Follow (Hide from search, follow links)',
                                'index, nofollow' => 'Index, No Follow (Show in search, don\'t follow links)',
                                'noindex, nofollow' => 'No Index, No Follow (Hide completely)',
                            ])
                            ->default('index, follow')
                            ->helperText('Controls how search engines index this page.'),
                        TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->placeholder('https://apnamobilehub.com/page')
                            ->helperText('Override the canonical URL. Leave empty to use the current page URL.')
                            ->url()
                            ->maxLength(500),
                        Textarea::make('schema_markup')
                            ->label('Custom JSON-LD Schema')
                            ->placeholder('{"@context":"https://schema.org",...}')
                            ->helperText('Paste custom JSON-LD structured data. Leave empty to use default schema.')
                            ->rows(8)
                            ->maxLength(10000),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_name')
                    ->label('Page')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('page_identifier')
                    ->label('Identifier')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('meta_title')
                    ->label('Meta Title')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('robots')
                    ->label('Robots')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'index, follow' => 'success',
                        'noindex, follow' => 'warning',
                        'index, nofollow' => 'warning',
                        'noindex, nofollow' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('page_name')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageSeos::route('/'),
            'create' => Pages\CreatePageSeo::route('/create'),
            'edit' => Pages\EditPageSeo::route('/{record}/edit'),
        ];
    }
}
