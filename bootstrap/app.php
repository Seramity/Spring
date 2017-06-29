<?php

use Dotenv\Dotenv;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\{HtmlDumper, CliDumper};

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
    $view->addExtension(new \App\View\DebugExtension);

    $view->getEnvironment()->addGlobal('app', $container->settings['app']);

    return $view;
};

require __DIR__ . '/../app/routes.php';


// SYMFONY VARDUMPER
VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner;

    $htmlDumper = new HtmlDumper;
    $htmlDumper->setStyles([
        'default' => 'background-color:#fff; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
        'public' => 'color:#555',
        'protected' => 'color:#555',
        'private' => 'color:#555',
    ]);

    $dumper = PHP_SAPI === 'cli' ? new CliDumper : $htmlDumper;

    $dumper->dump($cloner->cloneVar($var));
});