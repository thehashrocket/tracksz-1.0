<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Controllers;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;

class DefaultRoutes extends AbstractServiceProvider
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
            
            $routes->group('/', function (\League\Route\RouteGroup $route) {
                $route->get('/', Controllers\HomeController::class . '::index');
                $route->post('/interested', Controllers\HomeController::class . '::interested');

                $route->get('/fuck', Controllers\Account\ShippingController::class.'::fuck');
                
                // these are not done as much keep at end
                $route->get('/register', Controllers\AuthController::class . '::showRegister');
                $route->get('/login', Controllers\AuthController::class . '::showLogin');
                $route->get('/username', Controllers\AuthController::class . '::username');
                $route->get('/password', Controllers\AuthController::class . '::password');
                $route->get('/reset', Controllers\AuthController::class.'::showReset');
    
                $route->post('/register', Controllers\AuthController::class . '::register');
                $route->post('/login', Controllers\AuthController::class . '::login');
                $route->post('/username', Controllers\AuthController::class . '::sendUsername');
                $route->post('/password', Controllers\AuthController::class . '::sendReset');
                $route->post('/reset', Controllers\AuthController::class . '::doReset');
                
                // have to put these last as they are wild card and are accepted by any of the
            })->middleware($this->container->get('Csrf'));
            $routes->get('/logout', Controllers\AuthController::class.'::logout');
            
            // needed for registration and password email verifications, no csrf - password reset has tokens
            $routes->get('/verify-email/{selector}/{token}', Controllers\AuthController::class.'::verifyRegistration');
            $routes->get('/reset_password/{selector}/{token}', Controllers\AuthController::class.'::confirmReset');
            // Need for change validation email to change users email address, no CSRF
            $routes->get('/change-email/{selector}/{token}', Controllers\AuthController::class.'::verifyChange');
            

            return $routes;
        })->setShared(true);
    }
}
