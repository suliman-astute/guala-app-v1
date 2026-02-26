<?php

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sqlsrv1' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_SQLSRV1_HOST', 'localhost'),
            'port' => env('DB_SQLSRV1_PORT', '1433'),
            'database' => env('DB_SQLSRV1_DATABASE', 'forge'),
            'username' => env('DB_SQLSRV1_USERNAME', 'forge'),
            'password' => env('DB_SQLSRV1_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

        'sqlsrv2' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_SQLSRV2_HOST', 'localhost'),
            'port' => env('DB_SQLSRV2_PORT', '1433'),
            'database' => env('DB_SQLSRV2_DATABASE', 'forge'),
            'username' => env('DB_SQLSRV2_USERNAME', 'forge'),
            'password' => env('DB_SQLSRV2_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    'migrations' => 'migrations',

];
