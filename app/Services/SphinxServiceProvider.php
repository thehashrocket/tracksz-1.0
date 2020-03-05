<?php declare(strict_types = 1);

namespace App\Services;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Foolz\SphinxPdo;

class SphinxServiceProvider extends AbstractServiceProvider
{
    /**
     * @type array
     */
    protected $provides = [
        Sphinx::class,
    ];
    
    /**
     * Register method.
     */
    public function register()
    {
        $this->container->add(Sphinx::class, function () {
            return new SphinxPdo();
        })->setShared(true);
    }
}