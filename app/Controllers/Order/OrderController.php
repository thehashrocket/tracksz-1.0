<?php declare(strict_types = 1);

namespace App\Controllers\Order;

use App\Library\Views;
use Delight\Auth\Auth;
use PDO;

class OrderController
{
    private $view;
    private $auth;
    private $db;
    
    /**
     * _construct - create object
     *
     * @param  $view PHP Plates template object
     * @param  $auth Delight Auth authorization object
     * @param  $db PDO object from service provider
     * @return no return
     */
    public function __construct(Views $view, Auth $auth, PDO $db)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->db   = $db;
    }
    
    public function browse()
    {
        return $this->view->buildResponse('order/browse', []);
    }
}