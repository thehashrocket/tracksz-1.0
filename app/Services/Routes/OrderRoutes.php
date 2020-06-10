<?php

declare(strict_types=1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Order;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

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
            $routes->group('/order', function (\League\Route\RouteGroup $route) {
                $route->get('/browse', Order\OrderController::class . '::browse');
                $route->get('/batch-move', Order\OrderController::class . '::loadBatchMove');
                $route->post('/update-batchmove', Order\OrderController::class . '::updateBatchMove');
                $route->get('/confirmation-file', Order\OrderController::class . '::loadConfirmationFile');
                $route->get('/export-order', Order\OrderController::class . '::loadExportOrder');
                $route->post('/export-order-data', Order\OrderController::class . '::exportOrderData');
                $route->get('/shipping', Order\OrderController::class . '::loadShippingOrder');
                //$route->get('/order-settings', Order\OrderController::class . '::loadOrderSetting');
                $route->get('/postage-settings', Order\OrderController::class . '::loadPostageSetting');
                $route->post('/add_update_postage_setting', Order\OrderController::class . '::postageAddUpdateSettings');
                $route->get('/label-settings', Order\OrderController::class . '::loadLabelSetting');
                $route->post('/add_update_label_setting', Order\OrderController::class . '::labelAddUpdateSettings');

                $route->post('/export_order_list', Order\OrderController::class . '::export_Orderlist');

                $route->get('/order-settings', Order\OrderController::class . '::orderSettingsBrowse');
                $route->post('/order_update_settings', Order\OrderController::class . '::orderUpdateSettings');
                $route->get('/add', Order\OrderController::class . '::addLoadView');
                $route->post('/insert_order', Order\OrderController::class . '::addOrder');
                $route->post('/delete', Order\OrderController::class . '::deleteOrderData');
                $route->get('/edit/{Id:number}', Order\OrderController::class . '::editOrder');
                $route->post('/update', Order\OrderController::class . '::updateOrder');
                $route->post('/filter_order', Order\OrderController::class . '::searchOrder');
                $route->post('/update_status', Order\OrderController::class . '::updateOrderStatus');
                $route->post('/order_change', Order\OrderController::class . '::updateOrderChange');
                $route->get('/pick', Order\OrderController::class . '::pickOrder');
                $route->get('/packing', Order\OrderController::class . '::packingOrder');
                $route->get('/mailing', Order\OrderController::class . '::mailingOrder');
                $route->post('/pdf_mailing', Order\OrderController::class . '::pdfGenerateMailingLoad');
                $route->post('/pdf_packing', Order\OrderController::class . '::pdfGeneratePackingLoad');
                $route->post('/pdf_pick', Order\OrderController::class . '::pdfGeneratePickLoad');
                $route->post('/confirmation_files', Order\OrderController::class . '::confirmFilesUpload');
                $route->get('/download/{Id:number}', Order\OrderController::class . '::confirmFilesDownload');
                $route->get('/view/{Id:number}', Order\OrderController::class . '::confirmFilesView');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Store'))
                ->middleware($this->container->get('Auth'));
            return $routes;
        })->setShared(true);
    }
}
