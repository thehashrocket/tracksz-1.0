<?php declare(strict_types = 1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions
use App\Models;

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequest;
use Delight\Auth\Auth;
use PDO;

class AjaxRoutes extends AbstractServiceProvider
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
            $routes->group('/ajax', function (\League\Route\RouteGroup $route) {
                // get Json of States/Regions
                $route->get('/zones/{CountryId:number}', function (ServerRequest $request, array $args) : array {
                    $zone = new Models\Zone($this->container->get(PDO::class));
                    return $zone->find($args['CountryId']);
                });
    
                // change member avatar
                $route->post('/member/avatar', function (ServerRequest $request, array $args) : array {
                    $member = new Models\Account\Member($this->container->get(PDO::class));
                    $auth = $this->container->get(Auth::class);
                    return $member->updateAvatar($auth->getUserId());
                });
                
            })->middleware($this->container->get('Auth'))
              ->setStrategy(new JsonStrategy(new ResponseFactory()));
            
            return $routes;
        })->setShared(true);
    }
}
