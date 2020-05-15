<?php

declare(strict_types=1);

namespace App\Controllers\Order;

use App\Library\Views;
use Delight\Auth\Auth;
use App\Models\Inventory\OrderSetting;
use App\Models\Order\Order;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use Exception;
use PDO;
use App\Library\Config;
use Illuminate\Http\Request;
use Delight\Cookie\Session;
use App\Models\Marketplace\Marketplace;


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
        $all_order = (new Order($this->db))->getAllBelongsTo();
        return $this->view->buildResponse('order/browse', ['all_order' => $all_order]);
    }


    /*
    * view - Load loadBatchMove view file
    * @param  - none
    * @return view
    */
    public function loadBatchMove()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadConfirmationFile view file
    * @param  - none
    * @return view
    */
    public function loadConfirmationFile()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadExportOrder view file
    * @param  - none
    * @return view
    */
    public function loadExportOrder()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadShippingOrder view file
    * @param  - none
    * @return view
    */
    public function loadShippingOrder()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadOrderSetting view file
    * @param  - none
    * @return view
    */
    public function loadOrderSetting()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadPostageSetting view file
    * @param  - none
    * @return view
    */
    public function loadPostageSetting()
    {
        return $this->view->buildResponse('order/defaults', []);
    }

    /*
    * view - Load loadLabelSetting view file
    * @param  - none
    * @return view
    */
    public function loadLabelSetting()
    {
        return $this->view->buildResponse('order/defaults', []);
    }
