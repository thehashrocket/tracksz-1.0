<?php declare(strict_types = 1);

namespace App\Library;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;

/*
 * Views is used between Controllers and Plates Template engine, primarily because
 * Application.php requires a ResponseInteface return.  This provides one place to
 * create and return the ResponseInterface rather than doing it in all controllers.
 *
 * Additional functions have been added to access Plate Template functions.
 */

class Views
{
    
    private $view;
    
    /**
     * construct class
     */
    public function __construct()
    {
        $this->view = new Engine(__DIR__ . '/../../resources/views/default');
    }
    
    /**
     * Build Response - all responses to Application.php must be ResponseInterface.
     *                  This allows all classes to output the correct response object
     *
     * @param  string $template Name of item
     * @return HTML Response
     */
    public function buildResponse($template, $data = []) : ResponseInterface
    {
        $data = $this->retrieveFlash($template, $data);
        return new HtmlResponse($this->view->render($template, $data));
    }
    
    /**
     * Method to make a template before render - use for email body a lot
     *
     * @param  string $template Name of item
     * @return created template
     */
    public function make($template)
    {
        return $this->view->make($template);
    }
    
    // to render error page
    public function render($template)
    {
        return $this->view->render($template);
    }
    
    /**
     * Method to redirect to a new path
     *
     * @param  route - route for redirect
     * @return new page
     */
    public function redirect($route)
    {
        return new RedirectResponse($route);
    }
    
    /**
     * flash - saves data in _SESSION for next view->render
     *         primarily used when doing a view->redirect
     *
     * @param  data - array of the next views variable names -> value
     * @return boolean
     */
    public function flash($data)
    {
        $_SESSION['flash_data'] = $data;
        return true;
    }
    
    /**
     * retrieveFlash - returns the flash message array and addData
     *                 template/route being rendered
     *
     * @param $template - view to add data too.
     * @return boolean
     */
    public function retrieveFlash($template, $data)
    {
        if (!isset($_SESSION['flash_data'])) {
            return $data;
        }
        $flashed = $_SESSION['flash_data'];
        unset($_SESSION['flash_data']);
        return array_merge($data, $flashed);
    }
    
}
