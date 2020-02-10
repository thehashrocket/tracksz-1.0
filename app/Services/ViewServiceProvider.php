<?php declare(strict_types = 1);

namespace App\Services;

use League\Container\ServiceProvider\AbstractServiceProvider;
use App\Library\Views;

class ViewServiceProvider extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        View::class,
    ];
    
    /**
     * Register method.
     */
    public function register()
    {
        $this->container->add(View::class, function () {
            return new Views();
        })->setShared(true);
    }
}