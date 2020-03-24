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
                $route->post('/dashboard/step2/tejas', Marketplace\MarketplaceController::class . '::add_second');
                $route->post('/dashboard/step3/thanks', Marketplace\MarketplaceController::class . '::add_three');

            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Auth'));

            return $routes;
        })->setShared(true);
    }
}
