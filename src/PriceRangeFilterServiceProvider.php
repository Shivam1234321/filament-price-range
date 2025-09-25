<?php

namespace PriceRange\PriceRangeFilter;

use PriceRange\PriceRangeFilter\Forms\Components\PriceRangeFilter as PriceRangeFilterComponent;
use PriceRange\PriceRangeFilter\Tables\Filters\PriceRangeFilter as PriceRangeFilterTable;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Livewire\Livewire;

class PriceRangeFilterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/price-range-filter.php',
            'price-range-filter'
        );
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'price-range-filter');
        
        // Auto-register assets with Filament so the slider styles & script load in the panel
        FilamentAsset::register([
            Css::make('price-range-filter-styles', dirname(__DIR__) . '/resources/css/price-range-filter.css'),
            Js::make('price-range-filter-script', dirname(__DIR__) . '/resources/js/price-range-filter.js'),
        ], 'price-range/price-range-filter');

        $this->publishes([
            __DIR__ . '/config/price-range-filter.php' => config_path('price-range-filter.php'),
        ], 'price-range-filter-config');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/price-range-filter'),
        ], 'price-range-filter-views');

        $this->publishes([
            dirname(__DIR__) . '/resources/js' => public_path('vendor/price-range-filter/js'),
        ], 'price-range-filter-assets');

        $this->publishes([
            dirname(__DIR__) . '/resources/css' => public_path('vendor/price-range-filter/css'),
        ], 'price-range-filter-assets');

        // Register the form component
        Livewire::component('price-range-filter::forms.components.price-range-filter', PriceRangeFilterComponent::class);
        
        // Register the table filter
        Livewire::component('price-range-filter::tables.filters.price-range-filter', PriceRangeFilterTable::class);
    }
}
