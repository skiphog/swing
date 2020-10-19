<?php

return [
    /**
     * Окружение:  local | production
     */
    'env'          => 'local',

    /**
     * Имя приложения
     */
    'domain'       => 'unix',

    /**
     * Полный путь
     */
    'url'          => 'http://unix',

    /**
     * Использовать HTTPS в куках
     */
    'secure'       => false,

    /**
     * Подключение к бд
     */
    'db'           => [
        'driver' => 'mysql',

        'mysql' => [
            'host'     => '127.0.0.1',
            'database' => 'swingsite',
            'username' => 'root',
            'password' => 'root',
            'charset'  => 'utf8mb4',
            'options'  => [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                //\PDO::ATTR_EMULATE_PREPARES  => false,
                //\PDO::ATTR_STRINGIFY_FETCHES => false
            ],
        ],

        'sqlite' => [
            'database' => __DIR__ . '/database.sqlite',
        ],
    ],

    /**
     * Кеш
     */
    'cache_driver' => \System\Cache\Drivers\FileDriver::class,

    /**
     * Основной email
     */
    'mail'         => 'unix@unix.com',
];
