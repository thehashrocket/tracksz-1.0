<?php declare(strict_types = 1);

/****** YOU MUST ADD THIS TO $routeProviders in app\Application.php *****/

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class zzExampleRoutes extends AbstractServiceProvider
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
    
            // If for some reason you do not need CSRF, AUTH, and STORE Middleware
            // place routes outside the group
            //$routes->get('/example/path', Controllers\zzExampleController::class.'::patht');
            
            // You can also only use certain middleware
            //$routes->get('/example/path', Controllers\zzExampleController::class.'::patht')
            //   ->middleware($this->container->get('Auth'));
            
            // Main Example Routes
            $routes->group('/example', function (\League\Route\RouteGroup $route) {
                // path routes
                
                // If you have two routes the same, one with variables and one without
                // list the one without first.  Routes are found in order listed here.
                $route->get('/page',Controllers\zzExampleController::class.'::zzPage');
                $route->get('/page/{pg:number}/{per:number}',Controllers\zzExampleController::class.'::zzPage');
    
                $route->get('/find',Controllers\zzExampleController::class.'::zzFind');
                
                // Form Post
                $route->post('/find', Controllers\zzExampleController::class.'::findExample');
                
                // Middleware to check CSRF, check if a store is chosen,
                // and if they are logged in
            })->middleware($this->container->get('Csrf'))
                ->middleware($this->container->get('Store'))
                ->middleware($this->container->get('Auth'));
            
            return $routes;
        })->setShared(true);
    }
}
