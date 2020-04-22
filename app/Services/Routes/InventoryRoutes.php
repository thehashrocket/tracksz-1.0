<?php

declare(strict_types=1);

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
                $route->get('/browse', Inventory\InventoryController::class . '::browse');
                $route->get('/upload', Inventory\InventoryController::class . '::uploadInventory');
                $route->get('/update', Inventory\InventoryController::class . '::updateInventoryView');

                $route->get('/view', Inventory\InventoryController::class . '::view');
                $route->get('/add', Inventory\InventoryController::class . '::add');
                $route->get('/defaults', Inventory\InventoryController::class . '::defaults');
                $route->post('/ftpupload', Inventory\InventoryController::class . '::UploadInventoryFTP');
                $route->post('/csvupload', Inventory\InventoryController::class . '::updateCsvInventory');
                $route->post('/defaults', Inventory\InventoryController::class . '::updateDefaults');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Inventory routes.  Must have a selected store


            // Main Product routes.  Must have a selected store
            $routes->group('/product', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Inventory\ProductController::class . '::view');
                $route->get('/add', Inventory\ProductController::class . '::add');
                $route->post('/place_market', Inventory\ProductController::class . '::MapMarketProducts');
                $route->post('/delete', Inventory\ProductController::class . '::DeleteProductData');
                $route->get('/edit/{Id:number}', Inventory\ProductController::class . '::EditProduct');
                $route->post('/update', Inventory\ProductController::class . '::updateProduct');
                $route->get('/upload', Inventory\ProductController::class . '::UploadProduct');
                $route->post('/ftpupload', Inventory\ProductController::class . '::UploadInventoryFTP');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Product routes.  Must have a selected store    

            // Main Category routes.  Must have a selected store
            $routes->group('/category', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Inventory\CategoryController::class . '::view');
                $route->get('/add', Inventory\CategoryController::class . '::add');
                $route->get('/defaults', Inventory\CategoryController::class . '::defaults');
                $route->get('/lists', Inventory\CategoryController::class . '::get_list_records');

                $route->post('/defaults', Inventory\CategoryController::class . '::updateDefaults');
                $route->post('/insert_category', Inventory\CategoryController::class . '::addCategory');
                $route->post('/delete', Inventory\CategoryController::class . '::deleteCategoryData');
                $route->get('/edit/{Id:number}', Inventory\CategoryController::class . '::editCategory');
                $route->post('/update', Inventory\CategoryController::class . '::updateCategory');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Category routes.  Must have a selected store

            // Main Attribute routes.  Must have a selected store
            $routes->group('/attribute', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\AttributesController::class . '::view');
                $route->get('/add', Inventory\AttributesController::class . '::add');

                $route->post('/insert_attribute', Inventory\AttributesController::class . '::addAttribute');
                $route->post('/delete', Inventory\AttributesController::class . '::deleteAttributeData');
                $route->get('/edit/{Id:number}', Inventory\AttributesController::class . '::editAttribute');
                $route->post('/update', Inventory\AttributesController::class . '::updateAttribute');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Attribute routes.  Must have a selected store

            // Main Attribute routes.  Must have a selected store
            $routes->group('/download', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\DownloadController::class . '::view');
                $route->get('/add', Inventory\DownloadController::class . '::add');

                $route->post('/insert_download', Inventory\DownloadController::class . '::addDownload');
                $route->post('/delete', Inventory\DownloadController::class . '::deleteDownloadData');
                $route->get('/edit/{Id:number}', Inventory\DownloadController::class . '::editDownload');
                $route->post('/update', Inventory\DownloadController::class . '::updateDownload');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Attribute routes.  Must have a selected store



            // Main Recurring routes.  Must have a selected store
            $routes->group('/recurring', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\RecurringController::class . '::view');
                $route->get('/add', Inventory\RecurringController::class . '::add');
                $route->post('/insert_recurring', Inventory\RecurringController::class . '::addRecurringData');
                $route->post('/delete', Inventory\RecurringController::class . '::deleteRecurringData');
                $route->get('/edit/{Id:number}', Inventory\RecurringController::class . '::editRecurring');
                $route->post('/update', Inventory\RecurringController::class . '::updateRecurring');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main Recurring routes.  Must have a selected store

            // Main CustomerGroup routes.  Must have a selected store
            $routes->group('/customergroup', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\CustomerGroupController::class . '::view');
                $route->get('/add', Inventory\CustomerGroupController::class . '::add');
                $route->post('/insert_customergroup', Inventory\CustomerGroupController::class . '::addCustomerGroupData');
                $route->post('/delete', Inventory\CustomerGroupController::class . '::deleteCustomerGroupData');
                $route->get('/edit/{Id:number}', Inventory\CustomerGroupController::class . '::editCustomerGroup');
                $route->post('/update', Inventory\CustomerGroupController::class . '::updateCustomerGroup');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));
            // Main CustomerGroup routes.  Must have a selected store

            return $routes;
        })->setShared(true);
    }
}