public function orderinsertOrUpdate($data)
    {
        $order_setting = (new OrderSetting($this->db))->OrderSettingfindByUserId(Session::get('auth_user_id'));
        if (isset($order_setting) && !empty($order_setting)) { // update
            $data['Updated'] = date('Y-m-d H:i:s');
            $result = (new OrderSetting($this->db))->editOrderSettings($data);
        } else { // insert
            $data['Created'] = date('Y-m-d H:i:s');
            $result = (new OrderSetting($this->db))->addInventorySettings($data);
        }

        return $result;
    }



    /*
    * Order - Order setting
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function orderSettingsBrowse()
    {

        $order_details = (new OrderSetting($this->db))->OrderSettingfindByUserId(Session::get('auth_user_id'));
        return $this->view->buildResponse('inventory/settings/order', ['all_settings' => $order_details]);
    }


    /*
    * OrderupdateSettings - Update Order Settings
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function orderUpdateSettings(ServerRequest $request)
    {

        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data['UserId'] = Session::get('auth_user_id');
            $update_data['ConfirmEmail'] = $methodData['ConfirmEmail'];
            $update_data['CancelEmail'] = $methodData['CancelEmail'];
            $update_data['DeferEmail'] = $methodData['DeferEmail'];

            $is_data = $this->orderinsertOrUpdate($update_data);

            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Order settings updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $user_details = (new OrderSetting($this->db))->OrderSettingfindByUserId(Session::get('auth_user_id'));
                return $this->view->buildResponse('inventory/settings/order', ['all_settings' => $user_details]);
            } else {
                throw new Exception("Failed to update Settings. Please ensure all input is filled out correctly.", 301);
            }
        } catch (Exception $e) {

            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = $e->getMessage();
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            $user_details = (new OrderSetting($this->db))->OrderSettingfindByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('inventory/settings/order', ['all_settings' => $user_details]);
        }
    }


     /*
    * view - Load addLoadView view file
    * @param  - none
    * @return view
    */
    public function addLoadView()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('order/add', ['market_places' => $market_places]);
    }


    /*
    * addOrder - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addOrder(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'MarketPlaceOrder'    => 'required',
                'PaymentMethod'       => 'required',
                'BuyerNote'       => 'required',
                'SellerNote'       => 'required',
                'ShippingMethod'       => 'required',
                'Tracking'       => 'required',
                'ShippingName'       => 'required',
                'ShippingPhone'       => 'required',
                'ShippingEmail'       => 'required',
                'ShippingAddress1'       => 'required',
                'ShippingAddress2'       => 'required',
                'ShippingAddress3'       => 'required',
                'ShippingCity'       => 'required',
                'ShippingState'       => 'required',
                'ShippingZipCode'       => 'required',
                'ShippingCountry'       => 'required',
                'BillingName'       => 'required',
                'BillingPhone'       => 'required',
                'BillingEmail'       => 'required',
                'BillingAddress1'       => 'required',
                'BillingAddress2'       => 'required',
                'BillingAddress3'       => 'required',
                'BillingCity'       => 'required',
                'BillingState'       => 'required',
                'BillingZipCode'       => 'required',
                'BillingCountry'       => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            $insert_data = $this->PrepareInsertData($form);
            $order_obj = new Order($this->db);
            $all_order = $order_obj->addOrder($insert_data);

            if (isset($all_order) && !empty($all_order)) {
                $this->view->flash([
                    'alert' => _('Order added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/order/browse');
            } else {
                throw new Exception("Sorry we encountered an issue.  Please try again.", 301);
            }
        } catch (Exception $e) {

            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = $e->getMessage();
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
            return $this->view->buildResponse('order/add', ['market_places' => $market_places, 'form' => $form]);
        }
    }

    /*
    * PrepareInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareInsertData($form = array())
    {
        $form_data = array();
        $form_data['MarketPlaceId'] = (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : null;
        $form_data['OrderId'] = (isset($form['MarketPlaceOrder']) && !empty($form['MarketPlaceOrder'])) ? $form['MarketPlaceOrder'] : null;
        $form_data['Status'] = (isset($form['OrderStatus']) && !empty($form['OrderStatus'])) ? $form['OrderStatus'] : null;
        $form_data['Currency'] = (isset($form['Currency']) && !empty($form['Currency'])) ? $form['Currency'] : 0;
        $form_data['PaymentStatus'] = (isset($form['PaymentStatus']) && !empty($form['PaymentStatus'])) ? $form['PaymentStatus'] : null;
        $form_data['PaymentMethod'] = (isset($form['PaymentMethod']) && !empty($form['PaymentMethod'])) ? $form['PaymentMethod'] : null;
        $form_data['BuyerNote'] = (isset($form['BuyerNote']) && !empty($form['BuyerNote'])) ? $form['BuyerNote'] : null;
        $form_data['SellerNote'] = (isset($form['SellerNote']) && !empty($form['SellerNote'])) ? $form['SellerNote'] : null;
        $form_data['ShippingMethod'] = (isset($form['ShippingMethod']) && !empty($form['ShippingMethod'])) ? $form['ShippingMethod'] : null;
        $form_data['Tracking'] = (isset($form['Tracking']) && !empty($form['Tracking'])) ? $form['Tracking'] : null;
        $form_data['Carrier'] = (isset($form['CarrierOrder']) && !empty($form['CarrierOrder'])) ? $form['CarrierOrder'] : null;
        // Shipping
        $form_data['ShippingName'] = (isset($form['ShippingName']) && !empty($form['ShippingName'])) ? $form['ShippingName'] : null;
        $form_data['ShippingPhone'] = (isset($form['ShippingPhone']) && !empty($form['ShippingPhone'])) ? $form['ShippingPhone'] : null;
        $form_data['ShippingEmail'] = (isset($form['ShippingEmail']) && !empty($form['ShippingEmail'])) ? $form['ShippingEmail'] : null;
        $form_data['ShippingAddress1'] = (isset($form['ShippingAddress1']) && !empty($form['ShippingAddress1'])) ? $form['ShippingAddress1'] : null;
        $form_data['ShippingAddress2'] = (isset($form['ShippingAddress2']) && !empty($form['ShippingAddress2'])) ? $form['ShippingAddress2'] : null;
        $form_data['ShippingAddress3'] = (isset($form['ShippingAddress3']) && !empty($form['ShippingAddress3'])) ? $form['ShippingAddress3'] : null;
        $form_data['ShippingCity'] = (isset($form['ShippingCity']) && !empty($form['ShippingCity'])) ? $form['ShippingCity'] : null;
        $form_data['ShippingState'] = (isset($form['ShippingState']) && !empty($form['ShippingState'])) ? $form['ShippingState'] : null;
        $form_data['ShippingZipCode'] = (isset($form['ShippingZipCode']) && !empty($form['ShippingZipCode'])) ? $form['ShippingZipCode'] : null;
        $form_data['ShippingCountry'] = (isset($form['ShippingCountry']) && !empty($form['ShippingCountry'])) ? $form['ShippingCountry'] : null;
        // Billing
        $form_data['BillingName'] = (isset($form['BillingName']) && !empty($form['BillingName'])) ? $form['BillingName'] : null;
        $form_data['BillingPhone'] = (isset($form['BillingPhone']) && !empty($form['BillingPhone'])) ? $form['BillingPhone'] : null;
        $form_data['BillingEmail'] = (isset($form['BillingEmail']) && !empty($form['BillingEmail'])) ? $form['BillingEmail'] : null;
        $form_data['BillingAddress1'] = (isset($form['BillingAddress1']) && !empty($form['BillingAddress1'])) ? $form['BillingAddress1'] : null;
        $form_data['BillingAddress2'] = (isset($form['BillingAddress2']) && !empty($form['BillingAddress2'])) ? $form['BillingAddress2'] : null;
        $form_data['BillingAddress3'] = (isset($form['BillingAddress3']) && !empty($form['BillingAddress3'])) ? $form['BillingAddress3'] : null;
        $form_data['BillingCity'] = (isset($form['BillingCity']) && !empty($form['BillingCity'])) ? $form['BillingCity'] : null;
        $form_data['BillingState'] = (isset($form['BillingState']) && !empty($form['BillingState'])) ? $form['BillingState'] : null;
        $form_data['BillingZipCode'] = (isset($form['BillingZipCode']) && !empty($form['BillingZipCode'])) ? $form['BillingZipCode'] : null;
        $form_data['BillingCountry'] = (isset($form['BillingCountry']) && !empty($form['BillingCountry'])) ? $form['BillingCountry'] : null;

        $form_data['Created'] = date('Y-m-d H:I:S');

        return $form_data;
    }

    /*
    * deleteOrderData - Delete Category Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteOrderData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Order($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Order record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Order records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';
            echo json_encode($res);
            exit;
        }
    }

    /*
    * view - Load editOrder view file
    * @param  - none
    * @return view
    */
    public function editOrder(ServerRequest $request, $Id = [])
    {
        $form = (new Order($this->db))->findById($Id['Id']);
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $form]);
        } else {
            $this->browse->flash([
                'alert' => 'Failed to fetch Order details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $form]);
        }
    }

    /*
    * updateOrder - Update Category data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateOrder(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($methodData); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'MarketPlaceOrder'    => 'required',
                'PaymentMethod'       => 'required',
                'BuyerNote'       => 'required',
                'SellerNote'       => 'required',
                'ShippingMethod'       => 'required',
                'Tracking'       => 'required',
                'ShippingName'       => 'required',
                'ShippingPhone'       => 'required',
                'ShippingEmail'       => 'required',
                'ShippingAddress1'       => 'required',
                'ShippingAddress2'       => 'required',
                'ShippingAddress3'       => 'required',
                'ShippingCity'       => 'required',
                'ShippingState'       => 'required',
                'ShippingZipCode'       => 'required',
                'ShippingCountry'       => 'required',
                'BillingName'       => 'required',
                'BillingPhone'       => 'required',
                'BillingEmail'       => 'required',
                'BillingAddress1'       => 'required',
                'BillingAddress2'       => 'required',
                'BillingAddress3'       => 'required',
                'BillingCity'       => 'required',
                'BillingState'       => 'required',
                'BillingZipCode'       => 'required',
                'BillingCountry'       => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            $update_data = $this->PrepareUpdateData($methodData);
            $Id = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : null;
            $is_updated = (new Order($this->db))->editOrder($Id, $update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Order record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $all_order = (new Order($this->db))->getAllBelongsTo();
                return $this->view->buildResponse('order/browse', ['all_order' => $all_order]);
            } else {
                throw new Exception("Failed to update Order. Please ensure all input is filled out correctly.", 301);
            }
        } catch (Exception $e) {

            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = $e->getMessage();
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->browse->flash($validated);
            $form = (new Order($this->db))->findById($Id['Id']);
            $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $methodData]);
        }
    }

    /*
    * PrepareUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareUpdateData($form = array())
    {
        $form_data = array();
        $form_data['MarketPlaceId'] = (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : null;
        $form_data['OrderId'] = (isset($form['MarketPlaceOrder']) && !empty($form['MarketPlaceOrder'])) ? $form['MarketPlaceOrder'] : null;
        $form_data['Status'] = (isset($form['OrderStatus']) && !empty($form['OrderStatus'])) ? $form['OrderStatus'] : null;
        $form_data['Currency'] = (isset($form['Currency']) && !empty($form['Currency'])) ? $form['Currency'] : 0;
        $form_data['PaymentStatus'] = (isset($form['PaymentStatus']) && !empty($form['PaymentStatus'])) ? $form['PaymentStatus'] : null;
        $form_data['PaymentMethod'] = (isset($form['PaymentMethod']) && !empty($form['PaymentMethod'])) ? $form['PaymentMethod'] : null;
        $form_data['BuyerNote'] = (isset($form['BuyerNote']) && !empty($form['BuyerNote'])) ? $form['BuyerNote'] : null;
        $form_data['SellerNote'] = (isset($form['SellerNote']) && !empty($form['SellerNote'])) ? $form['SellerNote'] : null;
        $form_data['ShippingMethod'] = (isset($form['ShippingMethod']) && !empty($form['ShippingMethod'])) ? $form['ShippingMethod'] : null;
        $form_data['Tracking'] = (isset($form['Tracking']) && !empty($form['Tracking'])) ? $form['Tracking'] : null;
        $form_data['Carrier'] = (isset($form['CarrierOrder']) && !empty($form['CarrierOrder'])) ? $form['CarrierOrder'] : null;
        // Shipping
        $form_data['ShippingName'] = (isset($form['ShippingName']) && !empty($form['ShippingName'])) ? $form['ShippingName'] : null;
        $form_data['ShippingPhone'] = (isset($form['ShippingPhone']) && !empty($form['ShippingPhone'])) ? $form['ShippingPhone'] : null;
        $form_data['ShippingEmail'] = (isset($form['ShippingEmail']) && !empty($form['ShippingEmail'])) ? $form['ShippingEmail'] : null;
        $form_data['ShippingAddress1'] = (isset($form['ShippingAddress1']) && !empty($form['ShippingAddress1'])) ? $form['ShippingAddress1'] : null;
        $form_data['ShippingAddress2'] = (isset($form['ShippingAddress2']) && !empty($form['ShippingAddress2'])) ? $form['ShippingAddress2'] : null;
        $form_data['ShippingAddress3'] = (isset($form['ShippingAddress3']) && !empty($form['ShippingAddress3'])) ? $form['ShippingAddress3'] : null;
        $form_data['ShippingCity'] = (isset($form['ShippingCity']) && !empty($form['ShippingCity'])) ? $form['ShippingCity'] : null;
        $form_data['ShippingState'] = (isset($form['ShippingState']) && !empty($form['ShippingState'])) ? $form['ShippingState'] : null;
        $form_data['ShippingZipCode'] = (isset($form['ShippingZipCode']) && !empty($form['ShippingZipCode'])) ? $form['ShippingZipCode'] : null;
        $form_data['ShippingCountry'] = (isset($form['ShippingCountry']) && !empty($form['ShippingCountry'])) ? $form['ShippingCountry'] : null;
        // Billing
        $form_data['BillingName'] = (isset($form['BillingName']) && !empty($form['BillingName'])) ? $form['BillingName'] : null;
        $form_data['BillingPhone'] = (isset($form['BillingPhone']) && !empty($form['BillingPhone'])) ? $form['BillingPhone'] : null;
        $form_data['BillingEmail'] = (isset($form['BillingEmail']) && !empty($form['BillingEmail'])) ? $form['BillingEmail'] : null;
        $form_data['BillingAddress1'] = (isset($form['BillingAddress1']) && !empty($form['BillingAddress1'])) ? $form['BillingAddress1'] : null;
        $form_data['BillingAddress2'] = (isset($form['BillingAddress2']) && !empty($form['BillingAddress2'])) ? $form['BillingAddress2'] : null;
        $form_data['BillingAddress3'] = (isset($form['BillingAddress3']) && !empty($form['BillingAddress3'])) ? $form['BillingAddress3'] : null;
        $form_data['BillingCity'] = (isset($form['BillingCity']) && !empty($form['BillingCity'])) ? $form['BillingCity'] : null;
        $form_data['BillingState'] = (isset($form['BillingState']) && !empty($form['BillingState'])) ? $form['BillingState'] : null;
        $form_data['BillingZipCode'] = (isset($form['BillingZipCode']) && !empty($form['BillingZipCode'])) ? $form['BillingZipCode'] : null;
        $form_data['BillingCountry'] = (isset($form['BillingCountry']) && !empty($form['BillingCountry'])) ? $form['BillingCountry'] : null;
        $form_data['Updated'] = date('Y-m-d H:i:s');
        return $form_data;
    }
}
