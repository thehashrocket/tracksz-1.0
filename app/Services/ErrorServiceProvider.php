<?php declare(strict_types = 1);

namespace App\Services;

use App\Library\Config;
use ErrorException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ErrorServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @type array
     */
    protected $provides = [
        Run::class,
    ];
    
    /**
     * Boot method.
     */
    public function boot()
    {
        set_error_handler(function ($level, $message, $file = '', $line = 0, $context = []) {
            if (error_reporting() & $level) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }
        });
        
       set_exception_handler(function ($exception) {
           $this->container->get(Run::class)->handleException($exception);
       });
    }
    
    /**
     * Register method.
     */
    public function register()
    {
        $this->container->add(Run::class, function () {
            $whoops = new Run();
            if (Config::get('system_status') !== 'production') {
                $whoops->pushHandler(new PrettyPageHandler());
            } else {
                $whoops->pushHandler(function($e){
                   echo $this->container->get(View::class)->render('error');
                });
            }
            return $whoops;
        })->setShared(true);
    }
}
