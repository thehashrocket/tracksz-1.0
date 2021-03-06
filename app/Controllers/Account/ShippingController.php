<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Library\Views;
use App\Library\Paginate;
use App\Models\Country;
use App\Models\Account\ShippingMethod;
use App\Models\Account\ShippingZone;
use App\Models\Account\Store;
use App\Models\Account\Member;
use App\Models\Zone;
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
    private $countryCodes = [
        'US' => 223, 
        'CA' => 38, 
        'GB' => 222, 
        'AU' => 13
    ];
    
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
        $shippingMethodObj = new ShippingMethod($this->db);

        if ($shippingMethodObj->belongsToMember($data['Id'], $this->auth->getUserId()))
        {
            $method = $shippingMethodObj->find($data['Id']);
            return $this->view->buildResponse('/account/shipping_method_add', [
                'update_id' => $data['Id'],
                'update_name' => $method['Name'],
                'update_delivery' => $method['DeliveryTime'],
                'update_fee' => $method['InitialFee'],
                'update_discount_fee' => $method['DiscountFee'],
                'update_minimum' => $method['Minimum']
            ]);
        }
        else
        {
            return $this->view->redirect('/account/shipping-methods');
        }
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
        $shippingZoneObj = new ShippingZone($this->db);

        if ($shippingZoneObj->belongsToMember($data['ZoneId'], $this->auth->getUserId())) {
            $zone = (new ShippingZone($this->db))->find($data['ZoneId']);
            return $this->view->buildResponse('/account/shipping_zone_add', [
                'update_id' => $data['ZoneId'],
                'update_name' => $zone['Name']
            ]);
        }
        else {
            return $this->view->redirect('/account/shipping-methods');
        }
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
     *  viewManageZones - View page to assign shipping method to zone
     * 
     *  @return view - /account/shipping-zones/manage
     */
    public function viewManageZone(ServerRequest $request, array $data)
    {
        $storeId = Cookie::get('tracksz_active_store');
        $shippingMethodObj = (new ShippingMethod($this->db));
        $zone = (new ShippingZone($this->db))->find($data['ZoneId']);
        $assigned = $shippingMethodObj->getAssignedByZone($data['ZoneId']);
        $unassigned = $shippingMethodObj->getUnassignedByStore($storeId, $data['ZoneId']);

        return $this->view->buildResponse('/account/shipping_zone_manage', [
            'zone' => $zone,
            'assignedMethods' => $assigned,
            'unassignedMethods' => $unassigned
        ]);
    }

    /**
     *  viewAssignZones - View page to bulk assign shipping zones
     * 
     *  @return view - /account/shipping-assign
     */
    public function viewAssignZonesBulk()
    {
        $activeStoreId = Cookie::get('tracksz_active_store');
        $zones = (new ShippingZone($this->db))->findByStore($activeStoreId);

        return $this->view->buildResponse('/account/shipping_assign_bulk', [
            'shippingZones' => $zones
        ]);
    }

    /**
     *  viewAssignZonesIndividualCountries - View page to assign shipping zones to individual countries
     * 
     *  @return view - /account/shipping-assign/individual/countries
     */
    public function viewAssignZonesIndividualCountries()
    {
        $activeStoreId = Cookie::get('tracksz_active_store');
        $shippingZoneObj = (new ShippingZone($this->db));
        $countryZoneAssignments = $shippingZoneObj->getBulkCountryAssignmentMap($activeStoreId);
        $shippingZones = $shippingZoneObj->findByStore($activeStoreId);

        return $this->view->buildResponse('/account/shipping_assign_countries', [
            'countryZoneAssignments' => $countryZoneAssignments,
            'shippingZones' => $shippingZones
        ]);
    }

    /**
     *  viewAssignZonesIndividualStates - View page to assign shipping zones to individual states
     * 
     *  @return view - /account/shipping-assign/individual/states
     */
    public function viewAssignZonesIndividualStates(ServerRequest $request, array $data)
    {
        $countryId = $data['CountryId'];
        $activeStoreId = Cookie::get('tracksz_active_store');
        $shippingZoneObj = (new ShippingZone($this->db));
        $stateZoneAssignments = $shippingZoneObj->getStateAssignmentMap($activeStoreId, $countryId);
        $shippingZones = $shippingZoneObj->findByStore($activeStoreId);

        return $this->view->buildResponse('/account/shipping_assign_states', [
            'countryId' => $countryId,
            'stateZoneAssignments' => $stateZoneAssignments,
            'shippingZones' => $shippingZones
        ]);
    }

    public function viewAssignZonesIndividualZip(ServerRequest $request, array $data)
    {
        $activeStoreId = Cookie::get('tracksz_active_store');
        $stateId = $data['StateId'];
        $stateName = (new Zone($this->db))->getName($stateId);
        $zipRanges = include(__DIR__.'/../../../config/zip_codes.php');
        $zipRangesUS = $zipRanges['US'];
        $stateZipCodeMin = $zipRangesUS[$stateId]['min'];
        $stateZipCodeMax = $zipRangesUS[$stateId]['max'];

        $shippingZoneObj = new ShippingZone($this->db);
        $zipCodeAssignments = $shippingZoneObj->getZipCodeAssignments($stateId, $activeStoreId);
        $shippingZones = $shippingZoneObj->findByStore($activeStoreId);

        return $this->view->buildResponse('/account/shipping_assign_zip', [
            'stateId' => $stateId,
            'stateName' => $stateName,
            'stateZipCodeMin' => $stateZipCodeMin,
            'stateZipCodeMax' => $stateZipCodeMax,
            'zipCodeAssignments' => $zipCodeAssignments,
            'shippingZones' => $shippingZones
        ]);
    }

    public function createMethod(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        $methodData['StoreId'] = (new Member($this->db))->getActiveStoreId($this->auth->getUserId());
        $newMethod = (new ShippingMethod($this->db))->create($methodData);

        if ($newMethod) {
            $this->view->flash([
                'alert' => 'Successfully created new shipping method.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-methods');
        }
        else {
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

        if ($newZone) {
            $this->view->flash([
                'alert' => 'Successfully created new shipping zone.',
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/account/shipping-zones');
        }
        else {
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
        $shippingMethodObj = new ShippingMethod($this->db);
        $methodData = $request->getParsedBody();

        if ($shippingMethodObj->belongsToMember($methodData['update_id'], $this->auth->getUserId())) {
            $updated = $shippingMethodObj->update($methodData);
            if ($updated) {
                $this->view->flash([
                    'alert' => 'Successfully updated shipping method.',
                    'alert_type' => 'success'
                ]);
            }
            else {
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
        
        return $this->view->redirect('/account/shipping-methods');
    }

    /**
     *  updateZone  - Update shipping zone
     *
     *  @param  $request - To extract zone name
     *  @return view  - Redirect based on success
     */
    public function updateZone(ServerRequest $request, array $data)
    {
        $shippingZoneObj = new ShippingZone($this->db);
        $zoneData = $request->getParsedBody();

        if ($shippingZoneObj->belongsToMember($data['Id'], $this->auth->getUserId())) {
            $zoneData['Id'] = $data['Id'];
            $updated = $shippingZoneObj->update($zoneData);
            if ($updated) {
                $this->view->flash([
                    'alert' => 'Successfully updated shipping zone.',
                    'alert_type' => 'success'
                ]);
            }
            else {
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

        return $this->view->redirect('/account/shipping-zones');
    }

    /**
     *  deleteMethod - Delete shipping method via ID
     *
     *  @param  $data - Method data
     *  @return view  - Redirect based on success
     */
    public function deleteMethod(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);

        if ($shippingMethodObj->belongsToMember($data['Id'], $this->auth->getUserId())) {
            $deleted = $shippingMethodObj->delete($data['Id']);
            $this->view->flash([
                'alert' => $deleted ? 'Successfully deleted shipping method.' : 'Failed to delete shipping method.',
                'alert_type' => $deleted ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-methods');
    }

    /**
     *  deleteZone - Delete shipping zone
     * 
     *  @param $data - Zone data
     *  @return view - Redirect based on success
     */
    public function deleteZone(ServerRequest $request, array $data)
    {
        $shippingZoneObj = new ShippingZone($this->db);

        if ($shippingZoneObj->belongsToMember($data['Id'], $this->auth->getUserId())) {
            $deleted = $shippingZoneObj->delete($data['Id']);
            $this->view->flash([
                'alert' => $deleted ? 'Successfully deleted shipping zone.' : 'Failed to delete shipping zone.',
                'alert_type' => $deleted ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones');
    }

    /**
     *  assignMethod - Assign shipping method to zone
     * 
     *  @param $data - Method data
     *  @return view - Redirect based on success
     */
    public function assignMethod(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);

        if ($shippingMethodObj->belongsToMember($data['MethodId'], $this->auth->getUserId())) {
            $success = $shippingMethodObj->assign($data['MethodId'], $data['ZoneId']);
            $this->view->flash([
                'alert' => $success ? 'Successfully assigned shipping method.' : 'Failed to assign shipping method.',
                'alert_type' => $success ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones/manage/' . $data['ZoneId']);
    }

    /**
     *  unassignMethod - Unassign shipping method from zone
     * 
     *  @param $data - Method data
     *  @return view - Redirect based on success
     */
    public function unassignMethod(ServerRequest $request, array $data)
    {
        $shippingMethodObj = new ShippingMethod($this->db);

        if ($shippingMethodObj->belongsToMember($data['MethodId'], $this->auth->getUserId())) {
            $success = $shippingMethodObj->unassign($data['MethodId'], $data['ZoneId']);
            $this->view->flash([
                'alert' => $success ? 'Successfully unassigned shipping method.' : 'Failed to unassign shipping method.',
                'alert_type' => $success ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-zones/manage/' . $data['ZoneId']);
    }

    /**
     *  bulkAssign - Bulk assign shipping zone to country
     * 
     *  @return view - Redirect based on success
     */
    public function bulkAssign(ServerRequest $request)
    {
        $data = $request->getParsedBody();
        $zoneId = $data['ZoneId'];
        $countryCode = $data['Country'];
        
        if ($zoneId !== 'null') {
            if (array_key_exists($countryCode, $this->countryCodes)) {
                $countryIDs = [$this->countryCodes[$countryCode]];
            } 
            else if ($countryCode === 'US_CA') {
                $countryIDs = [$this->countryCodes['US'], $this->countryCodes['CA']];
            }
            else if ($countryCode === 'GB_AU') {
                $countryIDs = [$this->countryCodes['GB'], $this->countryCodes['AU']];
            }
            else {
                $countryIDs = array_values($this->countryCodes);
            }

            $success = (new ShippingZone($this->db))->bulkAssignToCountry($zoneId, $countryIDs);
            $this->view->flash([
                'alert' => $success ? 'Successfully assigned shipping zone to region(s).' : 'Failed to assign shipping zone to region(s).',
                'alert_type' => $success ? 'success' : 'danger'
            ]);
        }
        else {
            $success = (new ShippingZone($this->db))->deleteBulkCountryAssignment(Cookie::get('tracksz_active_store'), $this->countryCodes[$countryCode]);
            $this->view->flash([
                'alert' => $success ? 'Successfully assigned shipping zone to region(s).' : 'Failed to assign shipping zone to region(s).',
                'alert_type' => $success ? 'success' : 'danger'
            ]);
        }

        return $this->view->redirect('/account/shipping-assign/individual/countries');
    }

    /**
     *  assignZoneToState - Bulk assign shipping zone to state
     * 
     *  @return view - Redirect based on success
     */
    public function assignZoneToState(ServerRequest $request)
    {
        $data = $request->getParsedBody();
        $zoneId = $data['ZoneId'];
        $countryId = $data['CountryId'];
        $stateId = $data['StateId'];

        $success = (new ShippingZone($this->db))->bulkAssignToState($zoneId, $countryId, $stateId);
        $this->view->flash([
            'alert' => ($success ? 'Successfully assigned' : 'Failed to assign') . ' shipping zone to state.',
            'alert_type' => $success ? 'success' : 'danger'
        ]);

        return $this->view->redirect('/account/shipping-assign/individual/states/' . $countryId);
    }

    public function assignZoneToZipRange(ServerRequest $request)
    {
        $data = $request->getParsedBody();
        $stateId = $data['StateId'];
        $zoneId = $data['ZoneId'];
        $zipCodeMin = $data['ZipCodeMin'];
        $zipCodeMax = $data['ZipCodeMax'];
        $stateZipCodeMin = $data['StateZipCodeMin'];
        $stateZipCodeMax = $data['StateZipCodeMax'];

        if ($zipCodeMin < 0 || $zipCodeMax < 0 || $zipCodeMin > 99999 || $zipCodeMax > 99999 || $zipCodeMax < $zipCodeMin) {
            $this->view->flash([
                'alert' => 'Invalid zip code range.',
                'alert_type' => 'danger'
            ]);
        } 
        else if ($zipCodeMin < $stateZipCodeMin || $zipCodeMax > $stateZipCodeMax) {
            $this->view->flash([
                'alert' => 'Zip code range should be between ' . $stateZipCodeMin . ' and ' . $stateZipCodeMax . '.',
                'alert_type' => 'danger'
            ]);
        }
        else {
            $shippingZoneObj = new ShippingZone($this->db);
            $result = $shippingZoneObj->assignToZipRange($zoneId, $stateId, $zipCodeMin, $zipCodeMax);
            $zipCodeAssignments = $shippingZoneObj->getZipCodeAssignments($stateId, Cookie::get('tracksz_active_store'));
            $shippingZones = $shippingZoneObj->findByStore(Cookie::get('tracksz_active_store'));

            if (gettype($result) === 'array') {
                $this->view->flash([
                    'alert' => 'One or more entries below conflict with the submitted range.',
                    'alert_type' => 'danger'
                ]);

                return $this->view->buildResponse('/account/shipping_assign_zip', [
                    'stateId' => $stateId,
                    'stateName' => (new Zone($this->db))->getName($stateId),
                    'stateZipCodeMin' => $stateZipCodeMin,
                    'stateZipCodeMax' => $stateZipCodeMax,
                    'zipCodeAssignments' => $zipCodeAssignments,
                    'shippingZones' => $shippingZones,
                    'conflicts' => $result
                ]);
            }
            else {
                $this->view->flash([
                    'alert' => ($result ? 'Successfully assigned' : 'Failed to assign') . ' zone to zip code range.',
                    'alert_type' => $result ? 'success' : 'danger'
                ]);
            }
        }

        return $this->view->redirect('/account/shipping-assign/individual/zip/' . $stateId);
    }

    public function deleteZipCodeAssignment(ServerRequest $request, array $data)
    {
        $assignmentId = $data['AssignmentId'];
        $success = (new ShippingZone($this->db))->deleteZipCodeAssignment($assignmentId);
        $this->view->flash([
            'alert' => ($success ? 'Successfully deleted' : 'Failed to delete') . ' assignment.',
            'alert_type' => $success ? 'success' : 'danger'
        ]);

        return $this->viewAssignZonesIndividualZip($request, $data);
    }
}
