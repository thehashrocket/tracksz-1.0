<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Controllers\Payments\StripeController;
use App\Library\Config;
use App\Library\Encryption;
use App\Library\Utils;
use App\Library\Views;
use App\Models\Account\ShippingMethod;
use Delight\Auth\Auth;
use Delight\Cookie\Cookie;
use Delight\Cookie\Session;
use Laminas\Diactoros\ServerRequest;
use PDO;

class ShippingController
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
    
    /** 
     *  shippingMethods - Manage shipping methods
     * 
     *  @return view - /account/shipping-methods
     */
    public function shippingMethods()
    {
        $methods = (new ShippingMethod($this->db))->findByMember($this->auth->getUserId());
        return $this->view->buildResponse('/account/shipping_methods', [
            'shippingMethods' => $methods
        ]);
    }

    public function 
}