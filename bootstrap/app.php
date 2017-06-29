<?php

use Dotenv\Dotenv;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'views' => [
            'debug' => getenv('APP_DEBUG') === 'true',
            'cache' => getenv('APP_VIEW_CACHE') === 'true'
        ]
    ]
]);

$container = $app->getContainer();

// VIEWS
$container['view'] = function ($container) {
    $view = \App\View\Factory::getEngine($container);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('app', $container->settings['app']);

    return $view;
};

require __DIR__ . '/../app/routes.php';