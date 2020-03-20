<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Category;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
// use Stripe\Product;

class CategoryRoutes extends AbstractServiceProvider
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
            $routes->group('/category', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Category\CategoryController::class . '::view');
                $route->get('/add', Category\CategoryController::class . '::add');
                $route->get('/defaults', Category\CategoryController::class . '::defaults');
                $route->get('/lists', Category\CategoryController::class . '::get_list_records');
    
                $route->post('/defaults', Category\CategoryController::class . '::updateDefaults');
                $route->post('/insert_category', Category\CategoryController::class . '::add_Category');
                
                
            })->middleware($this->container->get('Csrf'))              
              ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
