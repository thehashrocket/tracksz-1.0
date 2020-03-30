<?php declare(strict_types = 1);

namespace App;

use App\Library\Utils;
use App\Library\Views;
use App\Middleware;
use Delight\Auth\Auth;
use Delight\Cookie\Session;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Dispatcher;
use League\Route\Router;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use PDO;

class Application
{
    /**
     * @type ContainerInterface
     */
    private $container;
    
    /**
     * @type string[]
     */
    protected $serviceProviders = [
        // add services required by other services first
        //Services\SphinxServiceProvider::class,
        Services\PDOServiceProvider::class,
        
        // rest of services
        Services\AuthServiceProvider::class,
        Services\ErrorServiceProvider::class,
        Services\ViewServiceProvider::class,
    ];
    
    // separate routes by task/location
    // determined by URI /keyherelowercase/what/ever/else
    // becomes Keyherelowercase (ie customer = Customer)
    protected $routeProviders = [
        'Default'   =>  Services\Routes\DefaultRoutes::class,
        'Account'   =>  Services\Routes\AccountRoutes::class,
        'Inventory' =>  Services\Routes\InventoryRoutes::class,
        'Ajax'      =>  Services\Routes\AjaxRoutes::class,
        'Api'       =>  Services\Routes\ApiRoutes::class,
        'Inventory' =>  Services\Routes\InventoryRoutes::class,
        'Product' =>  Services\Routes\ProductRoutes::class,
        'Category' =>  Services\Routes\CategoryRoutes::class,   
        'Marketplace' =>  Services\Routes\MarketplaceRoutes::class,        
        
        // example Rooutes file
        'Example'   =>  Services\Routes\zzExampleRoutes::class,
    ];
    
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->setupProviders();
    
        // check to make sure session is updated after Delight/Auth service provider
        // is constructed. If user is logged in and avatar not Saved then
        // login is NOT synced - sync it
        $auth = $this->container->get(Auth::class);
        if ($auth->isLoggedIn() && !Session::has('member_avatar')) {
            $utils = new Utils();
            $utils->syncLoginSession(
                $this->container->get(PDO::class),
                $auth->getUserId()
            );
        }
    }
    
    /**
     * Run the application.
     */
    public function run()
    {
        /** @type Dispatcher $dispatcher */
        /* @type Request $request */
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
        
        // Add Routes file based on Location
        Session::set('current_page', $request->getUri()->getPath());
        $path = explode('/', trim($request->getUri()->getPath(), '/'));
        $route_service = ucfirst($path[0]);
        if (!array_key_exists($route_service, $this->routeProviders)) {
            $route_service = 'Default';
        }
        $routeProvider = new $this->routeProviders[$route_service]();
        $routeProvider->setContainer($this->container);
        $this->container->addServiceProvider($routeProvider);
        
        (new SapiEmitter)->emit($this->container->get(Router::class)->dispatch($request));
    }
    
    /**
     * Register the providers with the container.
     */
    private function setupProviders()
    {
        foreach ($this->serviceProviders as &$serviceProvider) {
            /** @type ServiceProvider $serviceProvider */
            $serviceProvider = new $serviceProvider();
            $serviceProvider->setContainer($this->container);
        }
        
        // Register the service providers.
        array_walk($this->serviceProviders, function (AbstractServiceProvider $serviceProvider) {
            $this->container->addServiceProvider($serviceProvider);
        });
    
        // add some middleware
        $this->container->add('Csrf',  Middleware\CsrfMiddleware::class)
            ->addArguments([new StreamFactory(), session_id()])
            ->setShared(true);
    
        $this->container->add('Auth',  Middleware\AuthMiddleware::class)
            ->addArguments([$this->container->get(Auth::class), $this->container->get(Views::class)])
            ->setShared(true);
    
        $this->container->add('Store',  Middleware\StoreMiddleware::class)
            ->addArguments([$this->container->get(Views::class)])
            ->setShared(true);
    }
}
