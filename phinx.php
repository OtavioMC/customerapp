<?php
    require_once __DIR__ . "/config.php";

    return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASSWORD'],
                'port' => '3306',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_unicode_ci'
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASSWORD'],
                'port' => '3306',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASSWORD'],
                'port' => '3306',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_unicode_ci'
            ]
        ],
        'version_order' => 'creation'
    ];
