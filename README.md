# PriceRangeFilter - Laravel Filament Plugin

A custom Laravel Filament plugin that provides a beautiful dual-handle range slider for price filtering in forms and tables.

## Features

- 🎯 **Dual-handle range slider** with smooth dragging
- 📱 **Touch-friendly** interface for mobile devices
- 🎨 **Customizable styling** with multiple color variants
- 💾 **Flexible data storage** (JSON or separate columns)
- 🔧 **Highly configurable** min/max values and step size
- 📊 **Table filtering** support
- 📝 **Form field** support
- 🌙 **Dark mode** compatible
- ♿ **Accessibility** features included

## Installation

### 1. Install via Composer

```bash
composer require filament/price-range-filter
```

### 2. Publish Assets

Publish the configuration file:

```bash
php artisan vendor:publish --tag=price-range-filter-config
```

Publish the views (optional):

```bash
php artisan vendor:publish --tag=price-range-filter-views
```

Publish the assets (CSS and JavaScript):

```bash
php artisan vendor:publish --tag=price-range-filter-assets
```

### 3. Include Assets in Your Layout

Add the CSS and JavaScript to your Filament layout or app layout:

```blade
<!-- In your layout file -->
<link rel="stylesheet" href="{{ asset('vendor/price-range-filter/css/price-range-filter.css') }}">
<script src="{{ asset('vendor/price-range-filter/js/price-range-filter.js') }}"></script>
```

Or if you're using Vite, you can import them:

```js
// In your app.js or main.js
import '../vendor/price-range-filter/css/price-range-filter.css';
import '../vendor/price-range-filter/js/price-range-filter.js';
```

### 4. Configure (Optional)

Edit the published config file at `config/price-range-filter.php` to customize default values:

```php
return [
    'defaults' => [
        'min_value' => 0,
        'max_value' => 10000,
        'step' => 1,
        'from_label' => 'FROM',
        'to_label' => 'TO',
        'show_labels' => true,
    ],
    // ... other configuration options
];
```

## Usage

### In Filament Forms

```php
use Filament\PriceRangeFilter\Forms\Components\PriceRangeFilter;

// Basic usage
PriceRangeFilter::make('price_range')
    ->minValue(0)
    ->maxValue(10000)
    ->step(100)

// Advanced configuration
PriceRangeFilter::make('price_range')
    ->minValue(100)
    ->maxValue(50000)
    ->step(50)
    ->fromLabel('MIN PRICE')
    ->toLabel('MAX PRICE')
    ->showLabels(true)
    ->minFieldName('min_price')
    ->maxFieldName('max_price')
```

### In Filament Tables (as Filter)

```php
use Filament\PriceRangeFilter\Tables\Filters\PriceRangeFilter;

// Basic usage
PriceRangeFilter::make('price_range')
    ->minColumn('min_price')
    ->maxColumn('max_price')

// Advanced configuration
PriceRangeFilter::make('price_range')
    ->minColumn('min_price')
    ->maxColumn('max_price')
    ->minValue(0)
    ->maxValue(10000)
    ->step(100)
    ->fromLabel('MIN')
    ->toLabel('MAX')
```

## Database Storage

The plugin supports two storage methods:

### 1. JSON Storage (Default)

Store both values in a single JSON column:

```php
// Migration
Schema::table('products', function (Blueprint $table) {
    $table->json('price_range')->nullable();
});

// Model
class Product extends Model
{
    protected $casts = [
        'price_range' => 'array',
    ];
}
```

### 2. Separate Columns

Store values in separate columns:

```php
// Migration
Schema::table('products', function (Blueprint $table) {
    $table->integer('min_price')->nullable();
    $table->integer('max_price')->nullable();
});
```

## Configuration Options

### Form Component Options

| Method | Description | Default |
|--------|-------------|---------|
| `minValue(int $value)` | Minimum allowed value | 0 |
| `maxValue(int $value)` | Maximum allowed value | 10000 |
| `step(int $value)` | Step increment | 1 |
| `fromLabel(string $label)` | Label for minimum value | 'FROM' |
| `toLabel(string $label)` | Label for maximum value | 'TO' |
| `showLabels(bool $show)` | Show value labels | true |
| `minFieldName(string $name)` | Name for min input field | 'min_price' |
| `maxFieldName(string $name)` | Name for max input field | 'max_price' |

### Table Filter Options

| Method | Description | Default |
|--------|-------------|---------|
| `minColumn(string $column)` | Database column for minimum value | 'min_price' |
| `maxColumn(string $column)` | Database column for maximum value | 'max_price' |
| `minValue(int $value)` | Minimum allowed value | 0 |
| `maxValue(int $value)` | Maximum allowed value | 10000 |
| `step(int $value)` | Step increment | 1 |
| `fromLabel(string $label)` | Label for minimum value | 'FROM' |
| `toLabel(string $label)` | Label for maximum value | 'TO' |

## Styling

The plugin includes several built-in color variants:

```css
/* Apply variants via CSS classes */
.price-range-filter-container.variant-green { /* Green theme */ }
.price-range-filter-container.variant-red { /* Red theme */ }
.price-range-filter-container.variant-purple { /* Purple theme */ }
```

## Customization

### Custom CSS

You can customize the appearance by overriding the CSS classes:

```css
/* Custom styling */
.price-range-track {
    background-color: #your-color;
    height: 8px;
}

.price-range-handle {
    width: 24px;
    height: 24px;
    background-color: #your-color;
}
```

### Custom JavaScript

The JavaScript class is available globally as `PriceRangeFilter`:

```javascript
// Initialize manually
const slider = new PriceRangeFilter(element, {
    min: 0,
    max: 1000,
    step: 10,
    fromLabel: 'Min',
    toLabel: 'Max'
});

// Listen for changes
element.addEventListener('priceRangeChange', (event) => {
    console.log('Range changed:', event.detail);
});
```

## Requirements

- PHP 8.1+
- Laravel 10+
- Filament 3.0+
- Livewire 3.0+

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

If you encounter any issues or have questions, please open an issue on GitHub or contact the maintainers.

## Changelog

### v1.0.0
- Initial release
- Dual-handle range slider
- Form and table filter support
- Customizable styling
- Touch support
- Dark mode compatibility
