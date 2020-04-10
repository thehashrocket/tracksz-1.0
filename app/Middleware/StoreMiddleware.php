<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Library\Views;
use Delight\Cookie\Cookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Auth middleware protection PSR-15 middleware.
 */
final class StoreMiddleware implements MiddlewareInterface
{
    
    private $view;
    
    /**
     * Constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Views $view)
    {
        $this->view = $view;
    }
    
    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (Cookie::exists('tracksz_active_store') && Cookie::get('tracksz_active_store') > 0) {
            return $handler->handle($request);
        }
        $data = [
            'alert'     => _('You must have an Active Store Selected to work in this Area.'),
            'alert_type' => 'warning'
        ];
        $this->view->flash($data);
        return $this->view->redirect('/account/stores');
    }
}
