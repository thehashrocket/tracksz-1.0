<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Library\Views;
use App\Models\Account\ShippingMethod;
use App\Models\Account\Store;
use App\Models\Account\Member;
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
     *  viewShippingMethods - View shipping methods
     * 
     *  @return view - /account/shipping-methods
     */
    public function viewShippingMethods()
    {
        $methods = (new ShippingMethod($this->db))->findByMember($this->auth->getUserId());
        $activeStoreId = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        return $this->view->buildResponse('/account/shipping_methods', [
            'shippingMethods' => $methods,
            'activeStoreId' => $activeStoreId
        ]);
    }

    /**
     *  viewAddShippingMethod - View form to add a shipping method
     * 
     *  @return view - /account/add-shipping-method
     */
    public function viewAddShippingMethod()
    {
        return $this->view->buildResponse('/account/add_shipping_method', []);
    }

    /**
     *  createShippingMethod - Add shipping method and redirect to list of methods
     * 
     *  
     */
    public function createShippingMethod(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        $methodData['storeId'] = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        $methodData['memberId'] = $this->auth->getUserId();
        $newMethod = (new ShippingMethod($this->db))->addShippingMethod($methodData);
        if ($newMethod)
        {
            $this->view->flash([
                'alert' => 'Successfully added shipping method "' . $methodData['name'] . '"',
                'alert_type' => 'success'
            ]);
            return $this->view->buildResponse('/account/shipping_methods', [
                'shippingMethods' => (new ShippingMethod($this->db))->findByMember($this->auth->getUserId())
            ]);
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to add shipping method. Please ensure all input is filled out',
                'alert_type' => 'danger'
            ]);
            return $this->view->redirect('/account/add-shipping-method');
        }
    }
}