<?php
/**
 * Конфигурационной файл консольного приложения
 */
$config = [
    'core' => [ // подмассив, используемый самим ядром фреймворка
        'db' => [
            'dns' => 'mysql:host=localhost;dbname=db_cms',
            'username' => 'myuser',
            'password' => '12345'
        ],
        'router' => [ // подсистема маршрутизации
            'class' => \ItForFree\SimpleMVC\Router\ConsoleRouter::class,
	    'alias' => '@router'
        ]
    ]    
];

return $config;