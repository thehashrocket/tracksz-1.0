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
            
                $route->get('/upload', Inventory\InventoryController::class . '::uploadInventory');
                $route->get('/view', Inventory\InventoryController::class . '::view');
                $route->get('/add', Inventory\InventoryController::class . '::add');
                $route->get('/defaults', Inventory\InventoryController::class . '::defaults');
                
                $route->post('/ftpupload', Inventory\InventoryController::class . '::uploadInventoryFTP');
                $route->post('/defaults', Inventory\InventoryController::class . '::updateDefaults');
                
                /*********** Save for Review - Delete if Not Used ************/
                // $route->get('/add', Product\ProductController::class . '::add'); InventoryController?
                /*$route->post('/place_market', Product\ProductController::class . InventoryController?
'::map_market_products'); */
                /*********** End Save for Review Delete ************/
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Auth'));
    
    
            /*********** Save for Review - Delete if Not Used ************/
            // Main CATEGORY routes.  Must have a selected store
            /* $routes->group('/inventory/category', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Category\CategoryController::class . '::view');
                $route->get('/add', Category\CategoryController::class . '::add');
                $route->get('/defaults', Category\CategoryController::class . '::defaults');
                $route->get('/lists', Category\CategoryController::class . '::get_list_records');
        
                $route->post('/defaults', Category\CategoryController::class . '::updateDefaults');
                $route->post('/insert_category', Category\CategoryController::class . '::add_Category');
        
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth')); */
            /*********** End Save for Review Delete ************/
            
            return $routes;
        })->setShared(true);
    }
}
