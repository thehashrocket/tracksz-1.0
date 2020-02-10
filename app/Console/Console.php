<?php declare(strict_types = 1);

namespace App\Console;

require __DIR__.'/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Library\Config;

// Load Application Wide Configurations
Config::initialize();

$cli = '';
if (isset($argv[1])) $cli = $argv[1];

if (!$cli) {
    echo 'Error; Usage is "php Console.php ClassToRun"' . "\n";
    echo '  With class being the class with the "run" function.';
    exit();
} else {
    $cli_class = 'App\Console\\'.$cli;
}

$command = new $cli_class();
$command->run();