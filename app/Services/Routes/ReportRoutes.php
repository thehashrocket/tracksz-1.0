<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Report;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class ReportRoutes extends AbstractServiceProvider
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
            $routes->group('/report', function (\League\Route\RouteGroup $route) {
                
                $route->get('/sales', Report\ReportController::class . '::sales');
                
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Store'))
                ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
