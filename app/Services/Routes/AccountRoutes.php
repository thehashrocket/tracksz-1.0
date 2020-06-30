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
                $route->get('/profile', Account\AccountController::class . '::profile');
                $route->get('/profile/edit', Account\AccountController::class . '::editProfile');
                $route->post('/profile', Account\AccountController::class . '::updateProfile');
                $route->get('/payment', Account\PaymentController::class . '::payment');
                $route->get('/stores', Account\StoreController::class . '::stores');
                $route->get('/store', Account\StoreController::class . '::store');
                $route->get('/store/edit/{Id:number}', Account\StoreController::class . '::edit');
                $route->get('/stripe/connect/{Id:number}', Account\StoreController::class . '::stripeConnect');

                // change password & email for profile
                $route->post('/password', Controllers\AuthController::class . '::updatePassword');
                $route->post('/email', Controllers\AuthController::class . '::updateEmail');
                $route->post('/payment', Account\PaymentController::class . '::addPaymentMethod');
                $route->post('/payment/delete/{Id:number}', Account\PaymentPaymentController::class . '::delete');
                $route->post('/payment/edit/{Id:number}', Account\PaymentController::class . '::edit');
                $route->post('/address/delete/{Id:number}', Account\PaymentController::class . '::deleteAddress');
                $route->post('/store', Account\StoreController::class . '::storeUpdate');
                $route->post('/store/delete/{Id:number}', Account\StoreController::class . '::delete');
                $route->post('/store/restore/{Id:number}', Account\StoreController::class . '::restore');
                $route->post('/store/active/{Id:number}', Account\StoreController::class . '::setActive');
            })->middleware($this->container->get('Auth'));


            // Shipping Methods for a Specific Store
            $routes->group('/account/shipping-methods', function (\League\Route\RouteGroup $route) {
                $route->get('/', Account\ShippingController::class.'::viewMethods');
                $route->get('/add', Account\ShippingController::class.'::viewAddMethod');
                $route->get('/edit/{Id:number}', Account\ShippingController::class.'::viewUpdateMethod');

                $route->post('/create', Account\ShippingController::class.'::createMethod');
                $route->post('/delete/{Id:number}', Account\ShippingController::class.'::deleteMethod');
                $route->post('/edit', Account\ShippingController::class.'::updateMethod');
                $route->get('/assign/{MethodId:number}/{ZoneId:number}', Account\ShippingController::class.'::assignMethod');
                $route->get('/unassign/{MethodId:number}/{ZoneId:number}', Account\ShippingController::class.'::unassignMethod');
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Store'))
              ->middleware($this->container->get('Auth'));

            // Create/manage shipping zones
            $routes->group('/account/shipping-zones', function (\League\Route\RouteGroup $route) {
                $route->get('/', Account\ShippingController::class.'::viewZones');
                $route->get('/add', Account\ShippingController::class.'::viewAddZone');
                $route->get('/edit/{ZoneId:number}', Account\ShippingController::class.'::viewUpdateZone');
                $route->get('/manage/{ZoneId:number}', Account\ShippingController::class.'::viewManageZone');

                $route->post('/create', Account\ShippingController::class.'::createZone');
                $route->post('/edit/{Id:number}', Account\ShippingController::class.'::updateZone');
                $route->post('/delete/{Id:number}', Account\ShippingController::class.'::deleteZone');
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Store'))
              ->middleware($this->container->get('Auth'));

            $routes->group('/account/shipping-assign', function(\League\Route\RouteGroup $route) {
                $route->get('/', Account\ShippingController::class.'::viewAssignZonesBulk');
                $route->get('/individual/countries', Account\ShippingController::class.'::viewAssignZonesIndividualCountries');
                $route->get('/individual/states/{CountryId:number}', Account\ShippingController::class.'::viewAssignZonesIndividualStates');
                $route->get('/individual/zip/delete/{AssignmentId:number}/{StateId:number}', Account\ShippingController::class.'::deleteZipCodeAssignment');
                $route->get('/individual/zip/{StateId:number}', Account\ShippingController::class.'::viewAssignZonesIndividualZip');

                $route->post('/bulk-assign', Account\ShippingController::class.'::bulkAssign');
                $route->post('/individual/states', Account\ShippingController::class.'::assignZoneToState');
                $route->post('/individual/zip', Account\ShippingController::class.'::assignZoneToZipRange');
            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Store'))
              ->middleware($this->container->get('Auth'));

            // Assign shipping zones to regions
            $routes->group('/account/shipping-assign', function (\League\Route\RouteGroup $route) {

            })->middleware($this->container->get('Csrf'))
              ->middleware($this->container->get('Store'))
              ->middleware($this->container->get('Auth'));

            return $routes;
        })->setShared(true);
    }
}