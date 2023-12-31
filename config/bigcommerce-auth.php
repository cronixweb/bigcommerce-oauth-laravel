<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BigCommerce APP client id
    |--------------------------------------------------------------------------
    |
    | BigCommerce App client id is an id you will get after registering your app
    | at https://devtools.bigcommerce.com/my/apps. Click on "View Client ID"
    | button. You will see App client id there.
    |
     */
    'client_id' => env('BC_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | BigCommerce APP secret
    |--------------------------------------------------------------------------
    |
    | BigCommerce App secret is an id you will get after registering your app
    | at https://devtools.bigcommerce.com/my/apps. Click on "View Client ID"
    | button. You will see App secret there.
    |
     */
    'secret' => env('BC_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Redirect path
    |--------------------------------------------------------------------------
    |
    | This redirect path used to redirect after installation of app
    |
     */
    'redirect_path' => env('BC_REDIRECT_PATH'),

    /*
    |--------------------------------------------------------------------------
    | Session key
    |--------------------------------------------------------------------------
    |
    | This redirect path used to redirect after installation of app
    |
     */
    'session_key' => 'bigcommerce_auth',

    /*
    |--------------------------------------------------------------------------
    | Installation error page
    |--------------------------------------------------------------------------
    |
    | If you want to show your custom error page replace your view here.
    | Example: resources/views/errors/install-error.blade.php
    | ==> view value will be "errors.install-error"
    |
     */
    'error_view' => null,

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
     */
    'models' => [

        /*
        |--------------------------------------------------------------------------
        | BigCommerce Store Model
        |--------------------------------------------------------------------------
        |
         */
        'store_model' => \CronixWeb\BigCommerceAuth\Models\Store::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Database Tables
    |--------------------------------------------------------------------------
     */
    'tables' => [

        /*
        |--------------------------------------------------------------------------
        | Stores table
        |--------------------------------------------------------------------------
        |
         */
        'stores' => 'stores',

        /*
        |--------------------------------------------------------------------------
        | Stores has User table
        |--------------------------------------------------------------------------
        |
         */
        'store_has_users' => 'store_has_users',

    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Controllers
    |--------------------------------------------------------------------------
     */
    'controllers' => [

        /*
        |--------------------------------------------------------------------------
        | Big Commerce Load API call controller
        |--------------------------------------------------------------------------
        |
         */
        'load' => \CronixWeb\BigCommerceAuth\Http\Controllers\BigLoadController::class,

        /*
        |--------------------------------------------------------------------------
        | Big Commerce Install API call controller
        |--------------------------------------------------------------------------
        |
         */
        'install' => \CronixWeb\BigCommerceAuth\Http\Controllers\BigInstallController::class,

        /*
        |--------------------------------------------------------------------------
        | Big Commerce Uninstall API call controller
        |--------------------------------------------------------------------------
        |
         */
        'uninstall' => \CronixWeb\BigCommerceAuth\Http\Controllers\BigUninstallController::class,

        /*
        |--------------------------------------------------------------------------
        | Big Commerce Uninstall API call controller
        |--------------------------------------------------------------------------
        |
         */
        'remove_user' => \CronixWeb\BigCommerceAuth\Http\Controllers\BigRemoveUserController::class,

    ],
];
