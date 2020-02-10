<?php declare(strict_types = 1);

namespace App\Services;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Delight\Auth\Auth;
use PDO;

class AuthServiceProvider extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        Auth::class,
    ];
    
    /**
     * Register method.
     */
    public function register()
    {
        // Bind the Auth request to the container.
        $this->container->add(Auth::class, function () {
            return new Auth($this->container->get(PDO::class));
        })->setShared(true);
    }
}