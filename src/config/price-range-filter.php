<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default values for the price range filter component.
    | You can override these values in your application by publishing
    | the config file and modifying the values.
    |
    */

    'defaults' => [
        'min_value' => 0,
        'max_value' => 10000,
        'step' => 1,
        'from_label' => 'FROM',
        'to_label' => 'TO',
        'show_labels' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Styling Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default styling for the price range filter.
    |
    */

    'styling' => [
        'variant' => 'blue', // blue, green, red, purple
        'size' => 'default', // small, default, large
        'theme' => 'auto', // light, dark, auto
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how the price range values are stored in the database.
    |
    */

    'database' => [
        'storage_method' => 'json', // json, separate_columns
        'min_column' => 'min_price',
        'max_column' => 'max_price',
        'json_column' => 'price_range',
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Default validation rules for the price range filter.
    |
    */

    'validation' => [
        'min' => 'required|integer|min:0',
        'max' => 'required|integer|min:0|gte:min',
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the assets that should be published and loaded.
    |
    */

    'assets' => [
        'publish_js' => true,
        'publish_css' => true,
        'auto_load' => true,
    ],
];
