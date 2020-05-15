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
                $route->get('/confirmation-file', Order\OrderController::class . '::loadConfirmationFile');
                $route->get('/export-order', Order\OrderController::class . '::loadExportOrder');
                $route->get('/shipping', Order\OrderController::class . '::loadShippingOrder');
                $route->get('/order-settings', Order\OrderController::class . '::loadOrderSetting');
                $route->get('/postage-settings', Order\OrderController::class . '::loadPostageSetting');
                $route->get('/label-settings', Order\OrderController::class . '::loadLabelSetting');

                $route->get('/add', Order\OrderController::class . '::addLoadView');
                $route->post('/insert_order', Order\OrderController::class . '::addOrder');
                $route->post('/delete', Order\OrderController::class . '::deleteOrderData');
                $route->get('/edit/{Id:number}', Order\OrderController::class . '::editOrder');
                $route->post('/update', Order\OrderController::class . '::updateOrder');
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Store'))
                ->middleware($this->container->get('Auth'));
            return $routes;
        })->setShared(true);
    }
}
