<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';


    public static function getNavigationGroup(): ?string
    {
        return __('Shop');
    }

    public static function getModelLabel(): string
    {
        return __('Brand');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Brands');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->unique(ignoreRecord: true)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, callable $set) => $operation === 'edit' ?: $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->required()
                                ->disabledOn('edit')
                                ->dehydrated(fn(string $operation) => $operation === 'create')
                                ->unique(),
                            TextInput::make('url')
                                ->required()
                                ->columnSpanFull(),
                            RichEditor::make('description')
                                ->columnSpanFull(),
                        ])->columns()
                ]),
                Group::make([
                    Section::make(__('Status'))
                        ->schema([
                            Toggle::make('is_visible')
                                ->helperText(__('Enable or disable brand visibility'))
                                ->default(true),
                        ]),
                    Section::make(__('Color'))
                        ->schema([
                            ColorPicker::make('primary_hex')->prefixIcon('heroicon-o-paint-brush')
                        ])

                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('url')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                ColorColumn::make('primary_hex')->searchable(),
                IconColumn::make('is_visible')->boolean()->sortable(),
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
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
