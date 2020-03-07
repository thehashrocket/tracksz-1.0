<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers;
use App\Controllers\Account;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class AccountRoutes extends AbstractServiceProvider
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
    
            // stripe connect response uri for setting up new stripe connect account
            $routes->get('/account/connect/{response}', Account\StoreController::class.'::connect')
                ->middleware($this->container->get('Auth'));
            
            // Main Account routes
            $routes->group('/account', function (\League\Route\RouteGroup $route) {
                $route->get('/panel', Account\AccountController::class . '::index');
                $route->get('/', Account\AccountController::class . '::index');
                
                // these are done less often, put later in list
                $route->get('/profile', Account\AccountController::class.'::profile');
                $route->post('/profile', Account\AccountController::class.'::updateProfile');
                $route->get('/payment', Account\PaymentController::class.'::payment');
                $route->get('/stores', Account\StoreController::class.'::stores');
                $route->get('/shipping-methods', Account\ShippingController::class.'::viewMethods');
                $route->get('/shipping-methods/create', Account\ShippingController::class.'::viewCreateMethod');
                $route->get('/shipping-methods/edit/{Id:number}', Account\ShippingController::class.'::viewUpdateMethod');
                $route->get('/shipping-zones', Account\ShippingController::class.'::viewZones');
                $route->get('/shipping-zones/create', Account\ShippingController::class.'::viewCreateZone');
                $route->get('/shipping-zones/edit/{Id:number}', Account\ShippingController::class.'::viewUpdateZone');
                $route->get('/shipping-zones/manage/{Id:number}', Account\ShippingController::class.'::viewManageZone');
                $route->get('/shipping-zones/assign/{MethodId:number}/{ZoneId:number}', Account\ShippingController::class.'::assignMethodToZone');
                $route->get('/store', Account\StoreController::class.'::store');
                $route->get('/store/edit/{Id:number}', Account\StoreController::class.'::edit');
                $route->get('/stripe/connect/{Id:number}', Account\StoreController::class.'::stripeConnect');
                
                // change password & email for profile
                $route->post('/password', Controllers\AuthController::class.'::updatePassword');
                $route->post('/email', Controllers\AuthController::class.'::updateEmail');
                $route->post('/payment', Account\PaymentController::class.'::addPaymentMethod');
                $route->post('/payment/delete/{Id:number}', Account\PaymentPaymentController::class.'::delete');
                $route->post('/payment/edit/{Id:number}', Account\PaymentController::class.'::edit');
                $route->post('/address/delete/{Id:number}', Account\PaymentController::class.'::deleteAddress');
                $route->post('/store', Account\StoreController::class.'::storeUpdate');
                $route->post('/store/delete/{Id:number}', Account\StoreController::class.'::delete');
                $route->post('/store/restore/{Id:number}', Account\StoreController::class.'::restore');
                $route->post('/store/active/{Id:number}', Account\StoreController::class.'::setActive');
                $route->post('/shipping-methods/create', Account\ShippingController::class.'::createMethod');
                $route->post('/shipping-methods/delete/{Id:number}', Account\ShippingController::class.'::deleteMethod');
                $route->post('/shipping-methods/edit', Account\ShippingController::class.'::updateMethod');
                $route->post('/shipping-zones/create', Account\ShippingController::class.'::createZone');
                $route->post('/shipping-zones/edit', Account\ShippingController::class.'::updateZone');
                $route->post('/shipping-zones/delete/{Id:number}', Account\ShippingController::class.'::deleteZone');
                
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Auth'));
    
            return $routes;
        })->setShared(true);
    }
}