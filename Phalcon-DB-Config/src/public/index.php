<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Config\Adapter\Json;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->registerNamespaces(
    [
        'MyApp\Config' => APP_PATH .'/etc/',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'config',
    function () {
        $fileurl = '../app/etc/config.php';
        $factory  = new ConfigFactory();
        return $factory->newInstance('php', $fileurl);
    }
);

$container->set(
    'configs',
    function () {
        $fileurl = '../app/etc/configs.json';
        return new Json($fileurl);
    }
);

$container->set(
    'db',
    function () {
        return new Mysql($this['config']->db->toArray());
    }
);

$container->set(
    'mongo',
    function () {
        $mongo = new MongoClient();
        return $mongo->selectDB('phalt');
    },
    true
);

$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
