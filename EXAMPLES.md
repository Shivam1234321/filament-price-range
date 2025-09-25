# PriceRangeFilter - Usage Examples

This document provides comprehensive examples of how to use the PriceRangeFilter plugin in various scenarios.

## Table of Contents

1. [Basic Form Usage](#basic-form-usage)
2. [Advanced Form Configuration](#advanced-form-configuration)
3. [Table Filter Examples](#table-filter-examples)
4. [Database Integration](#database-integration)
5. [Custom Styling Examples](#custom-styling-examples)
6. [Real-world Use Cases](#real-world-use-cases)

## Basic Form Usage

### Simple Price Range Form

```php
<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\PriceRangeFilter\Forms\Components\PriceRangeFilter;

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

### Product Edit Form with Validation

```php
<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\PriceRangeFilter\Forms\Components\PriceRangeFilter;

class EditProduct extends EditRecord
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                PriceRangeFilter::make('price_range')
                    ->label('Price Range ($)')
                    ->minValue(1)
                    ->maxValue(50000)
                    ->step(50)
                    ->fromLabel('MIN PRICE')
                    ->toLabel('MAX PRICE')
                    ->required()
                    ->rules([
                        'required',
                        'array',
                        'min:2',
                        'max:2'
                    ])
                    ->rule('min.min', 'Minimum price must be at least $1')
                    ->rule('max.max', 'Maximum price cannot exceed $50,000'),
                
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
```

## Advanced Form Configuration

### Real Estate Property Form

```php
<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\PriceRangeFilter\Forms\Components\PriceRangeFilter;

class CreateProperty extends CreateRecord
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('address')
                    ->required(),
                
                PriceRangeFilter::make('price_range')
                    ->label('Price Range')
                    ->minValue(50000)
                    ->maxValue(2000000)
                    ->step(1000)
                    ->fromLabel('FROM $')
                    ->toLabel('TO $')
                    ->showLabels(true)
                    ->minFieldName('min_price')
                    ->maxFieldName('max_price')
                    ->dehydrateStateUsing(function ($state) {
                        return [
                            'min' => $state['min'] ?? 50000,
                            'max' => $state['max'] ?? 2000000,
                        ];
                    }),
                
                PriceRangeFilter::make('size_range')
                    ->label('Size Range (sq ft)')
                    ->minValue(500)
                    ->maxValue(10000)
                    ->step(50)
                    ->fromLabel('MIN SQ FT')
                    ->toLabel('MAX SQ FT'),
                
                Forms\Components\Select::make('property_type')
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                        'townhouse' => 'Townhouse',
                    ])
                    ->required(),
            ]);
    }
}
```

### Job Posting Form with Salary Range

```php
<?php

namespace App\Filament\Resources\JobResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\PriceRangeFilter\Forms\Components\PriceRangeFilter;

