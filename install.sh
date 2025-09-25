#!/bin/bash

# PriceRangeFilter Plugin Installation Script
# This script helps you install and configure the PriceRangeFilter plugin

echo "🎯 Installing PriceRangeFilter Plugin for Laravel Filament"
echo "=================================================="

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: This doesn't appear to be a Laravel project directory."
    echo "Please run this script from your Laravel project root."
    exit 1
fi

# Check if Composer is available
if ! command -v composer &> /dev/null; then
    echo "❌ Error: Composer is not installed or not in PATH."
    echo "Please install Composer first: https://getcomposer.org/"
    exit 1
fi

echo "📦 Installing plugin via Composer..."
composer require price-range/price-range-filter:dev-master

echo "📁 Publishing configuration..."
php artisan vendor:publish --tag=price-range-filter-config

echo "🎨 Publishing assets..."
php artisan vendor:publish --tag=price-range-filter-assets

echo "📝 Publishing views (optional)..."
php artisan vendor:publish --tag=price-range-filter-views

echo "🔧 Creating sample migration..."
php artisan make:migration add_price_range_to_products_table

echo "✅ Installation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Edit the migration file to add price_range column"
echo "2. Run: php artisan migrate"
echo "3. Include assets in your layout file"
echo "4. Check the README.md for usage examples"
echo ""
echo "📚 Documentation:"
echo "- README.md - Installation and basic usage"
echo "- EXAMPLES.md - Comprehensive usage examples"
echo "- config/price-range-filter.php - Configuration options"
echo ""
echo "🎉 Happy coding!"
