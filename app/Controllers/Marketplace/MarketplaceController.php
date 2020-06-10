<?php

declare(strict_types=1);

namespace App\Controllers\Marketplace;

use App\Library\Config;
use App\Library\Views;
use App\Models\Marketplace\Marketplace;
use App\Models\Inventory\Category;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Diactoros\ServerRequest;
use Delight\Auth\Auth;
use Delight\Cookie\Session;
use App\Models\Account\Store;
use App\Models\Product\Product;
use PDO;
use Exception;

class MarketplaceController
{
    private $view;
    private $db;
    private $storeid;

    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db = $db;

        $store = (new Store($this->db))->find(Session::get('member_id'), 1);
        $this->storeid   = (isset($store[0]['Id']) && !empty($store[0]['Id'])) ? $store[0]['Id'] : 0;
    }

    public function view()
    {
        $result_data = (new Marketplace($this->db))->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->view->buildResponse('marketplace/view', ['marketplace' => $result_data]);
    }

    public function add()
    {
        $market_places = Config::get('market_places');
        return $this->view->buildResponse('marketplace/add', ['market_places' => $market_places]);
    }

    public function addSecond(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.

        if (is_array($form) && $form['MarketName'] == 'Select Marketplace...') {
            $form['MarketName'] = '';
        }

        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)

        $validate->validation_rules(array(
            'MarketName'    => 'required'
        ));

        $validated = $validate->run($form, true);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            $validated['alert'] = 'Sorry, we could not got to next step.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/marketplace/dashboard');
        }

        $market_price = Config::get('market_price');
        return $this->view->buildResponse('marketplace/add_step_second', ['form' => $form, 'market_price' => $market_price]);
    }

    public function addThree(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
        $validate->validation_rules(array(
            'EmailAddress'    => 'required|valid_email',
            'MarketName'    => 'required',
            'SellerID'    => 'required',
            'Password'    => 'required',
            'FtpAddress'    => 'required',
            'FtpId'    => 'required',
            'FtpPwd'    => 'required',
            'PrependVenue'    => 'required',
            'AppendVenue'    => 'required',
            'IncreaseMinMarket'    => 'required',
            'FileFormat'    => 'required',
            'FtpAppendVenue'    => 'required',
            // 'SuspendExport'    => 'required',
            // 'SendDeletes'    => 'required',
            'MarketAcceptPrice'    => 'required',
            'MarketAcceptPriceVal'    => 'required',
            'MarketAcceptPriceValMulti'    => 'required',
            'MarketSpecificPrice'    => 'required',
            'MarketAcceptPriceVal2'    => 'required',
            'MarketAcceptPriceValMulti2'    => 'required',
        ));

        // Add filters for non-strings (integers, float, emails, etc) ALWAYS Trim
        $validate->filter_rules(array(
            'EmailAddress'    => 'trim|sanitize_email',
        ));
        $validated = $validate->run($form);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            $validated['alert'] = 'Sorry, Please fill marketplace data.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            $market_price = Config::get('market_price');
            return $this->view->buildResponse('marketplace/add_step_second', ['form' => $form, 'market_price' => $market_price]);
        }
        $form_insert_data = array(
            'EmailAddress' => (isset($form['EmailAddress']) && !empty($form['EmailAddress'])) ? $form['EmailAddress'] : null,
            'MarketName' => (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : null,
            'SellerID' => (isset($form['SellerID']) && !empty($form['SellerID'])) ? $form['SellerID'] : null,
            'Password' => (isset($form['Password']) && !empty($form['Password'])) ? $form['Password'] : null,
            'FtpAddress' => (isset($form['FtpAddress']) && !empty($form['FtpAddress'])) ? $form['FtpAddress'] : null,
            'FtpUserId' => (isset($form['FtpId']) && !empty($form['FtpId'])) ? $form['FtpId'] : null,
            'FtpPassword' => (isset($form['FtpPwd']) && !empty($form['FtpPwd'])) ? $form['FtpPwd'] : null,
            'PrependVenue' => (isset($form['PrependVenue']) && !empty($form['PrependVenue'])) ? $form['PrependVenue'] : null,
            'AppendVenue' => (isset($form['AppendVenue']) && !empty($form['AppendVenue'])) ? $form['AppendVenue'] : null,
            'IncreaseMinMarket' => (isset($form['IncreaseMinMarket']) && !empty($form['IncreaseMinMarket'])) ? $form['IncreaseMinMarket'] : null,
            'FileFormat' => (isset($form['FileFormat']) && !empty($form['FileFormat'])) ? $form['FileFormat'] : null,
            'FtpAppendVenue' => (isset($form['FtpAppendVenue']) && !empty($form['FtpAppendVenue'])) ? $form['FtpAppendVenue'] : null,
            'SuspendExport' => (isset($form['SuspendExport']) && !empty($form['SuspendExport'])) ? 1 : null,
            'SendDeletes' => (isset($form['SendDeletes']) && !empty($form['SendDeletes'])) ? 1 : null,
            'MarketAcceptPrice' => (isset($form['MarketAcceptPrice']) && !empty($form['MarketAcceptPrice'])) ? $form['MarketAcceptPrice'] : null,
            'MarketAcceptPriceVal' => (isset($form['MarketAcceptPriceVal']) && !empty($form['MarketAcceptPriceVal'])) ? $form['MarketAcceptPriceVal'] : null,
            'MarketAcceptPriceValMulti' => (isset($form['MarketAcceptPriceValMulti']) && !empty($form['MarketAcceptPriceValMulti'])) ? $form['MarketAcceptPriceValMulti'] : null,
            'MarketSpecificPrice' => (isset($form['MarketSpecificPrice']) && !empty($form['MarketSpecificPrice'])) ? $form['MarketSpecificPrice'] : null,
            'MarketAcceptPriceVal2' => (isset($form['MarketAcceptPriceVal2']) && !empty($form['MarketAcceptPriceVal2'])) ? $form['MarketAcceptPriceVal2'] : null,
            'MarketAcceptPriceValMulti2' => (isset($form['MarketAcceptPriceValMulti2']) && !empty($form['MarketAcceptPriceValMulti2'])) ? $form['MarketAcceptPriceValMulti2'] : null,
            'Status' => (isset($form['MarketStatus']) && $form['MarketStatus'] == 1) ? $form['MarketStatus'] : 0,
            'UserId' => Session::get('auth_user_id'),
        );

        $inserted_result = (new Marketplace($this->db))->addMarketplace($form_insert_data);

        if (isset($inserted_result) && $inserted_result) {
            return $this->view->buildResponse('marketplace/add_step_three');
        } else {
            $validated['alert'] = 'Sorry, we could not add marketplace.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/marketplace/dashboard/step2');
        }
        $this->view->flash($validated);
        return $this->view->redirect('/marketplace/dashboard/step2');
    }

    public function editMarketplace(ServerRequest $request, $Id = [])
    {
        $form = (new Marketplace($this->db))->findById($Id['Id']);
        if (is_array($form) && !empty($form)) {
            $market_price = Config::get('market_price');
            return $this->view->buildResponse('/marketplace/edit', [
                'form' => $form, 'market_price' => $market_price
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch markerplace details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('/marketplace/list');
        }
    }


    public function updateMarketplace(ServerRequest $request, $Id = [])
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.

        $form_udpate_data = array(
            'Id' => (isset($methodData['Id']) && !empty($methodData['Id'])) ? $methodData['Id'] : null,
            'EmailAddress' => (isset($methodData['EmailAddress']) && !empty($methodData['EmailAddress'])) ? $methodData['EmailAddress'] : null,
            'MarketName' => (isset($methodData['MarketName']) && !empty($methodData['MarketName'])) ? $methodData['MarketName'] : null,
            'SellerID' => (isset($methodData['SellerID']) && !empty($methodData['SellerID'])) ? $methodData['SellerID'] : null,
            'Password' => (isset($methodData['Password']) && !empty($methodData['Password'])) ? $methodData['Password'] : null,
            'FtpAddress' => (isset($methodData['FtpAddress']) && !empty($methodData['FtpAddress'])) ? $methodData['FtpAddress'] : null,
            'FtpUserId' => (isset($methodData['FtpId']) && !empty($methodData['FtpId'])) ? $methodData['FtpId'] : null,
            'FtpPassword' => (isset($methodData['FtpPwd']) && !empty($methodData['FtpPwd'])) ? $methodData['FtpPwd'] : null,
            'PrependVenue' => (isset($methodData['PrependVenue']) && !empty($methodData['PrependVenue'])) ? $methodData['PrependVenue'] : null,
            'AppendVenue' => (isset($methodData['AppendVenue']) && !empty($methodData['AppendVenue'])) ? $methodData['AppendVenue'] : null,
            'IncreaseMinMarket' => (isset($methodData['IncreaseMinMarket']) && !empty($methodData['IncreaseMinMarket'])) ? $methodData['IncreaseMinMarket'] : null,
            'FileFormat' => (isset($methodData['FileFormat']) && !empty($methodData['FileFormat'])) ? $methodData['FileFormat'] : null,
            'FtpAppendVenue' => (isset($methodData['FtpAppendVenue']) && !empty($methodData['FtpAppendVenue'])) ? $methodData['FtpAppendVenue'] : null,
            'SuspendExport' => (isset($methodData['SuspendExport']) && !empty($methodData['SuspendExport'])) ? 1 : null,
            'SendDeletes' => (isset($methodData['SendDeletes']) && !empty($methodData['SendDeletes'])) ? 1 : null,
            'MarketAcceptPrice' => (isset($methodData['MarketAcceptPrice']) && !empty($methodData['MarketAcceptPrice'])) ? $methodData['MarketAcceptPrice'] : null,
            'MarketAcceptPriceVal' => (isset($methodData['MarketAcceptPriceVal']) && !empty($methodData['MarketAcceptPriceVal'])) ? $methodData['MarketAcceptPriceVal'] : null,
            'MarketAcceptPriceValMulti' => (isset($methodData['MarketAcceptPriceValMulti']) && !empty($methodData['MarketAcceptPriceValMulti'])) ? $methodData['MarketAcceptPriceValMulti'] : null,
            'MarketSpecificPrice' => (isset($methodData['MarketSpecificPrice']) && !empty($methodData['MarketSpecificPrice'])) ? $methodData['MarketSpecificPrice'] : null,
            'MarketAcceptPriceVal2' => (isset($methodData['MarketAcceptPriceVal2']) && !empty($methodData['MarketAcceptPriceVal2'])) ? $methodData['MarketAcceptPriceVal2'] : null,
            'MarketAcceptPriceValMulti2' => (isset($methodData['MarketAcceptPriceValMulti2']) && !empty($methodData['MarketAcceptPriceValMulti2'])) ? $methodData['MarketAcceptPriceValMulti2'] : null,
            'Status' => (isset($methodData['MarketStatus']) && $methodData['MarketStatus'] == 1) ? $methodData['MarketStatus'] : 0,
            'Updated' => date('Y-m-d H:i:s')
        );

        $is_updated = (new Marketplace($this->db))->editMarket($form_udpate_data);
        if (isset($is_updated) && !empty($is_updated)) {
            $this->view->flash([
                'alert' => 'Marketplace record updated successfully..!',
                'alert_type' => 'success'
            ]);
            $result_data = (new Marketplace($this->db))->getAll();
            return $this->view->buildResponse('marketplace/view', ['marketplace' => $result_data]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to update marketplace. Please ensure all input is filled out correctly.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('/marketplace/edit', [
                'update_id' => $methodData['update_id'],
                'update_name' => $methodData['Name'],
                'update_delivery' => $methodData['DeliveryTime'],
                'update_fee' => $methodData['InitialFee'],
                'update_discount_fee' => $methodData['DiscountFee'],
                'update_minimum' => $methodData['Minimum']
            ]);
        }
    }


    public function deleteMarketData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Marketplace($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {

            $validated['alert'] = 'Marketplace record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Marketplace records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';
            echo json_encode($res);
            exit;
        }
    }


    public function addMarketPrices(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        // Pre defined data starts
        $result_data = (new Marketplace($this->db))->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        // Pre defined data ends
        try {
            $insert_data = $this->PreparePriceInsertData($form);

            $add_result = (new Marketplace($this->db))->addMarketPlacePrice($insert_data);
            if (isset($add_result) && !empty($add_result)) {
                $this->view->flash([
                    'alert' => _('MarketSpecific Price added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $form['Id']);
                // return $this->view->buildResponse('/inventory/product/add', ['all_category' => $all_category, 'form' => $form, 'market_places' => $result_data]);
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

            $validated['alert'] = 'Marketplace Prices are not updated...!';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/product/edit/' . $form['Id']);
        }
    }


    /*
    * PrepareInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PreparePriceInsertData($form = array())
    {
        $form_data = array();

        $form_data['ProductId'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['AbeBooks'] = (isset($form['AbeBooks']) && !empty($form['AbeBooks'])) ? $form['AbeBooks'] : 0.00;
        $form_data['Alibris'] = (isset($form['Alibris']) && !empty($form['Alibris'])) ? $form['Alibris'] : 0.00;
        $form_data['Amazon'] = (isset($form['Amazon']) && !empty($form['Amazon'])) ? $form['Amazon'] : 0.00;
        $form_data['AmazonEurope'] = (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : 0.00;
        $form_data['BarnesAndNoble'] = (isset($form['BarnesAndNoble']) && !empty($form['BarnesAndNoble'])) ? $form['BarnesAndNoble'] : 0.00;
        $form_data['Biblio'] = (isset($form['Biblio']) && !empty($form['Biblio'])) ? $form['Biblio'] : 0.00;
        $form_data['Chrislands'] = (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : 0.00;
        $form_data['eBay'] = (isset($form['eBay']) && !empty($form['eBay'])) ? $form['eBay'] : 0.00;
        $form_data['eCampus'] = (isset($form['eCampus']) && !empty($form['eCampus'])) ? $form['eCampus'] : 0.00;
        $form_data['TextbookRush'] = (isset($form['TextbookRush']) && !empty($form['TextbookRush'])) ? $form['TextbookRush'] : 0.00;
        $form_data['TextbookX'] = (isset($form['TextbookX']) && !empty($form['TextbookX'])) ? $form['TextbookX'] : 0.00;
        $form_data['Valore'] = (isset($form['Valore']) && !empty($form['Valore'])) ? $form['Valore'] : 0.00;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Created'] = date('Y-m-d H:I:S');
        return $form_data;
    }


    /*
    * updateMarketPrices - Update MarketSpecific Price
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateMarketPrices(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.     
        try {
            $is_data = $this->insertOrUpdateMarketPrice($methodData);
            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Marketprice updated successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $methodData['Id']);
            } else {
                throw new Exception("Failed to update marketprice. Please ensure all input is filled out correctly.", 301);
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
            return $this->view->redirect('/product/edit/' . $methodData['Id']);
        }
    }


    /*
    * PreparePriceUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PreparePriceUpdateData($form = array())
    {
        $form_data = array();
        $form_data['AbeBooks'] = (isset($form['AbeBooks']) && !empty($form['AbeBooks'])) ? $form['AbeBooks'] : 0.00;
        $form_data['Alibris'] = (isset($form['Alibris']) && !empty($form['Alibris'])) ? $form['Alibris'] : 0.00;
        $form_data['Amazon'] = (isset($form['Amazon']) && !empty($form['Amazon'])) ? $form['Amazon'] : 0.00;
        $form_data['AmazonEurope'] = (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : 0.00;
        $form_data['BarnesAndNoble'] = (isset($form['BarnesAndNoble']) && !empty($form['BarnesAndNoble'])) ? $form['BarnesAndNoble'] : 0.00;
        $form_data['Biblio'] = (isset($form['Biblio']) && !empty($form['Biblio'])) ? $form['Biblio'] : 0.00;
        $form_data['Chrislands'] = (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : 0.00;
        $form_data['eBay'] = (isset($form['eBay']) && !empty($form['eBay'])) ? $form['eBay'] : 0.00;
        $form_data['eCampus'] = (isset($form['eCampus']) && !empty($form['eCampus'])) ? $form['eCampus'] : 0.00;
        $form_data['TextbookRush'] = (isset($form['TextbookRush']) && !empty($form['TextbookRush'])) ? $form['TextbookRush'] : 0.00;
        $form_data['TextbookX'] = (isset($form['TextbookX']) && !empty($form['TextbookX'])) ? $form['TextbookX'] : 0.00;
        $form_data['Valore'] = (isset($form['Valore']) && !empty($form['Valore'])) ? $form['Valore'] : 0.00;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * insertOrUpdateMarketPrice - find marketprice product
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function insertOrUpdateMarketPrice($data)
    {
        $market_details = (new Marketplace($this->db))->findPriceProductId($data['Id']);

        if (isset($market_details) && !empty($market_details)) { // update
            $update_data = $this->PreparePriceUpdateData($data);
            $result = (new Marketplace($this->db))->updateMarketPriceProduct($data['Id'], $update_data);
        } else { // insert
            $insert_data = $this->PreparePriceInsertData($data);
            $result = (new Marketplace($this->db))->addMarketPlacePrice($insert_data);
        }
        return $result;
    }

    /*
    * updateMarketTemplate - update Market Template Price
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateMarketTemplate(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.     
        try {

            $is_data = $this->insertOrUpdateMarketTemplate($methodData);
            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Market template updated successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $methodData['Id']);
            } else {
                throw new Exception("Failed to update template. Please ensure all input is filled out correctly.", 301);
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
            return $this->view->redirect('/product/edit/' . $methodData['Id']);
        }
    }

    /*
    * insertOrUpdateMarketTemplate - find insertOrUpdateMarketTemplate product
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function insertOrUpdateMarketTemplate($data)
    {
        $market_details = (new Marketplace($this->db))->findTemplateProductId($data['Id']);

        if (isset($market_details) && !empty($market_details)) { // update
            $update_data = $this->PrepareTemplateUpdateData($data);
            $result = (new Marketplace($this->db))->updateMarketTemplateProduct($data['Id'], $update_data);
        } else { // insert
            $insert_data = $this->PrepareTemplateInsertData($data);
            $result = (new Marketplace($this->db))->addMarketTemplateProduct($insert_data);
        }
        return $result;
    }

    /*
    * PrepareTemplateUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareTemplateUpdateData($form = array())
    {
        $form_data = array();
        $form_data['DefaultTemplate'] = (isset($form['DefaultTemplate']) && !empty($form['DefaultTemplate'])) ? $form['DefaultTemplate'] : null;
        $form_data['AbeBooksTemplate'] = (isset($form['AbeBooksTemplate']) && !empty($form['AbeBooksTemplate'])) ? $form['AbeBooksTemplate'] : null;
        $form_data['AlibrisTemplate'] = (isset($form['AlibrisTemplate']) && !empty($form['AlibrisTemplate'])) ? $form['AlibrisTemplate'] : null;
        $form_data['AmazonTemplate'] = (isset($form['AmazonTemplate']) && !empty($form['AmazonTemplate'])) ? $form['AmazonTemplate'] : null;
        $form_data['AmazonEuropeTemplate'] = (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : null;
        $form_data['BarnesAndNobleTemplate'] = (isset($form['BarnesAndNobleTemplate']) && !empty($form['BarnesAndNobleTemplate'])) ? $form['BarnesAndNobleTemplate'] : null;
        $form_data['BiblioTemplate'] = (isset($form['BiblioTemplate']) && !empty($form['BiblioTemplate'])) ? $form['BiblioTemplate'] : null;
        $form_data['ChrislandsTemplate'] = (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : null;
        $form_data['eBayTemplate'] = (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : null;
        $form_data['eCampusTemplate'] = (isset($form['eCampusTemplate']) && !empty($form['eCampusTemplate'])) ? $form['eCampusTemplate'] : null;
        $form_data['TextbookRushTemplate'] = (isset($form['TextbookRushTemplate']) && !empty($form['TextbookRushTemplate'])) ? $form['TextbookRushTemplate'] : null;
        $form_data['TextbookXTemplate'] = (isset($form['TextbookXTemplate']) && !empty($form['TextbookXTemplate'])) ? $form['TextbookXTemplate'] : null;
        $form_data['ValoreTemplate'] = (isset($form['ValoreTemplate']) && !empty($form['ValoreTemplate'])) ? $form['ValoreTemplate'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * PrepareTemplateInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareTemplateInsertData($form = array())
    {
        $form_data = array();
        $form_data['ProductId'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['DefaultTemplate'] = (isset($form['DefaultTemplate']) && !empty($form['DefaultTemplate'])) ? $form['DefaultTemplate'] : null;
        $form_data['AbeBooksTemplate'] = (isset($form['AbeBooksTemplate']) && !empty($form['AbeBooksTemplate'])) ? $form['AbeBooksTemplate'] : null;
        $form_data['AlibrisTemplate'] = (isset($form['AlibrisTemplate']) && !empty($form['AlibrisTemplate'])) ? $form['AlibrisTemplate'] : null;
        $form_data['AmazonTemplate'] = (isset($form['AmazonTemplate']) && !empty($form['AmazonTemplate'])) ? $form['AmazonTemplate'] : null;
        $form_data['AmazonEuropeTemplate'] = (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : null;
        $form_data['BarnesAndNobleTemplate'] = (isset($form['BarnesAndNobleTemplate']) && !empty($form['BarnesAndNobleTemplate'])) ? $form['BarnesAndNobleTemplate'] : null;
        $form_data['BiblioTemplate'] = (isset($form['BiblioTemplate']) && !empty($form['BiblioTemplate'])) ? $form['BiblioTemplate'] : null;
        $form_data['ChrislandsTemplate'] = (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : null;
        $form_data['eBayTemplate'] = (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : null;
        $form_data['eCampusTemplate'] = (isset($form['eCampusTemplate']) && !empty($form['eCampusTemplate'])) ? $form['eCampusTemplate'] : null;
        $form_data['TextbookRushTemplate'] = (isset($form['TextbookRushTemplate']) && !empty($form['TextbookRushTemplate'])) ? $form['TextbookRushTemplate'] : null;
        $form_data['TextbookXTemplate'] = (isset($form['TextbookXTemplate']) && !empty($form['TextbookXTemplate'])) ? $form['TextbookXTemplate'] : null;
        $form_data['ValoreTemplate'] = (isset($form['ValoreTemplate']) && !empty($form['ValoreTemplate'])) ? $form['ValoreTemplate'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Created'] = date('Y-m-d H:I:S');
        return $form_data;
    }


    /*
    * updateMarketShipRates - update Market Ship Rates
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateMarketShipRates(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.     
        try {

            $is_data = $this->insertOrUpdateMarketShipRate($methodData);
            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Market ship rate updated successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $methodData['Id']);
            } else {
                throw new Exception("Failed to update Market ship rate. Please ensure all input is filled out correctly.", 301);
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
            return $this->view->redirect('/product/edit/' . $methodData['Id']);
        }
    }

    /*
    * insertOrUpdateMarketTemplate - find insertOrUpdateMarketTemplate product
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function insertOrUpdateMarketShipRate($data)
    {
        $market_details = (new Marketplace($this->db))->findShipRateProductId($data['Id']);

        if (isset($market_details) && !empty($market_details)) { // update
            $update_data = $this->PrepareShipRateUpdateData($data);
            $result = (new Marketplace($this->db))->updateMarketShipRateProduct($data['Id'], $update_data);
        } else { // insert
            $insert_data = $this->PrepareShipRateInsertData($data);
            $result = (new Marketplace($this->db))->addMarketShipRateProduct($insert_data);
        }
        return $result;
    }

    /*
    * PrepareShipRateUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareShipRateUpdateData($form = array())
    {
        $form_data = array();
        $form_data['Domestic'] = (isset($form['Domestic']) && !empty($form['Domestic'])) ? $form['Domestic'] : null;
        $form_data['International'] = (isset($form['International']) && !empty($form['International'])) ? $form['International'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * PrepareShipRateInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareShipRateInsertData($form = array())
    {
        $form_data = array();
        $form_data['ProductId'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['Domestic'] = (isset($form['Domestic']) && !empty($form['Domestic'])) ? $form['Domestic'] : null;
        $form_data['International'] = (isset($form['International']) && !empty($form['International'])) ? $form['International'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Created'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * updateMarketHandling - update Market Ship Rates
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateMarketHandling(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.     
        try {

            $is_data = $this->insertOrUpdateMarketHandlingTime($methodData);
            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Market handling updated successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $methodData['Id']);
            } else {
                throw new Exception("Failed to update Market handling Please ensure all input is filled out correctly.", 301);
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
            return $this->view->redirect('/product/edit/' . $methodData['Id']);
        }
    }

    /*
    * insertOrUpdateMarketHandlingTime - find insertOrUpdateMarketHandlingTime product
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function insertOrUpdateMarketHandlingTime($data)
    {
        $market_details = (new Marketplace($this->db))->findHandlingTimeProductId($data['Id']);

        if (isset($market_details) && !empty($market_details)) { // update
            $update_data = $this->PrepareHandlingUpdateData($data);
            $result = (new Marketplace($this->db))->updateMarketHindlingProduct($data['Id'], $update_data);
        } else { // insert
            $insert_data = $this->PrepareHindlingInsertData($data);
            $result = (new Marketplace($this->db))->addMarketHindlingProduct($insert_data);
        }
        return $result;
    }

    /*
    * PrepareHandlingUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareHandlingUpdateData($form = array())
    {
        $form_data = array();
        $form_data['DefaultHandlingTime'] = (isset($form['DefaultHandlingTime']) && !empty($form['DefaultHandlingTime'])) ? $form['DefaultHandlingTime'] : null;
        $form_data['AbeBooksHandlingTime'] = (isset($form['AbeBooksHandlingTime']) && !empty($form['AbeBooksHandlingTime'])) ? $form['AbeBooksHandlingTime'] : null;
        $form_data['AlibrisHandlingTime'] = (isset($form['AlibrisHandlingTime']) && !empty($form['AlibrisHandlingTime'])) ? $form['AlibrisHandlingTime'] : null;
        $form_data['AmazonHandlingTime'] = (isset($form['AmazonHandlingTime']) && !empty($form['AmazonHandlingTime'])) ? $form['AmazonHandlingTime'] : null;
        $form_data['AmazonEuropeHandlingTime'] = (isset($form['AmazonEuropeHandlingTime']) && !empty($form['AmazonEuropeHandlingTime'])) ? $form['AmazonEuropeHandlingTime'] : null;
        $form_data['BarnesAndNobleHandlingTime'] = (isset($form['BarnesAndNobleHandlingTime']) && !empty($form['BarnesAndNobleHandlingTime'])) ? $form['BarnesAndNobleHandlingTime'] : null;
        $form_data['BiblioHandlingTime'] = (isset($form['BiblioHandlingTime']) && !empty($form['BiblioHandlingTime'])) ? $form['BiblioHandlingTime'] : null;
        $form_data['ChrislandsHandlingTime'] = (isset($form['ChrislandsHandlingTime']) && !empty($form['ChrislandsHandlingTime'])) ? $form['ChrislandsHandlingTime'] : null;
        $form_data['eBayHandlingTime'] = (isset($form['eBayHandlingTime']) && !empty($form['eBayHandlingTime'])) ? $form['eBayHandlingTime'] : null;
        $form_data['eCampusHandlingTime'] = (isset($form['eCampusHandlingTime']) && !empty($form['eCampusHandlingTime'])) ? $form['eCampusHandlingTime'] : null;
        $form_data['TextbookRushHandlingTime'] = (isset($form['TextbookRushHandlingTime']) && !empty($form['TextbookRushHandlingTime'])) ? $form['TextbookRushHandlingTime'] : null;
        $form_data['TextbookXHandlingTime'] = (isset($form['TextbookXHandlingTime']) && !empty($form['TextbookXHandlingTime'])) ? $form['TextbookXHandlingTime'] : null;
        $form_data['ValoreHandlingTime'] = (isset($form['ValoreHandlingTime']) && !empty($form['ValoreHandlingTime'])) ? $form['ValoreHandlingTime'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * PrepareHindlingInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareHindlingInsertData($form = array())
    {
        $form_data = array();
        $form_data['ProductId'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['DefaultHandlingTime'] = (isset($form['DefaultHandlingTime']) && !empty($form['DefaultHandlingTime'])) ? $form['DefaultHandlingTime'] : null;
        $form_data['AbeBooksHandlingTime'] = (isset($form['AbeBooksHandlingTime']) && !empty($form['AbeBooksHandlingTime'])) ? $form['AbeBooksHandlingTime'] : null;
        $form_data['AlibrisHandlingTime'] = (isset($form['AlibrisHandlingTime']) && !empty($form['AlibrisHandlingTime'])) ? $form['AlibrisHandlingTime'] : null;
        $form_data['AmazonHandlingTime'] = (isset($form['AmazonHandlingTime']) && !empty($form['AmazonHandlingTime'])) ? $form['AmazonHandlingTime'] : null;
        $form_data['AmazonEuropeHandlingTime'] = (isset($form['AmazonEuropeHandlingTime']) && !empty($form['AmazonEuropeHandlingTime'])) ? $form['AmazonEuropeHandlingTime'] : null;
        $form_data['BarnesAndNobleHandlingTime'] = (isset($form['BarnesAndNobleHandlingTime']) && !empty($form['BarnesAndNobleHandlingTime'])) ? $form['BarnesAndNobleHandlingTime'] : null;
        $form_data['BiblioHandlingTime'] = (isset($form['BiblioHandlingTime']) && !empty($form['BiblioHandlingTime'])) ? $form['BiblioHandlingTime'] : null;
        $form_data['ChrislandsHandlingTime'] = (isset($form['ChrislandsHandlingTime']) && !empty($form['ChrislandsHandlingTime'])) ? $form['ChrislandsHandlingTime'] : null;
        $form_data['eBayHandlingTime'] = (isset($form['eBayHandlingTime']) && !empty($form['eBayHandlingTime'])) ? $form['eBayHandlingTime'] : null;
        $form_data['eCampusHandlingTime'] = (isset($form['eCampusHandlingTime']) && !empty($form['eCampusHandlingTime'])) ? $form['eCampusHandlingTime'] : null;
        $form_data['TextbookRushHandlingTime'] = (isset($form['TextbookRushHandlingTime']) && !empty($form['TextbookRushHandlingTime'])) ? $form['TextbookRushHandlingTime'] : null;
        $form_data['TextbookXHandlingTime'] = (isset($form['TextbookXHandlingTime']) && !empty($form['TextbookXHandlingTime'])) ? $form['TextbookXHandlingTime'] : null;
        $form_data['ValoreHandlingTime'] = (isset($form['ValoreHandlingTime']) && !empty($form['ValoreHandlingTime'])) ? $form['ValoreHandlingTime'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Created'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * updateMarketAdditionalInfo - update Market Ship Rates
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateMarketAdditionalInfo(ServerRequest $request)
    {
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.     
        try {

            $is_data = $this->insertOrUpdateMarketAdditional($methodData);
            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Market Additional Information updated successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/product/edit/' . $methodData['Id']);
            } else {
                throw new Exception("Failed to update Market Additional Information Please ensure all input is filled out correctly.", 301);
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
            return $this->view->redirect('/product/edit/' . $methodData['Id']);
        }
    }

    /*
    * insertOrUpdateMarketHandlingTime - find insertOrUpdateMarketHandlingTime product
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function insertOrUpdateMarketAdditional($data)
    {
        $market_details = (new Marketplace($this->db))->findAdditionalProductId($data['Id']);

        if (isset($market_details) && !empty($market_details)) { // update
            $update_data = $this->PrepareAdditionalUpdateData($data);
            $result = (new Marketplace($this->db))->updateAdditionalProduct($data['Id'], $update_data);
        } else { // insert

            $insert_data = $this->PrepareAdditionalInsertData($data);
            $result = (new Marketplace($this->db))->addAdditionalProduct($insert_data);
        }
        return $result;
    }

    /*
    * PrepareAdditionalUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareAdditionalUpdateData($form = array())
    {
        $form_data = array();
        $form_data['Cost'] = (isset($form['Cost']) && !empty($form['Cost'])) ? $form['Cost'] : null;
        $form_data['Location'] = (isset($form['Location']) && !empty($form['Location'])) ? $form['Location'] : null;
        $form_data['Brand'] = (isset($form['Brand']) && !empty($form['Brand'])) ? $form['Brand'] : null;
        $form_data['Language'] = (isset($form['Language']) && !empty($form['Language'])) ? $form['Language'] : null;
        $form_data['Source'] = (isset($form['Source']) && !empty($form['Source'])) ? $form['Source'] : null;
        $form_data['Category'] = (isset($form['Category']) && !empty($form['Category'])) ? $form['Category'] : null;
        $form_data['ManufacturerPartNumber'] = (isset($form['ManufacturerPartNumber']) && !empty($form['ManufacturerPartNumber'])) ? $form['ManufacturerPartNumber'] : null;
        $form_data['AdditionalUIEE'] = (isset($form['AdditionalUIEE']) && !empty($form['AdditionalUIEE'])) ? $form['AdditionalUIEE'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:I:S');
        return $form_data;
    }

    /*
    * PrepareAdditionalInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareAdditionalInsertData($form = array())
    {
        $form_data = array();
        $form_data['ProductId'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['Cost'] = (isset($form['Cost']) && !empty($form['Cost'])) ? $form['Cost'] : null;
        $form_data['Location'] = (isset($form['Location']) && !empty($form['Location'])) ? $form['Location'] : null;
        $form_data['Brand'] = (isset($form['Brand']) && !empty($form['Brand'])) ? $form['Brand'] : null;
        $form_data['Language'] = (isset($form['Language']) && !empty($form['Language'])) ? $form['Language'] : null;
        $form_data['Source'] = (isset($form['Source']) && !empty($form['Source'])) ? $form['Source'] : null;
        $form_data['Category'] = (isset($form['Category']) && !empty($form['Category'])) ? $form['Category'] : null;
        $form_data['ManufacturerPartNumber'] = (isset($form['ManufacturerPartNumber']) && !empty($form['ManufacturerPartNumber'])) ? $form['ManufacturerPartNumber'] : null;
        $form_data['AdditionalUIEE'] = (isset($form['AdditionalUIEE']) && !empty($form['AdditionalUIEE'])) ? $form['AdditionalUIEE'] : null;
        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
        $form_data['Created'] = date('Y-m-d H:I:S');
        return $form_data;
    }
}
