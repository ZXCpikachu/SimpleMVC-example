<?php
/**
 * Конфигурационной файл приложения
 */
$config = [
    'core' => [ // подмассив используемый самим ядром фреймворка
        'db' => [
            'dns' => 'mysql:host=localhost;dbname=db_cms',
            'username' => 'myuser',
            'password' => '12345'
        ],
        'router' => [ // подсистема маршрутизация
            'class' => \ItForFree\SimpleMVC\Router\WebRouter::class,
	    'alias' => '@router',
        ],
        'mvc' => [ // настройки MVC
            'views' => [
                'base-template-path' => '../application/CMSviews/',
                'base-layouts-path' => '../application/CMSviews/layouts/',
                'footer-path' => '',
                'header-path' => ''
            ]
        ],
        'handlers' => [ // подсистема перехвата исключений
            'ItForFree\SimpleMVC\Exceptions\SmvcAccessException' 
		=> \application\handlers\UserExceptionHandler::class,
            'ItForFree\SimpleMVC\Exceptions\SmvcRoutingException' 
		=> \application\handlers\UserExceptionHandler::class
        ],
        'user' => [ // подсистема авторизации
            'class' => \application\models\User::class,
	    'construct' => [
                'session' => '@session',
                'router' => '@router'
             ], 
        ],
        'session' => [ // подсистема работы с сессиями
            'class' => ItForFree\SimpleMVC\Session::class,
            'alias' => '@session'
        ],
        'homepageNumArticles' => 5
    ]    
];

return $config;