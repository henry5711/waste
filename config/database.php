Skip to content
Search or jump to…
Pull requests
Issues
Marketplace
Explore
 
@marcoslopez95 
marcoslopez95
/
TuSalud
Public
Code
Issues
Pull requests
Actions
Projects
Wiki
Security
Insights
Settings
TuSalud/config/database.php /
@marcoslopez95
marcoslopez95 first commit
Latest commit 4a8d61d 24 days ago
 History
 1 contributor
Executable File  147 lines (126 sloc)  4.94 KB
 
<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
        'onlyRead' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_READ_URL'),
            'host' => env('DB_READ_HOST', '127.0.0.1'),
            'port' => env('DB_READ_PORT', '5432'),
            'database' => env('DB_READ_DATABASE', 'forge'),
            'username' => env('DB_READ_USERNAME', 'forge'),
            'password' => env('DB_READ_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',
];