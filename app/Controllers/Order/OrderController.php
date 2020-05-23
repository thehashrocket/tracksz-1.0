<?php

declare(strict_types=1);

namespace App\Controllers\Order;

use App\Library\Views;
use Delight\Auth\Auth;
use App\Models\Inventory\OrderSetting;
use App\Models\Order\PostageSetting;
use App\Models\Order\LabelSetting;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Account\Store;
use App\Models\Marketplace\Marketplace;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use Exception;
use PDO;
use App\Library\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriteXlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriteCsv;
use Illuminate\Http\Request;
use Delight\Cookie\Session;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use Laminas\Log\Formatter\Json;
use App\Library\Email;
// use Resque;

class OrderController
{
    private $view;
    private $auth;
    private $db;
    private $storeid;
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
        $store = (new Store($this->db))->find(Session::get('member_id'), 1);
        $this->storeid   = (isset($store[0]['Id']) && !empty($store[0]['Id'])) ? $store[0]['Id'] : 0;
        ini_set('memory_limit', '-1');
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
        return $this->view->buildResponse('order/batchmove', []);
    }

    /*
    * updateBatchMove - Update Batch Move
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateBatchMove(ServerRequest $request)
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $map_data = $this->mapBatchMove($methodData);
            $is_data = $this->insertOrUpdate($map_data);

            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Order Batch moved successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->buildResponse('order/batchmove', []);
            } else {
                throw new Exception("Order Id not found into our database...!", 301);
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
            return $this->view->buildResponse('order/batchmove', []);
        }
    }

    /*
         @author    :: Tejas
         @task_id   :: 
         @task_desc :: 
         @params    :: 
        */
    public function mapBatchMove($batch_data = array())
    {
        $order_ids = [];
        if ($batch_data['OrderId'] == trim($batch_data['OrderId']) && strpos($batch_data['OrderId'], ' ') !== false) {
            $order_ids = explode(" ", $batch_data['OrderId']);
        } else if ($batch_data['OrderId'] == trim($batch_data['OrderId']) && strpos($batch_data['OrderId'], ',') !== false) {
            $order_ids = explode(",", $batch_data['OrderId']);
        } else {
            $order_ids[] = $batch_data['OrderId'];
        }

        $map_order = [];
        $set_map_order = [];
        foreach ($order_ids as $order) {
            $map_order['OrderId'] = $order;
            $map_order['Status'] = $batch_data['UpdateStatusOrder'];
            $map_order['Carrier'] = $batch_data['ShippingCarrierOrder'];
            $set_map_order[] = $map_order;
        } // Loops Ends
        return $set_map_order;
    }

    /*
    * insertOrUpdate - find user id if exist
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function insertOrUpdate($data)
    {
        $result = false;
        foreach ($data as $key => $value) {
            $user_details = (new Order($this->db))->findByOrderID($value['OrderId'], Session::get('auth_user_id'));
            if (isset($user_details) && !empty($user_details)) { // update
                $data['Updated'] = date('Y-m-d H:i:s');
                $result = (new Order($this->db))->editOrder($user_details['Id'], $value);
            }
        } // Loops Ends
        return $result;
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
        $all_order = (new Order($this->db))->getAllBelongsTo();
        return $this->view->buildResponse('order/export_order', ['all_order' => $all_order]);
        //return $this->view->buildResponse('order/defaults', []);
    }

    public function exportOrderData(ServerRequest $request)
    {
        try {
            $form = $request->getParsedBody();
            $export_type = $form['export_format'];


            $from_date = $form['from_date'];
            $to_date = $form['to_date'];

            $orderStatus = $form['orderStatus'];

            $allorder   = $form['exportType'];

             if($allorder == 'new')
            {
               $orderStatus = $allorder;
               $order_data = (new Order($this->db))->orderstatusSearchByOrderData($orderStatus);
            }


            if($allorder == 'All')
            {
               $order_data = (new Order($this->db))->allorderSearchByOrderData();
            }

            if($orderStatus!= '')
            {
                 $order_data = (new Order($this->db))->orderstatusSearchByOrderData($orderStatus);
            }

            if($from_date!='')
            {
              
             $formD  =  date("Y-m-d",strtotime($from_date));
             $ToD    =  date("Y-m-d",strtotime($to_date));

             $order_data = (new Order($this->db))->dateRangeSearchByOrderData($formD, $ToD);
            }

            
          


           $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'MarketPlaceId');
            $sheet->setCellValue('B1', 'OrderId');
            $sheet->setCellValue('C1', 'Status');
            $sheet->setCellValue('D1', 'Currency');
            $sheet->setCellValue('E1', 'PaymentStatus');
            $sheet->setCellValue('F1', 'PaymentMethod');
            $sheet->setCellValue('G1', 'BuyerNote');
            $sheet->setCellValue('H1', 'SellerNote');
            $sheet->setCellValue('I1', 'ShippingMethod');
            $sheet->setCellValue('J1', 'Tracking');
            $sheet->setCellValue('K1', 'Carrier');
            $sheet->setCellValue('L1', 'ShippingName');
            $sheet->setCellValue('M1', 'ShippingPhone');
            $sheet->setCellValue('N1', 'ShippingEmail');
            $sheet->setCellValue('O1', 'ShippingAddress1');
            $sheet->setCellValue('P1', 'ShippingAddress2');
            $sheet->setCellValue('Q1', 'ShippingAddress3');
            $sheet->setCellValue('R1', 'ShippingCity');
            $sheet->setCellValue('S1', 'ShippingState');
            $sheet->setCellValue('T1', 'ShippingZipCode');
            $sheet->setCellValue('U1', 'ShippingCountry');
            $sheet->setCellValue('V1', 'BillingName');
            $sheet->setCellValue('W1', 'BillingPhone');
            $sheet->setCellValue('X1', 'BillingEmail');
            $sheet->setCellValue('Y1', 'BillingAddress1');
            $sheet->setCellValue('Z1', 'BillingAddress2');
            $sheet->setCellValue('AA1', 'BillingAddress3');
            $sheet->setCellValue('AB1', 'BillingCity');
            $sheet->setCellValue('AC1', 'BillingState');
            $sheet->setCellValue('AD1', 'BillingZipCode');
            $sheet->setCellValue('AE1', 'BillingCountry');
            
            $rows = 2;
            foreach ($order_data as $orderd) {
                
                $sheet->setCellValue('A' . $rows, $orderd['MarketPlaceId']);
                $sheet->setCellValue('B' . $rows, $orderd['OrderId']);
                $sheet->setCellValue('C' . $rows, $orderd['Status']);
                $sheet->setCellValue('D' . $rows, $orderd['Currency']);
                $sheet->setCellValue('E' . $rows, $orderd['PaymentStatus']);
                $sheet->setCellValue('F' . $rows, $orderd['PaymentMethod']);
                $sheet->setCellValue('G' . $rows, $orderd['BuyerNote']);
                $sheet->setCellValue('H' . $rows, $orderd['SellerNote']);
                $sheet->setCellValue('I' . $rows, $orderd['ShippingMethod']);
                $sheet->setCellValue('J' . $rows, $orderd['Tracking']);
                $sheet->setCellValue('K' . $rows, $orderd['Carrier']);
                $sheet->setCellValue('L' . $rows, $orderd['ShippingName']);
                $sheet->setCellValue('M' . $rows, $orderd['ShippingPhone']);
                $sheet->setCellValue('N' . $rows, $orderd['ShippingEmail']);
                $sheet->setCellValue('O' . $rows, $orderd['ShippingAddress1']);
                $sheet->setCellValue('P' . $rows, $orderd['ShippingAddress2']);
                 $sheet->setCellValue('Q' . $rows, $orderd['ShippingAddress3']);
                $sheet->setCellValue('R' . $rows, $orderd['ShippingCity']);
                $sheet->setCellValue('S' . $rows, $orderd['ShippingState']);
                $sheet->setCellValue('T' . $rows, $orderd['ShippingZipCode']);
                $sheet->setCellValue('U' . $rows, $orderd['ShippingCountry']);
                $sheet->setCellValue('V' . $rows, $orderd['BillingName']);
                $sheet->setCellValue('W' . $rows, $orderd['BillingPhone']);
                $sheet->setCellValue('X' . $rows, $orderd['BillingEmail']);
                $sheet->setCellValue('Y' . $rows, $orderd['BillingAddress1']);
                $sheet->setCellValue('Z' . $rows, $orderd['BillingAddress2']);
                $sheet->setCellValue('AA' . $rows, $orderd['BillingAddress3']);
                $sheet->setCellValue('AB' . $rows, $orderd['BillingCity']);
                $sheet->setCellValue('AC' . $rows, $orderd['BillingState']);
                $sheet->setCellValue('AD' . $rows, $orderd['BillingZipCode']);
                $sheet->setCellValue('AE' . $rows, $orderd['BillingCountry']);
                
                $rows++;
            }

            if ($export_type == 'xlsx' || $export_type == 'csv') {
                $this->view->flash([
                    'alert' => 'Order Data sucessfully export..!',
                    'alert_type' => 'success'
                ]);

                if ($export_type == 'xlsx') {
                    //$writer = new WriteXlsx($spreadsheet);
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="order.xlsx"');
                    $writer->save("php://output");
                    return $this->view->redirect('/order/export-order');
                } else if ($export_type == 'csv') {
                    $writer = new WriteCsv($spreadsheet);
                    $writer->save("order." . $export_type);
                    return $this->view->redirect('/order/export-order');
                }
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

            $validated['alert'] = 'Please Select Xlsx or Csv File Format..!';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/order/export-order');
        }
            
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
        $all_order = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));
        return $this->view->buildResponse('order/postage_setting', ['all_order' => $all_order]);
    }
    public function postageinsertOrUpdate($data)
    {
        $postage_setting = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));
        if (isset($postage_setting) && !empty($postage_setting)) { // update
            $data['Updated'] = date('Y-m-d H:i:s');
            $result = (new PostageSetting($this->db))->editPostageSettings($data);
        } else { // insert
            $data['Created'] = date('Y-m-d H:i:s');
            $result = (new PostageSetting($this->db))->addPostageSettings($data);
        }

        return $result;
    }
    /*
    * PostageAddupdateSettings - Add Update Postage Settings
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function postageAddUpdateSettings(ServerRequest $request)
    {

        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data['UserId'] = Session::get('auth_user_id');
            $update_data['OperatingSystem'] = $methodData['OperatingSystem'];
            $update_data['MaxWeight'] = $methodData['MaxWeight'];
            $update_data['DeliveryConfirmation'] = $methodData['DeliveryConfirmation'];
            $update_data['MinOrderTotalDelivery'] = $methodData['MinOrderTotalDelivery'];
            $update_data['SignatureConfirmation'] = $methodData['SignatureConfirmation'];
            $update_data['ConsolidatorLabel'] = $methodData['ConsolidatorLabel'];

            $update_data['IncludeInsurance'] = $methodData['IncludeInsurance'];
            $update_data['MinOrderTotalInsurance'] = $methodData['MinOrderTotalInsurance'];
            $update_data['RoundDownPartial'] = $methodData['RoundDownPartial'];

            $update_data['EstimatePostage'] = $methodData['EstimatePostage'];
            $update_data['MaxPostageBatch'] = $methodData['MaxPostageBatch'];
            $update_data['CustomsSigner'] = $methodData['CustomsSigner'];
            $update_data['DefaultWeight'] = $methodData['DefaultWeight'];
            $update_data['FlatRatePriority'] = $methodData['FlatRatePriority'];
            $update_data['GlobalWeight'] = $methodData['GlobalWeight'];



            $is_data = $this->postageinsertOrUpdate($update_data);


            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Postage settings updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $all_order = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));



                return $this->view->buildResponse('order/postage_setting', ['all_order' => $all_order]);
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
            /*$user_details = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('order/postage_setting', ['all_settings' => $user_details]);*/

            $all_order = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('order/postage_setting', ['all_order' => $all_order]);
        }
    }



    /*
    * view - Load loadLabelSetting view file
    * @param  - none
    * @return view
    */
    public function loadLabelSetting()
    {
        $all_order = (new LabelSetting($this->db))->LabelSettingfindByUserId(Session::get('auth_user_id'));
        return $this->view->buildResponse('order/label_setting', ['all_order' => $all_order]);
    }
    public function labelinsertOrUpdate($data)
    {
        $label_setting = (new LabelSetting($this->db))->LabelSettingfindByUserId(Session::get('auth_user_id'));

        if (!empty($label_setting)) { // update

            $data['Updated'] = date('Y-m-d H:i:s');

            $result = (new LabelSetting($this->db))->editLabelSettings($data);
           
        } else { // insert
            $data['Created'] = date('Y-m-d H:i:s');
            $result = (new LabelSetting($this->db))->addLabelSettings($data);
        }

        return $result;
    }
    /*
    * LabelAddupdateSettings - Add Update Label Settings
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function LabelAddUpdateSettings(ServerRequest $request)
    {

        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data['UserId'] = Session::get('auth_user_id');
            // $update_data['SkipPDFView'] = $methodData['SkipPDFView'];
            $update_data['SkipPDFView'] = (isset($methodData['SkipPDFView']) && !empty($methodData['SkipPDFView']))?1:null;
            // print_r($update_data['SkipPDFView']);
            $update_data['DefaultAction'] = $methodData['DefaultAction'];
            $update_data['SortOrders'] = $methodData['SortOrders'];

            // $update_data['SplitOrders'] = $methodData['SplitOrders'];
            $update_data['SplitOrders'] = (isset($methodData['SplitOrders']) && !empty($methodData['SplitOrders'])) ? 1 : null;
            $update_data['AddBarcode'] = (isset($methodData['AddBarcode']) && !empty($methodData['AddBarcode'])) ? 1 : null;
            //$update_data['AddBarcode'] = $methodData['AddBarcode'];
            $update_data['BarcodeType'] = $methodData['BarcodeType'];
            $update_data['SortPickList'] = $methodData['SortPickList'];

            $update_data['DefaultTemplate'] = $methodData['DefaultTemplate'];
            $update_data['HeaderImageURL'] = $methodData['HeaderImageURL'];
            $update_data['FooterImageURL'] = $methodData['FooterImageURL'];
            $update_data['PackingSlipHeader'] = $methodData['PackingSlipHeader'];
            $update_data['PackingSlipFooter'] = $methodData['PackingSlipFooter'];
            $update_data['PackingSlipFrom'] = $methodData['PackingSlipFrom'];
            // $update_data['IncludeOrderBarcodes'] = $methodData['IncludeOrderBarcodes'];
            $update_data['IncludeOrderBarcodes'] = (isset($methodData['IncludeOrderBarcodes']) && !empty($methodData['IncludeOrderBarcodes'])) ? 1 : null;
            $update_data['IncludeItemBarcodes'] = (isset($methodData['IncludeItemBarcodes']) && !empty($methodData['IncludeItemBarcodes'])) ? 1 : null;
            $update_data['CentreHeaderText'] = (isset($methodData['CentreHeaderText']) && !empty($methodData['CentreHeaderText'])) ? 1 : null;
            $update_data['HideEmail'] = (isset($methodData['HideEmail']) && !empty($methodData['HideEmail'])) ? 1 : null;
            $update_data['HidePhone'] = (isset($methodData['HidePhone']) && !empty($methodData['HidePhone'])) ? 1 : null;
            $update_data['IncludeGSTExAus1'] = (isset($methodData['IncludeGSTExAus1']) && !empty($methodData['IncludeGSTExAus1'])) ? 1 : null;
            $update_data['CentreFooter'] = (isset($methodData['CentreFooter']) && !empty($methodData['CentreFooter'])) ? 1 : null;
            $update_data['ShowItemPrice'] = (isset($methodData['ShowItemPrice']) && !empty($methodData['ShowItemPrice'])) ? 1 : null;
            $update_data['IncludeMarketplaceOrder'] = (isset($methodData['IncludeMarketplaceOrder']) && !empty($methodData['IncludeMarketplaceOrder'])) ? 1 : null;
            $update_data['IncludePageNumbers'] = (isset($methodData['IncludePageNumbers']) && !empty($methodData['IncludePageNumbers'])) ? 1 : null;
            //$update_data['IncludeItemBarcodes'] = $methodData['IncludeItemBarcodes'];
            //$update_data['CentreHeaderText'] = $methodData['CentreHeaderText'];
            // $update_data['HideEmail'] = $methodData['HideEmail'];
            //$update_data['HidePhone'] = $methodData['HidePhone'];
            /*$update_data['IncludeGSTExAus1'] = $methodData['IncludeGSTExAus1'];
            $update_data['CentreFooter'] = $methodData['CentreFooter'];
            $update_data['ShowItemPrice'] = $methodData['ShowItemPrice'];
            $update_data['IncludeMarketplaceOrder'] = $methodData['IncludeMarketplaceOrder'];
            $update_data['IncludePageNumbers'] = $methodData['IncludePageNumbers'];*/
            

            $update_data['ColumnsPerPage'] = $methodData['ColumnsPerPage'];
            $update_data['RowsPerPage'] = $methodData['RowsPerPage'];
            $update_data['FontSize'] = $methodData['FontSize'];
            $update_data['HideLabelBoundaries'] = (isset($methodData['HideLabelBoundaries']) && !empty($methodData['HideLabelBoundaries'])) ? 1 : null;
            $update_data['IncludeGSTExAus2'] = (isset($methodData['IncludeGSTExAus2']) && !empty($methodData['IncludeGSTExAus2'])) ? 1 : null;
            //$update_data['HideLabelBoundaries'] = $methodData['HideLabelBoundaries'];
            //$update_data['IncludeGSTExAus2'] = $methodData['IncludeGSTExAus2'];
            $update_data['LabelWidth'] = $methodData['LabelWidth'];

            $update_data['LabelWidthIn'] = $methodData['LabelWidthIn'];
            $update_data['LabelHeight'] = $methodData['LabelHeight'];
            $update_data['LabelHeightIn'] = $methodData['LabelHeightIn'];
            $update_data['PageMargins'] = $methodData['PageMargins'];
            $update_data['PageMarginsIn'] = $methodData['PageMarginsIn'];
            $update_data['LabelMargins'] = $methodData['LabelMargins'];
            $update_data['LabelMarginsIn'] = $methodData['LabelMarginsIn'];


            
            $is_data = $this->labelinsertOrUpdate($update_data);
            

            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Label settings updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $all_order = (new LabelSetting($this->db))->LabelSettingfindByUserId(Session::get('auth_user_id'));



                return $this->view->buildResponse('order/label_setting', ['all_order' => $all_order]);
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
            /*$user_details = (new PostageSetting($this->db))->PostageSettingfindByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('order/postage_setting', ['all_settings' => $user_details]);*/

            $all_order = (new LabelSetting($this->db))->LabelSettingfindByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('order/label_setting', ['all_order' => $all_order]);
        }
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
        return $this->view->buildResponse('inventory/settings/order', ['order_details' => $order_details]);
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
            $update_data['DontSendCopy'] = (isset($methodData['DontSendCopy']) && !empty($methodData['DontSendCopy']))?1:null;
            $update_data['NoAdditionalOrder'] = $methodData['NoAdditionalOrder'];
           /* for($i=1; $i <= $nooforderfoldercount;$i++)
            {
                $work1 = $methodData['NoAdditionalOrder'.$i];
                //echo 'sadasda';
                //print_r($work1); exit;
            }
            return $i;*/
  /*          $sql = array;
$yourArrFromCsv = explode(",", $nooforderfoldercount);
//then insert to db
foreach( $yourArrFromCsv as $row ) {
    $sql[] = '('.$compProdId.', '.$row.')';
}
mysql_query('INSERT INTO table (comp_prod, product_id) VALUES '.implode(',', $sql));*/



            $is_data = $this->orderinsertOrUpdate($update_data);

            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Order settings updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $order_details = (new OrderSetting($this->db))->OrderSettingfindByUserId(Session::get('auth_user_id'));
                return $this->view->buildResponse('inventory/settings/order', ['order_details' => $order_details]);
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
        $products = (new Product($this->db))->getActiveUserAll(Session::get('auth_user_id'), [1, 0]);
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('order/add', ['market_places' => $market_places, 'products' => $products]);
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
                'StoreProductId' => 'required',
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

            // check product availablity in stock
            $is_avail = $this->checkProductQty($form['StoreProductId']);

            if (!$is_avail['status'])
                throw new Exception("Sorry, Product is not available in stock...!", 301);

            $insert_data = $this->PrepareInsertData($form);
            $order_obj = new Order($this->db);
            $all_order = $order_obj->addOrder($insert_data);
            // Update Product Qty : Decrease product qty
            $prodUpdate['Qty'] = $is_avail['qty'] - 1;
            $prod_obj = new Product($this->db);
            $all_prod = $prod_obj->updateProdInventory($form['StoreProductId'], $prodUpdate);

            // Email Start
            $message['html']  = $this->view->make('emails/orderconfirm');
            $message['plain'] = $this->view->make('emails/plain/orderconfirm');
            $mailer = new Email();
            $mailer->sendEmail(
                $form['ShippingEmail'],
                Config::get('company_name'),
                _('Order Confirmation'),
                $message,
                ['OrderId' => $form['MarketPlaceOrder'], 'Carrier' => $form['CarrierOrder']]
            );
            // Email End
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

            $products = (new Product($this->db))->getActiveUserAll(Session::get('auth_user_id'), [1, 0]);
            $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
            return $this->view->buildResponse('order/add', ['market_places' => $market_places, 'products' => $products, 'form' => $form]);
        }
    }


    /*
         @author    :: Tejas
         @task_id   :: check product availablity
         @task_desc :: In stock product details
         @params    :: 
        */
    public function checkProductQty($product_id = "")
    {
        $res['status'] = false;
        $res['qty'] = 0;
        if (empty($product_id))
            return $res;

        $products = (new Product($this->db))->findById($product_id);
        if (isset($products) && !empty($products) && $products['Qty'] > 0) {
            $res['status'] = true;
            $res['qty'] = $products['Qty'];
            return $res;
        } else {
            return $res;
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
        $form_data['StoreProductId'] = (isset($form['StoreProductId']) && !empty($form['StoreProductId'])) ? $form['StoreProductId'] : null;
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

        $form_data['UserId'] = Session::get('auth_user_id');
        $form_data['StoreId'] = $this->storeid;
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
        $products = (new Product($this->db))->getActiveUserAll(Session::get('auth_user_id'), [1, 0]);
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $form, 'products' => $products, 'hidden_prod' => $form['StoreProductId']]);
        } else {
            $this->browse->flash([
                'alert' => 'Failed to fetch Order details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $form, 'products' => $products, 'hidden_prod' => $form['StoreProductId']]);
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
                'StoreProductId' => 'required',
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

            $update_prod = false;
            if (!empty($methodData['hidden_prod']) && $methodData['hidden_prod'] != $methodData['StoreProductId']) {
                $update_prod = true;
                // check product availablity in stock
                $is_avail = $this->checkProductQty($form['StoreProductId']);
                if (isset($is_avail['status']) && $is_avail['status'] == false)
                    throw new Exception("Sorry, Product is not available in stock...!", 301);
            }


            $update_data = $this->PrepareUpdateData($methodData);
            $Id = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : null;
            $is_updated = (new Order($this->db))->editOrder($Id, $update_data);

            if ($update_prod == true) {
                // Update Product Qty : Decrease product qty
                $prodUpdate['Qty'] = $is_avail['qty'] - 1;
                $prod_obj = new Product($this->db);
                $all_prod = $prod_obj->updateProdInventory($form['StoreProductId'], $prodUpdate);
            }

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
            $this->view->flash($validated);
            $products = (new Product($this->db))->getActiveUserAll(Session::get('auth_user_id'), [1, 0]);
            $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
            return $this->view->buildResponse('order/edit', ['market_places' => $market_places, 'form' => $methodData, 'products' => $products, 'hidden_prod' => $methodData['hidden_prod']]);
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
        $form_data['StoreId'] = $this->storeid;
        $form_data['Updated'] = date('Y-m-d H:i:s');
        return $form_data;
    }

    /*
    * searchOrder - Update Batch Move
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function searchOrder(ServerRequest $request)
    {

        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $result = (new Product($this->db))->searchProductFilter($methodData);

            if (isset($result) && !empty($result)) {
                $this->view->flash([
                    'alert' => 'Order result get successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->buildResponse('order/browse', ['all_order' => $result]);
            } else {
                throw new Exception("Search result not found...!", 301);
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
            return $this->view->buildResponse('order/browse', []);
        }
    }

    /*
    * updateOrderStatus - Update Batch Move
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateOrderStatus(ServerRequest $request)
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $map_data = $this->_mapOrderStatusUpdate($methodData);

            if (isset($map_data) && !empty($map_data)) {
                $this->view->flash([
                    'alert' => 'Order Status updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $res['status'] = true;
                $res['data'] = [];
                $res['message'] = 'Order Status updated successfully..!';
                die(json_encode($res));
                //return $this->view->buildResponse('order/browse', []);
            } else {
                throw new Exception("Order Status not updated...!", 301);
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
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Order Status not updated..!';
            die(json_encode($res));
            // return $this->view->buildResponse('order/browse', []);
        }
    }


    /*
    @author    :: Tejas
    @task_id   :: 
    @task_desc :: 
    @params    :: 
    */
    public function _mapOrderStatusUpdate($status_data = [])
    {
        foreach ($status_data['ids'] as $key_data => $value) {
            $update_result = (new Order($this->db))->editOrder($value, ['Status' => $status_data['status']]);
        } // Loops Ends
        return true;
    }


    /*
     @author    :: Tejas
     @task_id   :: 
     @task_desc :: 
     @params    :: 
    */
    public function updateOrderChange(ServerRequest $request)
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $map_data = (new Order($this->db))->getStatusOrders($methodData['OrderStatus']);
            if (isset($map_data) && !empty($map_data)) {
                $this->view->flash([
                    'alert' => 'Order Status get successfully..!',
                    'alert_type' => 'success'
                ]);
                return $this->view->buildResponse('order/browse', ['all_order' => $map_data]);
            } else {
                throw new Exception("Order result not get...!", 301);
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
            return $this->view->buildResponse('order/browse', []);
        }
    }



    /*
    * pickOrder - Update Batch Move
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function pickOrder()
    {
        return $this->view->buildResponse('order/pick', []);
    }
    /*
    * packingOrder - load packinglist view
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function packingOrder()
    {
        return $this->view->buildResponse('order/packingslip', []);
    }

    /*
    * mailingOrder - load mailinglist view
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function mailingOrder()
    {
        return $this->view->buildResponse('order/mailinglabel', []);
    }
}