class CreateJob extends CreateRecord
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->required(),
                
                PriceRangeFilter::make('salary_range')
                    ->label('Salary Range (Annual)')
                    ->minValue(30000)
                    ->maxValue(200000)
                    ->step(5000)
                    ->fromLabel('MIN SALARY')
                    ->toLabel('MAX SALARY')
                    ->required()
                    ->helperText('Enter the minimum and maximum salary for this position'),
                
                Forms\Components\Select::make('experience_level')
                    ->options([
                        'entry' => 'Entry Level',
                        'mid' => 'Mid Level',
                        'senior' => 'Senior Level',
                        'executive' => 'Executive',
                    ])
                    ->required(),
                
                Forms\Components\Select::make('location')
                    ->options([
                        'remote' => 'Remote',
                        'hybrid' => 'Hybrid',
                        'onsite' => 'On-site',
                    ])
                    ->required(),
            ]);
    }
}
```

## Table Filter Examples

### Basic Product Table with Price Filter

```php
<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\PriceRangeFilter\Tables\Filters\PriceRangeFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price_range')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return '$' . number_format($state['min']) . ' - $' . number_format($state['max']);
                        }
                        return 'N/A';
                    }),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                PriceRangeFilter::make('price_range')
                    ->minColumn('min_price')
                    ->maxColumn('max_price')
                    ->minValue(0)
                    ->maxValue(10000)
                    ->step(100),
                
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
```

### Advanced Real Estate Table with Multiple Range Filters

```php
<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\PriceRangeFilter\Tables\Filters\PriceRangeFilter;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('price_range')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return '$' . number_format($state['min']) . ' - $' . number_format($state['max']);
                        }
                        return 'N/A';
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('size_range')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return number_format($state['min']) . ' - ' . number_format($state['max']) . ' sq ft';
                        }
                        return 'N/A';
                    }),
                
                Tables\Columns\TextColumn::make('property_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'house' => 'success',
                        'apartment' => 'info',
                        'condo' => 'warning',
                        'townhouse' => 'danger',
                    }),
            ])
            ->filters([
                PriceRangeFilter::make('price_range')
                    ->minColumn('min_price')
                    ->maxColumn('max_price')
                    ->minValue(50000)
                    ->maxValue(2000000)
                    ->step(1000)
                    ->fromLabel('MIN PRICE')
                    ->toLabel('MAX PRICE'),
                
                PriceRangeFilter::make('size_range')
                    ->minColumn('min_size')
                    ->maxColumn('max_size')
                    ->minValue(500)
                    ->maxValue(10000)
                    ->step(50)
                    ->fromLabel('MIN SQ FT')
                    ->toLabel('MAX SQ FT'),
                
                Tables\Filters\SelectFilter::make('property_type')
                    ->options([
                        'house' => 'House',
                        'apartment' => 'Apartment',
                        'condo' => 'Condo',
                        'townhouse' => 'Townhouse',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
```

## Database Integration

### Migration Examples

#### JSON Storage Method

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('price_range')->nullable(); // Stores {"min": 100, "max": 500}
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```

#### Separate Columns Method

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('address');
            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();
            $table->integer('min_size')->nullable();
            $table->integer('max_size')->nullable();
            $table->string('property_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
```

### Model Examples

#### JSON Storage Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price_range',
        'description',
    ];

    protected $casts = [
        'price_range' => 'array',
    ];

    // Accessor for formatted price range
    public function getFormattedPriceRangeAttribute()
    {
        if (!$this->price_range) {
            return 'N/A';
        }

        return '$' . number_format($this->price_range['min']) . ' - $' . number_format($this->price_range['max']);
    }

    // Scope for filtering by price range
    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->where(function ($q) use ($min, $max) {
            $q->whereJsonContains('price_range->min', '<=', $max)
              ->whereJsonContains('price_range->max', '>=', $min);
        });
    }
}
```

#### Separate Columns Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'title',
        'address',
        'min_price',
        'max_price',
        'min_size',
        'max_size',
        'property_type',
    ];

    // Accessor for formatted price range
    public function getFormattedPriceRangeAttribute()
    {
        if (!$this->min_price || !$this->max_price) {
            return 'N/A';
        }

        return '$' . number_format($this->min_price) . ' - $' . number_format($this->max_price);
    }

    // Accessor for formatted size range
    public function getFormattedSizeRangeAttribute()
    {
        if (!$this->min_size || !$this->max_size) {
            return 'N/A';
        }

        return number_format($this->min_size) . ' - ' . number_format($this->max_size) . ' sq ft';
    }

    // Scope for filtering by price range
    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->where('min_price', '<=', $max)
                    ->where('max_price', '>=', $min);
    }

    // Scope for filtering by size range
    public function scopeInSizeRange($query, $min, $max)
    {
        return $query->where('min_size', '<=', $max)
                    ->where('max_size', '>=', $min);
    }
}
```

## Custom Styling Examples

### Custom Color Themes

```css
/* Custom green theme */
.price-range-filter-container.variant-green .price-range-active {
    background-color: #10b981;
}

.price-range-filter-container.variant-green .price-range-handle {
    border-color: #10b981;
}

.price-range-filter-container.variant-green .price-range-handle:hover {
    background-color: #10b981;
    color: white;
}

/* Custom red theme */
.price-range-filter-container.variant-red .price-range-active {
    background-color: #ef4444;
}

.price-range-filter-container.variant-red .price-range-handle {
    border-color: #ef4444;
}

/* Custom purple theme */
.price-range-filter-container.variant-purple .price-range-active {
    background-color: #8b5cf6;
}

.price-range-filter-container.variant-purple .price-range-handle {
    border-color: #8b5cf6;
}
```

### Custom Size Variants

```css
/* Small variant */
.price-range-filter-container.size-small .price-range-track {
    height: 4px;
}

.price-range-filter-container.size-small .price-range-handle {
    width: 16px;
    height: 16px;
    top: -6px;
}

.price-range-filter-container.size-small .price-range-value {
    font-size: 1rem;
}

/* Large variant */
.price-range-filter-container.size-large .price-range-track {
    height: 12px;
}

.price-range-filter-container.size-large .price-range-handle {
    width: 32px;
    height: 32px;
    top: -12px;
}

.price-range-filter-container.size-large .price-range-value {
    font-size: 2.5rem;
}
```

## Real-world Use Cases

### E-commerce Product Filtering

```php
// Product catalog with price filtering
PriceRangeFilter::make('price_range')
    ->label('Price Range')
    ->minValue(0)
    ->maxValue(5000)
    ->step(50)
    ->fromLabel('MIN $')
    ->toLabel('MAX $')
    ->helperText('Filter products by price range')
```

### Real Estate Property Search

```php
// Property search with multiple range filters
PriceRangeFilter::make('price_range')
    ->label('Price Range')
    ->minValue(100000)
    ->maxValue(5000000)
    ->step(10000)
    ->fromLabel('FROM $')
    ->toLabel('TO $'),

PriceRangeFilter::make('size_range')
    ->label('Size Range (sq ft)')
    ->minValue(500)
    ->maxValue(10000)
    ->step(100)
    ->fromLabel('MIN SQ FT')
    ->toLabel('MAX SQ FT'),
```

### Job Board Salary Filtering

```php
// Job search with salary range
PriceRangeFilter::make('salary_range')
    ->label('Salary Range (Annual)')
    ->minValue(30000)
    ->maxValue(300000)
    ->step(5000)
    ->fromLabel('MIN SALARY')
    ->toLabel('MAX SALARY')
    ->helperText('Enter your desired salary range')
```

### Car Rental Price Filtering

```php
// Car rental with daily price range
PriceRangeFilter::make('daily_price_range')
    ->label('Daily Price Range')
    ->minValue(20)
    ->maxValue(500)
    ->step(10)
    ->fromLabel('MIN $/DAY')
    ->toLabel('MAX $/DAY')
```

### Event Ticket Pricing

```php
// Event tickets with price range
PriceRangeFilter::make('ticket_price_range')
    ->label('Ticket Price Range')
    ->minValue(10)
    ->maxValue(1000)
    ->step(5)
    ->fromLabel('MIN $')
    ->toLabel('MAX $')
    ->helperText('Filter events by ticket price')
```

This comprehensive example guide should help you implement the PriceRangeFilter plugin in various real-world scenarios. The plugin is highly flexible and can be adapted to many different use cases where range filtering is needed.
