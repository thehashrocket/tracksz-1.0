<?php declare(strict_types = 1);

namespace App\Services;

use League\Container\ServiceProvider\AbstractServiceProvider;
use PDO;

class PDOServiceProvider extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        PDO::class,
    ];
    
    /**
     * Register method.
     */
    public function register()
    {
        $this->container->add(PDO::class, function () {
            return new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        })->setShared(true);
    }
}
