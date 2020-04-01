<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Models;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use Delight\Auth\Auth;
use PDO;

class OrderRoutes extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        Router::class
    ];
    
    /**
     * Register method,.
     */
    public function register()
    {
        // Bind a route collection to the container.
        $this->container->add(Router::class, function () {
            $strategy = (new ApplicationStrategy)->setContainer($this->container);
            $routes   = (new Router)->setStrategy($strategy);
            
            // Use for AJAX Calls for Logged in Users, Need Json results
            $routes->group('/orders', function (\League\Route\RouteGroup $route) {
    
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Store'))
                ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
