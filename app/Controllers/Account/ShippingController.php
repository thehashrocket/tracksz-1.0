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
        $activeStoreId = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
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
    public function viewCreateMethod()
    {
        return $this->view->buildResponse('/account/shipping_method_create', []);
    }

    /**
     *  viewUpdateMethod - View form to update shipping method
     * 
     *  @param  $id  - ID of shipping method to update
     *  @return view - Redirect to list of methods on update
     */
    public function viewUpdateMethod(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);
        if ($shippingMethodObj->getStoreId($data['Id']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $method = $shippingMethodObj->find($data['Id']);

            return $this->view->buildResponse('/account/shipping_method_create', [
                'updateId' => $data['Id'],
                'updateName' => $method['Name'],
                'updateDelivery' => $method['DeliveryTime'],
                'updateFee' => $method['InitialFee'],
                'updateDiscountFee' => $method['DiscountFee'],
                'updateMinimum' => $method['Minimum']
            ]);
        }
        else
        {
            return $this->viewMethods();
        }
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
        $this->view->flash([
            'alert' => $newMethod ? 'Successfully created new shipping method.' : 'Failed to create shipping method. Please ensure all input is filled out',
            'alert_type' => $newMethod ? 'success' : 'danger'
        ]);

        return $this->view->redirect($newMethod ? '/account/shipping-methods' : '/account/shipping-methods/create');
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
        $shippingMethodObj = new ShippingMethod($this->db);
        if ($shippingMethodObj->getStoreId($methodData['updateId']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $updated = $shippingMethodObj->update($methodData);
            $this->view->flash([
                'alert' => $updated ? 'Successfully updated shipping method.' : 'Failed to update shipping method. Please ensure all input is filled out correctly.',
                'alert_type' => $updated ? 'success' : 'danger'
            ]);
    
            if ($updated)
            {
                return $this->view->redirect('/account/shipping-methods');
            }
            else
            {
                return $this->view->buildResponse('/account/shipping/create', [
                    'updateId' => $methodData['UpdateId'],
                    'updateName' => $methodData['Name'],
                    'updateDelivery' => $methodData['DeliveryTime'],
                    'updateFee' => $methodData['InitialFee'],
                    'updateDiscountFee' => $methodData['DiscountFee'],
                    'updateMinimum' => $methodData['Minimum']
                ]);
            }
        }
        else
        {
            return $this->viewMethods();
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
        $shippingMethodObj = new ShippingMethod($this->db);
        
        if ($shippingMethodObj->getStoreId($data['Id']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $deleted = $shippingMethodObj->delete($data['Id']);
            $this->view->flash([
                'alert' => $deleted ? 'Successfully deleted shipping method.' : 'Failed to delete shipping method.',
                'alert_type' => $deleted ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-methods');
    }

    /** 
     *  viewZones - View shipping zones
     * 
     *  @return view - /account/shipping-zones
     */
    public function viewZones()
    {
        $activeStoreId = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        $zones = (new ShippingZone($this->db))->findByStore($activeStoreId);

        return $this->view->buildResponse('/account/shipping_zone', [
            'shippingZones' => $zones
        ]);
    }

    /**
     *  viewManageZone - View page to manage shipping methods for a zone
     * 
     *  @return view - /account/shipping-zones/manage
     */
    public function viewManageZone(ServerRequest $request, array $data)
    {
        $shippingZoneObj = (new ShippingZone($this->db));
        $shippingMethodObj = (new ShippingMethod($this->db));
        $memberObj = (new Member($this->db));
        if ($shippingZoneObj->getStoreId($data['Id']) == $memberObj->getActiveStoreId($this->auth->getUserId()))
        {
            $activeStoreId = $memberObj->getActiveStoreId($this->auth->getUserId());
            $unassignedMethods = $shippingMethodObj->getUnassignedByStore($activeStoreId);
            $assignedMethods = $shippingMethodObj->getAssignedByStore($activeStoreId);
            $zone = $shippingZoneObj->find($data['Id']);
    
            return $this->view->buildResponse('/account/shipping_zone_manage', [
                'zone' => $zone,
                'unassignedMethods' => $unassignedMethods,
                'assignedMethods' => $assignedMethods
            ]);
        }
        else
        {
            return $this->viewZones();
        }
    }

    /**
     *  viewCreateZone - View form to create a shipping zone
     * 
     *  @return view - /account/shipping-zones/create
     */
    public function viewCreateZone()
    {
        return $this->view->buildResponse('/account/shipping_zone_create', []);
    }

    /**
     *  viewUpdateZone - View form to update shipping zone
     * 
     *  @param  $id  - ID of shipping method to update
     *  @return view - Redirect to list of methods on update
     */
    public function viewUpdateZone(ServerRequest $request, array $data)
    {
        $shippingZoneObj = new ShippingZone($this->db);
        if ($shippingZoneObj->getStoreId($data['Id']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $zone = $shippingZoneObj->find($data['Id']);

            return $this->view->buildResponse('/account/shipping_zone_create', [
                'updateId' => $data['Id'],
                'updateName' => $zone['Name']
            ]);
        }
        
    }

    /**
     *  viewZoneRegions - View page to assign zones to regions
     * 
     *  @return view
     */
    public function viewZoneRegions()
    {
        $zones = (new ShippingZone($this->db))->findByStore((new Member($this->db))->getActiveStoreId($this->auth->getUserId()));

        return $this->view->buildResponse('/account/shipping_zone_assign', [
            'zones' => $zones
        ]);
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
        $zoneData['StoreId'] = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        $newZone = (new ShippingZone($this->db))->create($zoneData);
        $this->view->flash([
            'alert' => $newZone ? 'Successfully created new shipping zone.' : 'Failed to add shipping zone. Please ensure name is filled out.',
            'alert_type' => $newZone ? 'success' : 'danger'
        ]);

        return $this->view->redirect($newZone ? '/account/shipping-zones' : '/account/shipping-zones/create');
    }

    /**
     *  updateZone  - Update shipping zone
     * 
     *  @param  $request - To extract zone name
     *  @return view  - Redirect based on success
     */
    public function updateZone(ServerRequest $request)
    {
        $zoneData = $request->getParsedBody();
        $shippingZoneObj = new ShippingZone($this->db);
        if ($shippingZoneObj->getStoreId($zoneData['updateId']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $updated = (new ShippingZone($this->db))->update($zoneData);
            $this->view->flash([
                'alert' => $updated ? 'Successfully updated shipping zone.' : 'Failed to update shipping zone. Please ensure all input is filled out correctly.',
                'alert_type' => $updated ? 'success' : 'danger'
            ]);
    
            if ($updated)
            {
                return $this->view->redirect('/account/shipping-zones');
            }
            else
            {
                return $this->view->buildResponse('/account/shipping-zones/create', [
                    'updateId' => $zoneData['updateId'],
                    'updateName' => $zoneData['Name']
                ]);
            }
        }
        else
        {
            return $this->viewZones();
        }
    }

     /**
     *  deleteZone - Delete shipping zone via ID
     * 
     *  @param  $data - Contains ID
     *  @return view  - Redirect with success/fail message
     */
    public function deleteZone(ServerRequest $request, array $data)
    {
        $shippingZoneObj = new ShippingZone($this->db);
        if ($shippingZoneObj->getStoreId($data['Id']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $deleted = $shippingZoneObj->delete($data['Id']);
            $this->view->flash([
                'alert' => $deleted ? 'Successfully deleted shipping zone.' : 'Failed to delete shipping zone.',
                'alert_type' => $deleted ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones');
    }

    /**
     *  assignMethodToZone - Assign shipping method to zone
     *  @param $data - Contains method and zone IDs
     *  @return view - Redirect with success/fail message
     */
    public function assignMethodToZone(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);
        if ($shippingMethodObj->getStoreId($data['MethodId']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $assigned = $shippingMethodObj->assign($data['MethodId'], $data['ZoneId']);
            $this->view->flash([
                'alert' => $assigned ? 'Successfully assigned shipping method.' : 'Failed to assign shipping method.',
                'alert_type' => $assigned ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones/manage/'.$data['ZoneId']);
    }

    /**
     *  unassignMethodFromZone - Unassign shipping method from zone
     *  @param $data - Contains method and zone IDs
     *  @return view - Redirect with success/fail message
     */
    public function unassignMethodFromZone(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);
        if ($shippingMethodObj->getStoreId($data['MethodId']) == (new Member($this->db))->getActiveStoreId($this->auth->getUserId()))
        {
            $assigned = $shippingMethodObj->unassign($data['MethodId'], $data['ZoneId']);
            $this->view->flash([
                'alert' => $assigned ? 'Successfully unassigned shipping method.' : 'Failed to unassign shipping method.',
                'alert_type' => $assigned ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones/manage/'.$data['ZoneId']);
    }
}