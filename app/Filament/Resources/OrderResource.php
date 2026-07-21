<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages\EditOrder;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static bool $shouldRegisterNavigation = false; // Registered via custom navigation items

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Reference')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->disabled()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'delivered' => 'Delivered',
                                'canceled' => 'Canceled',
                            ])
                            ->required(),
                    ]),

                Section::make('Customer Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->disabled(),
                        TextInput::make('customer_phone')
                            ->disabled(),
                        Textarea::make('customer_address')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('Payment Summary')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled(),
                        TextInput::make('shipping_fee')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled(),
                        TextInput::make('total')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->label('Order #'),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer'),
                TextColumn::make('customer_phone')
                    ->searchable()
                    ->label('Phone'),
                TextColumn::make('total')
                    ->money('INR')
                    ->sortable()
                    ->label('Total'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'delivered' => 'success',
                        'canceled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Placed At'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('download_pdf')
                    ->label('PDF Invoice')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->color('success')
                    ->url(fn (Order $record) => route('order.pdf', $record->order_number))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                // Disable bulk delete for audit safety
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
            'index' => ListOrders::route('/'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
