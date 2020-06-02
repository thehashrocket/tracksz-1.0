<?php

declare(strict_types=1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use League\Route\Router;

// For functionality
use App\Controllers\Inventory;
use App\Controllers\Marketplace;
use App\Controllers\Job\UploadQueue;
use League\Route\Strategy\ApplicationStrategy;
use League\Container\ServiceProvider\AbstractServiceProvider;

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
                $route->get('/browse', Inventory\ProductController::class . '::browse');
                $route->get('/condition-price', Inventory\InventoryController::class . '::conditionPriceBrowse');
                $route->get('/import', Inventory\InventoryController::class . '::uploadInventory');
                $route->get('/export', Inventory\InventoryController::class . '::exportInventoryBrowse');
                $route->post('/fileexport', Inventory\InventoryController::class . '::exportInventoryData');
                $route->get('/re-price', Inventory\InventoryController::class . '::repriceInventoryBrowse');
                $route->get('/update', Inventory\InventoryController::class . '::updateInventoryView');
                $route->get('/queue', Inventory\UploadQueue::class . '::fooAction');

                $route->get('/add', Inventory\ProductController::class . '::add');
                $route->get('/defaults', Inventory\InventoryController::class . '::defaults');
                $route->post('/ftpupload', Inventory\InventoryController::class . '::importInventoryFTP');
                $route->post('/csvupload', Inventory\InventoryController::class . '::updateCsvInventory');
                $route->post('/importupload', Inventory\InventoryController::class . '::browseInventoryUpload');
                $route->post('/importdelete', Inventory\InventoryController::class . '::browseInventoryDelete');
                $route->post('/defaults', Inventory\InventoryController::class . '::updateDefaults');
                $route->get('/inventory-settings', Inventory\InventoryController::class . '::inventorySettingsBrowse');
                $route->get('/advanced-settings', Inventory\InventoryController::class . '::advancedSettingsBrowse');
                $route->get('/excel', Inventory\InventoryController::class . '::excel');
                $route->post('/update_settings', Inventory\InventoryController::class . '::updateSettings');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));

            // Main Inventory routes.  Must have a selected store


            // Main Product routes.  Must have a selected store
            $routes->group('/product', function (\League\Route\RouteGroup $route) {
                $route->get('/view', Inventory\ProductController::class . '::view');
                $route->get('/add', Inventory\ProductController::class . '::add');
                $route->get('/add_2', Inventory\ProductController::class . '::add_2');
                $route->post('/place_market', Inventory\ProductController::class . '::MapMarketProducts');
                $route->post('/delete', Inventory\ProductController::class . '::DeleteProductData');
                $route->get('/edit/{Id:number}', Inventory\ProductController::class . '::EditProduct');
                $route->post('/update', Inventory\ProductController::class . '::updateProduct');
                $route->get('/upload', Inventory\ProductController::class . '::UploadProduct');
                $route->post('/ftpupload', Inventory\ProductController::class . '::UploadInventoryFTP');
                $route->post('/delete_product', Inventory\ProductController::class . '::delete_productProductData');
                $route->post('/change_marketplace', Inventory\ProductController::class . '::marketplacebyproduct');

                $route->post('/export_product', Inventory\ProductController::class . '::export_ProductData');
                $route->post('/market_price', Marketplace\MarketplaceController::class . '::addMarketPrices');
                $route->post('/market_price_update', Marketplace\MarketplaceController::class . '::updateMarketPrices');
                $route->post('/market_template_update', Marketplace\MarketplaceController::class . '::updateMarketTemplate');
                $route->post('/market_shiprates', Marketplace\MarketplaceController::class . '::updateMarketShipRates');
                $route->post('/market_handletime', Marketplace\MarketplaceController::class . '::updateMarketHandling');
                $route->post('/market_additional_info', Marketplace\MarketplaceController::class . '::updateMarketAdditionalInfo');

                $route->post('/no_catalog', Inventory\ProductController::class . '::addNoneCatalogProducts');
                $route->post('/no_catalog_update', Inventory\ProductController::class . '::updateNoneCatalogProducts');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
            // Main Product routes.  Must have a selected store

            // Main Category routes.  Must have a selected store
            $routes->group('/category', function (\League\Route\RouteGroup $route) {
                $route->get('/browse', Inventory\CategoryController::class . '::browse');
                $route->get('/add', Inventory\CategoryController::class . '::add');
                $route->post('/insert_category', Inventory\CategoryController::class . '::addCategory');
                $route->post('/delete', Inventory\CategoryController::class . '::deleteCategoryData');
                $route->get('/edit/{Id:number}', Inventory\CategoryController::class . '::editCategory');
                $route->post('/update', Inventory\CategoryController::class . '::updateCategory');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
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
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
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
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
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
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
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
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
            // Main CustomerGroup routes.  Must have a selected store

            // Main ProductDiscount routes.  Must have a selected store
            $routes->group('/productdiscount', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\ProductDiscountController::class . '::view');
                $route->get('/add', Inventory\ProductDiscountController::class . '::add');
                $route->post('/insert_discount', Inventory\ProductDiscountController::class . '::addProductDiscountData');
                $route->post('/delete', Inventory\ProductDiscountController::class . '::deleteProductDiscountData');
                $route->get('/edit/{Id:number}', Inventory\ProductDiscountController::class . '::editProductDiscount');
                $route->post('/update', Inventory\ProductDiscountController::class . '::updateProductDiscount');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
            // Main ProductDiscount routes.  Must have a selected store

            // Main ProductSpecial routes.  Must have a selected store
            $routes->group('/productspecial', function (\League\Route\RouteGroup $route) {
                $route->get('/page', Inventory\ProductSpecialController::class . '::view');
                $route->get('/add', Inventory\ProductSpecialController::class . '::add');
                $route->post('/insert_discount', Inventory\ProductSpecialController::class . '::addProductSpecialData');
                $route->post('/delete', Inventory\ProductSpecialController::class . '::deleteProductSpecialData');
                $route->get('/edit/{Id:number}', Inventory\ProductSpecialController::class . '::editProductSpecial');
                $route->post('/update', Inventory\ProductSpecialController::class . '::updateProductSpecial');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'))
                ->middleware($this->container->get('Store'));
            // Main ProductSpecial routes.  Must have a selected store

            return $routes;
        })->setShared(true);
    }
}
