<?php

declare(strict_types=1);

namespace App\Services\Routes;

// Use to shorten controller paths in route definitions

// For functionality
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use App\Controllers\Api;

class ApiRoutes extends AbstractServiceProvider
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
            // Use for API Calls, May need authentication of some kind, Need Json results
            $routes->group('/api', function (\League\Route\RouteGroup $route) {
                $route->get('/orderprocess', Api\ScheduleBackgroundJobs::class . '::orderBackgroundProcess');
                $route->get('/ftpuploadprocess', Api\ScheduleBackgroundJobs::class . '::ftpUploadBackgroundProcess');
            });
            return $routes;
        })->setShared(true);
    }
}
