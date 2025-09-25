# Quick Start Guide

Get up and running with PriceRangeFilter in 5 minutes!

## 1. Installation

```bash
# Install via Composer
composer require price-range/price-range-filter

# Publish assets
php artisan vendor:publish --tag=price-range-filter-assets
php artisan vendor:publish --tag=price-range-filter-config
```

## 2. Add Assets to Your Layout

Add these lines to your Filament layout or app layout:

```blade
<!-- In resources/views/layouts/app.blade.php or your Filament layout -->
<link rel="stylesheet" href="{{ asset('vendor/price-range-filter/css/price-range-filter.css') }}">
<script src="{{ asset('vendor/price-range-filter/js/price-range-filter.js') }}"></script>
```

## 3. Create a Migration

```bash
php artisan make:migration add_price_range_to_products_table
```

Edit the migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('price_range')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_range');
        });
    }
};
```

Run the migration:

```bash
php artisan migrate
```

## 4. Update Your Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $casts = [
        'price_range' => 'array',
    ];
}
```

## 5. Use in a Filament Form

```php
<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use PriceRange\PriceRangeFilter\Forms\Components\PriceRangeFilter;

class CreateProduct extends CreateRecord
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                
                PriceRangeFilter::make('price_range')
                    ->label('Price Range')
                    ->minValue(0)
                    ->maxValue(10000)
                    ->step(100),
                
                Forms\Components\Textarea::make('description'),
            ]);
    }
}
```

## 6. Use in a Filament Table Filter

```php
<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use PriceRange\PriceRangeFilter\Tables\Filters\PriceRangeFilter;

class ProductResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price_range')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return '$' . number_format($state['min']) . ' - $' . number_format($state['max']);
                        }
                        return 'N/A';
                    }),
            ])
            ->filters([
                PriceRangeFilter::make('price_range')
                    ->minColumn('min_price')
                    ->maxColumn('max_price'),
            ]);
    }
}
```

## 7. That's It! 🎉

Your PriceRangeFilter is now ready to use! The slider will appear with:
- Two draggable handles for min/max values
- Current values displayed above the slider
- Smooth animations and touch support
- Dark mode compatibility

## Need More Examples?

Check out the [EXAMPLES.md](EXAMPLES.md) file for comprehensive usage examples including:
- Real estate property filtering
- Job board salary ranges
- E-commerce product filtering
- Custom styling examples
- Advanced configurations

## Troubleshooting

**Slider not appearing?**
- Make sure you've included the CSS and JS assets
- Check browser console for JavaScript errors

**Values not saving?**
- Verify your model has the correct cast for the price_range field
- Check that the migration was run successfully

**Styling issues?**
- Ensure Tailwind CSS is properly configured
- Check if there are CSS conflicts with your existing styles

## Support

- 📚 [Full Documentation](README.md)
- 💡 [Examples](EXAMPLES.md)
- 🐛 [Report Issues](https://github.com/filament/price-range-filter/issues)
- 💬 [Discussions](https://github.com/filament/price-range-filter/discussions)
