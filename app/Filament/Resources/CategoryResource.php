<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 5;


    public static function getNavigationGroup(): ?string
    {
        return __('Shop');
    }

    public static function getModelLabel(): string
    {
        return __('Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Categories');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Category registered';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Forms\Components\Group::make([
                    Forms\Components\Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, callable $set) => $operation === 'edit' ?: $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->required()
                                ->disabledOn('edit')
                                ->dehydrated(fn(string $operation) => $operation === 'create')
                                ->unique(ignoreRecord: true),
                            RichEditor::make('description')
                                ->columnSpanFull(),
                        ])->columns()
                ]),

                Forms\Components\Group::make(schema: [
                    Forms\Components\Section::make()
                        ->schema(components: [
                            Select::make('parent_id')->label('Parent')
                                ->relationship(name: 'parent', titleAttribute: 'name', modifyQueryUsing: fn($query) => $query->isParent()->where('is_visible', true), ignoreRecord: true),
                            Toggle::make('is_visible')
                                ->required()
                                ->default(true),
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parent.name')->label('Parent')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_visible')->boolean()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
//
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
