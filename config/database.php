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

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'read' => ['host' => env('DB_HOST_READ', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE', '127.0.0.1')],
            'driver' => env('DB_CONNECTION'),
            'url' => env('DATABASE_URL'),
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
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'platform' => [
            'read' => ['host' => env('DB_HOST_READ_PLATFORM', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_PLATFORM', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_PLATFORM'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_PLATFORM', '3306'),
            'database' => env('DB_DATABASE_PLATFORM', 'forge'),
            'username' => env('DB_USERNAME_PLATFORM', 'forge'),
            'password' => env('DB_PASSWORD_PLATFORM', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'slot_system' => [
            'read' => ['host' => env('DB_HOST_READ_SLOT_SYSTEM', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_SLOT_SYSTEM', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_SLOT_SYSTEM'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_SLOT_SYSTEM', '3306'),
            'database' => env('DB_DATABASE_SLOT_SYSTEM', 'forge'),
            'username' => env('DB_USERNAME_SLOT_SYSTEM', 'forge'),
            'password' => env('DB_PASSWORD_SLOT_SYSTEM', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'slot_user' => [
            'read' => ['host' => env('DB_HOST_READ_SLOT_USER', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_SLOT_USER', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_SLOT_USER'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_SLOT_USER', '3306'),
            'database' => env('DB_DATABASE_SLOT_USER', 'forge'),
            'username' => env('DB_USERNAME_SLOT_USER', 'forge'),
            'password' => env('DB_PASSWORD_SLOT_USER', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'slot_log' => [
            'read' => ['host' => env('DB_HOST_READ_SLOT_LOG', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_SLOT_LOG', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_SLOT_LOG'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_SLOT_LOG', '3306'),
            'database' => env('DB_DATABASE_SLOT_LOG', 'forge'),
            'username' => env('DB_USERNAME_SLOT_LOG', 'forge'),
            'password' => env('DB_PASSWORD_SLOT_LOG', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'slot_common' => [
            'read' => ['host' => env('DB_HOST_READ_SLOT_COMMON', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_SLOT_COMMON', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_SLOT_COMMON'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_SLOT_COMMON', '3306'),
            'database' => env('DB_DATABASE_SLOT_COMMON', 'forge'),
            'username' => env('DB_USERNAME_SLOT_COMMON', 'forge'),
            'password' => env('DB_PASSWORD_SLOT_COMMON', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

        'assigns' => [
            'read' => ['host' => env('DB_HOST_READ_ASSIGNS', '127.0.0.1')],
            'write' => ['host' => env('DB_HOST_WRITE_ASSIGNS', '127.0.0.1')],
            'driver' => env('DB_CONNECTION_ASSIGNS'),
            'url' => env('DATABASE_URL'),
            'port' => env('DB_PORT_ASSIGNS', '3306'),
            'database' => env('DB_DATABASE_ASSIGNS', 'forge'),
            'username' => env('DB_USERNAME_ASSIGNS', 'forge'),
            'password' => env('DB_PASSWORD_ASSIGNS', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_EMULATE_PREPARES => true,
            ]) : [],
        ],

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

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
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

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            // 'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
            'prefix' => '',
        ],

        'default' => [
//            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],


		'redis_slots'	=> [

			'client'	=> env('REDIS_SLOT_CLIENT', 'phpredis'),

			'options'	=> [
				'cluster'	=> env('REDIS_SLOT_CLUSTER', 'redis'),
				'prefix'	=> '',
			],

			'host'			=> env('REDIS_SLOT_HOST', '127.0.0.1'),
			'password'		=> env('REDIS_SLOT_PASSWORD', null),
			'port'			=> env('REDIS_SLOT_PORT', '6379'),
			'database'		=> env('REDIS_SLOT_DB', '0'),
			'read_timeout'	=> env('REDIS_SLOT_TIMEOUT', 5),
		],


		'redis_store'	=> [

			'client'	=> env('REDIS_STORE_CLIENT', 'phpredis'),

			'options'	=> [
				'cluster'	=> env('REDIS_STORE_CLUSTER', 'redis'),
				'prefix'	=> '',
			],

			'host'			=> env('REDIS_STORE_HOST', '127.0.0.1'),
			'password'		=> env('REDIS_STORE_PASSWORD', null),
			'port'			=> env('REDIS_STORE_PORT', '6379'),
			'database'		=> env('REDIS_STORE_DB', '0'),
			'read_timeout'	=> env('REDIS_STORE_TIMEOUT', 5),
		],


		'redis_poker'	=> [

			'client'	=> env('REDIS_STORE_CLIENT', 'phpredis'),

			'options'	=> [
				'cluster'	=> env('REDIS_STORE_CLUSTER', 'redis'),
				'prefix'	=> '',
			],

			'host'			=> env('REDIS_POKER_HOST', '127.0.0.1'),
			'password'		=> env('REDIS_POKER_PASSWORD', null),
			'port'			=> env('REDIS_POKER_PORT', '6379'),
			'database'		=> env('REDIS_POKER_DB', '0'),
			'read_timeout'	=> env('REDIS_POKER_TIMEOUT', 5),
		],
    ],


];
