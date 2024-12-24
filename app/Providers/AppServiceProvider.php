<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureUsing();
    }

    public function configureUsing(): void
    {
        foreach ([Column::class, Field::class] as $component) {
            $component::configureUsing(static fn($com) => $com->translateLabel());
        }

        DatePicker::configureUsing(static fn($component) => $component->jalali()->prefixIcon('heroicon-o-calendar'));

        Table::configureUsing(static fn($component) => $component->striped());

        Column::configureUsing(static fn ($component) => $component->placeholder(__('No data')));

        ImageColumn::configureUsing(static fn ($component) => $component->circular()->defaultImageUrl(asset('dashboard/empty.png')));


        TextColumn::configureUsing(static function (TextColumn $component) {
            if (in_array($component->getName(), ['published_at', 'deleted_at', 'created_at', 'updated_at'])) {
                $component->jalaliDate();
            }
        });
    }


}
