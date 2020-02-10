<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Inventory;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class InventoryRoutes extends AbstractServiceProvider
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
    
            // Main Inventory routes.  Must have a selected store
            $routes->group('/inventory', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Inventory\InventoryController::class . '::view');
                $route->get('/add', Inventory\InventoryController::class . '::add');
                $route->get('/defaults', Inventory\InventoryController::class . '::defaults');
    
                $route->post('/defaults', Inventory\InventoryController::class . '::updateDefaults');
                
                
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Store'))
              ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
