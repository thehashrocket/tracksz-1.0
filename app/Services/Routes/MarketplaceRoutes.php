<?php declare (strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers\Marketplace;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

// use Stripe\Product;

class MarketplaceRoutes extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        Router::class,
    ];

    /**
     * Register method,.
     */
    public function register()
    {
        // Bind a route collection to the container.
        $this->container->add(Router::class, function () {
            $strategy = (new ApplicationStrategy)->setContainer($this->container);
            $routes = (new Router)->setStrategy($strategy);
            // Main Inventory routes.  Must have a selected store
                       
            $routes->group('/marketplace', function (\League\Route\RouteGroup $route) {
                $route->get('/dashboard', Marketplace\MarketplaceController::class . '::add');
                $route->get('/list', Marketplace\MarketplaceController::class . '::view');
                $route->post('/dashboard/step2', Marketplace\MarketplaceController::class . '::addSecond');
                $route->get('/dashboard/step2', Marketplace\MarketplaceController::class . '::addSecond');
                $route->post('/dashboard/step3', Marketplace\MarketplaceController::class . '::addThree');
                $route->get('/edit/{Id:number}', Marketplace\MarketplaceController::class . '::editMarketplace');
                $route->post('/update', Marketplace\MarketplaceController::class . '::updateMarketplace');

                $route->post('/delete', Marketplace\MarketplaceController::class . '::deleteMarketData');

            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));

            return $routes;
        })->setShared(true);
    }
}
