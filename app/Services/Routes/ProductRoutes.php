<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Product;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
// use Stripe\Product;

class ProductRoutes extends AbstractServiceProvider
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
            $routes->group('/product', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Product\ProductController::class . '::view');
                $route->get('/add', Product\ProductController::class . '::add');
                $route->get('/defaults', Product\ProductController::class . '::defaults');
    
                $route->post('/defaults', Product\ProductController::class . '::updateDefaults');
                $route->post('/place_market', Product\ProductController::class . '::map_market_products');
                
                
            })->middleware($this->container->get('Csrf'))              
              ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
