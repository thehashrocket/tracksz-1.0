<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Library\Views;
use App\Models\Account\ShippingMethod;
use App\Models\Account\ShippingZone;
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
     *  viewMethods - View shipping methods
     *
     *  @return view - /account/shipping-methods
     */
    public function viewMethods()
    {
        $activeStoreId = Cookie::get('tracksz_active_store');
        $methods = (new ShippingMethod($this->db))->findByStore($activeStoreId);
        return $this->view->buildResponse('/account/shipping_method', [
            'shippingMethods' => $methods,
            'activeStoreId' => $activeStoreId
        ]);
    }

    /**
     *  viewCreateMethod - View form to create a shipping method
     *
     *  @return view - /account/shipping-methods/create
     */
    public function viewAddMethod()
    {
        return $this->view->buildResponse('/account/shipping_method_add', []);
    }

    /**
     *  viewUpdateMethod - View form to update shipping method
     *
     *  @param  $id  - ID of shipping method to update
     *  @return view - Redirect to list of methods on update
     */
    public function viewUpdateMethod(ServerRequest $request, array $data)
    {
        $method = (new ShippingMethod($this->db))->find($data['Id']);
        return $this->view->buildResponse('/account/shipping_method_add', [
            'update_id' => $data['Id'],
            'update_name' => $method['Name'],
            'update_delivery' => $method['DeliveryTime'],
            'update_fee' => $method['InitialFee'],
            'update_discount_fee' => $method['DiscountFee'],
            'update_minimum' => $method['Minimum']
        ]);
    }

    /**
     *  viewCreateZone - View form to create a shipping zone
     *
     *  @return view - /account/shipping-zones/create
     */
    public function viewAddZone()
    {
        return $this->view->buildResponse('/account/shipping_zone_add', []);
    }

    /**
     *  viewUpdateZone - View form to update shipping zone
     *
     *  @param  $id  - ID of shipping method to update
     *  @return view - Redirect to list of methods on update
     */
    public function viewUpdateZone(ServerRequest $request, array $data)
    {
        $zone = (new ShippingZone($this->db))->find($data['Id']);
        return $this->view->buildResponse('/account/shipping_zone_add', [
            'update_id' => $data['Id'],
            'update_name' => $zone['Name']
        ]);
    }

    /**
     *  viewZones - View shipping zones
     *
     *  @return view - /account/shipping-zones
     */
    public function viewZones()
    {
        $activeStoreId = Cookie::get('tracksz_active_store');
        $zones = (new ShippingZone($this->db))->findByStore($activeStoreId);
        return $this->view->buildResponse('/account/shipping_zone', [
            'shippingZones' => $zones
        ]);
    }

    /**
     *  createMethod - Add shipping method and redirect to list of methods
     *
     *  @param  ServerRequest - To grab form data
     *  @return view - Redirect based on success
     */
    public function createMethod(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        $methodData['StoreId'] = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        $newMethod = (new ShippingMethod($this->db))->create($methodData);
        if ($newMethod)
        {
            $this->view->flash([
                'alert' => 'Successfully created new shipping method.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-methods');
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to create shipping method. Please ensure all input is filled out',
                'alert_type' => 'danger'
            ]);
            return $this->view->redirect('/account/shipping-methods/create');
        }
    }

     /**
     *  createZone - Add shipping zone and redirect to list of zones
     *
     *  @param  ServerRequest - To grab form data
     *  @return view - Redirect based on success
     */
    public function createZone(ServerRequest $request)
    {
        $zoneData = $request->getParsedBody();
        $zoneData['StoreId'] = Cookie::get('tracksz_active_store');
        $newZone = (new ShippingZone($this->db))->create($zoneData);
        if ($newZone)
        {
            $this->view->flash([
                'alert' => 'Successfully created new shipping zone.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-zones');
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to add shipping zone. Please ensure name is filled out.',
                'alert_type' => 'danger'
            ]);
            return $this->view->redirect('/account/shipping-zones/create');
        }
    }

    /**
     *  updateMethod  - Update shipping method
     *
     *  @param  $request - To extract method data
     *  @return view  - Redirect based on success
     */
    public function updateMethod(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        $updated = (new ShippingMethod($this->db))->update($methodData);
        if ($updated)
        {
            $this->view->flash([
                'alert' => 'Successfully updated shipping method.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-methods');
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to update shipping method. Please ensure all input is filled out correctly.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('/account/shipping/create', [
                'update_id' => $methodData['update_id'],
                'update_name' => $methodData['Name'],
                'update_delivery' => $methodData['DeliveryTime'],
                'update_fee' => $methodData['InitialFee'],
                'update_discount_fee' => $methodData['DiscountFee'],
                'update_minimum' => $methodData['Minimum']
            ]);
        }
    }

    /**
     *  updateZone  - Update shipping zone
     *
     *  @param  $request - To extract zone name
     *  @return view  - Redirect based on success
     */
    public function updateZone(ServerRequest $request, array $data)
    {
        $zoneData = $request->getParsedBody();
        $zoneData['Id'] = $data['Id'];
        $updated = (new ShippingZone($this->db))->update($zoneData);
        if ($updated)
        {
            $this->view->flash([
                'alert' => 'Successfully updated shipping zone.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-zones');
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to update shipping zone. Please ensure all input is filled out correctly.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('/account/shipping-zones/create', [
                'update_id' => $methodData['update_id'],
                'update_name' => $methodData['Name']
            ]);
        }
    }

    /**
     *  deleteMethod - Delete shipping method via ID
     *
     *  @param  $data - Contains ID
     *  @return view  - Redirect based on success
     */
    public function deleteMethod(ServerRequest $request, array $data)
    {
        $deleted = (new ShippingMethod($this->db))->delete($data['Id']);
        if ($deleted)
        {
            $this->view->flash([
                'alert' => 'Successfully deleted shipping method.',
                'alert_type' => 'success'
            ]);
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to delete shipping method.',
                'alert_type' => 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-methods');
    }

    public function deleteZone(ServerRequest $request, array $data)
    {
        $deleted = (new ShippingZone($this->db))->delete($data['Id']);
        if ($deleted)
        {
            $this->view->flash([
                'alert' => 'Successfully deleted shipping zone.',
                'alert_type' => 'success'
            ]);
        }
        else
        {
            $this->view->flash([
                'alert' => 'Failed to delete shipping zone.',
                'alert_type' => 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones');
    }
}