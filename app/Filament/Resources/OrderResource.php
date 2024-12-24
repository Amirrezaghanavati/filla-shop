<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Enums\ProductTypeEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';


    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return parent::getModel()::where('status', OrderStatusEnum::PROCESSING)->count();
    }


    public static function getNavigationGroup(): ?string
    {
        return __('Shop');
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make(__('Order details'))
                        ->schema([
                            Forms\Components\TextInput::make('number')
                                ->default('OR-' . random_int(1000000, 9999999))
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            Forms\Components\Select::make('customer_id')
                                ->relationship('customer', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('type')
                                ->options(OrderStatusEnum::class)
                                ->required()
                                ->columnSpanFull(),

                            Forms\Components\RichEditor::make('notes')
                                ->required()
                                ->columnSpanFull()

                        ])->columns(),
                    Forms\Components\Wizard\Step::make(__('Order items'))
                        ->schema([
                            Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label(__('Product'))
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->required(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1),

                                Forms\Components\TextInput::make('unit_price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->numeric(),
                            ])->columns(3)
                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')->numeric()->searchable()->sortable(),
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('total_price')->numeric()->searchable()->sortable()
                    ->summarize([
                        Sum::make()->money()
                    ]),
                TextColumn::make('shipping_price')->numeric()->sortable(),
                TextColumn::make('status')->searchable()->sortable(),
                TextColumn::make('created_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->color('primary')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
