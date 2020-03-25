<?php declare(strict_types = 1);

namespace App\Console;

require __DIR__.'/../../vendor/autoload.php';

use App\Library\Config;
use Dotenv\Dotenv;

// Load Application Wide Configurations
Config::initialize();

// Load environment variables (.env)
$dotenv = Dotenv::create(__DIR__.'/../../bootstrap');
$dotenv->load();

$cli = '';
if (isset($argv[1])) $cli = $argv[1];

if (!$cli) {
    echo 'Error; Usage from framework root is "php app/console/Console.php ClassToRun"' . "\n";
    echo '  With ClassToRun being the class with the "run" function you wish to execute.';
    exit();
} else {
    $cli = str_replace(':', '\\', $cli);
    $cli_class = 'App\Console\\'.$cli;
}

$command = new $cli_class();
$command->run();