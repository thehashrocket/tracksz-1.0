<?php

declare(strict_types=1);

namespace App\Middleware;

use Delight\Auth\Auth;
use App\Library\Views;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Auth middleware protection PSR-15 middleware.
 */
final class AuthMiddleware implements MiddlewareInterface
{
    
    private $auth;
    private $view;
    
    /**
     * Constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth, Views $view)
    {
        $this->auth = $auth;
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
        if ($this->auth->isLoggedIn()) {
            return $handler->handle($request);
        }
        $data = [
            'alert'     => 'You must login to proceed. Please register if you do not have an account.',
            'alert_type' => 'warning'
        ];
        $this->view->flash($data);
        return $this->view->redirect('/login');
    }
}
