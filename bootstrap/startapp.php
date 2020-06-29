<?php

declare(strict_types=1);

error_reporting(E_ALL);

use Dotenv\Dotenv;
use League\Container\Container;
use App\Application;

// Load Application Wide Configurations
App\Library\Config::initialize();

// Load environment variables (.env)
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

// try Zebra Session https://github.com/stefangabos/Zebra_Session
$link = new MySQLi(getenv('DB_HOST'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DATABASE'), intval(getenv('DB_PORT')));
$session = new \App\Library\Zebra_Session($link, getenv('SESSION_SECURITY'));

// Set Language Default
if (isset($_SESSION['locale'])) {
    $locale = $_SESSION['locale'];
} else {
    $locale = 'en_US';
}
putenv("LANG=$locale");
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
$domain = 'messages';
bindtextdomain($domain, __DIR__ . '/../resources/language');
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8');

//  RUN Application
$container = new Container();
$container->delegate(
    new League\Container\ReflectionContainer
);
$app = new Application($container);

return $app->run();
