<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Models\Inventory\Category;
use App\Models\Inventory\InventorySetting;
use App\Models\Product\Product;
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
use Delight\Cookie\Session;


class InventoryController
{
    private $view;
    private $db;
    public function __construct(Views $view, PDO $db)
    {

        $this->view = $view;
        $this->db   = $db;
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function uploadInventory()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/upload', ['market_places' => $market_places]);
    }

    /*
    * browseInventoryUpload - import inventory file and update inventory table
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function browseInventoryUpload()
    {


        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $headerOnly = (isset($sheetData) && is_array($sheetData) && !empty($sheetData['0'])) ? $sheetData['0'] : null;
            unset($sheetData[0]);
            $map_data = $this->mapFieldsAttributes("Chrislands.com", $headerOnly, $sheetData);

            $is_result = $this->insertOrUpdateInventory($map_data);
        } else { // UIEE Format

        }


        // ! yersterday code working
        // $file_stream = $_FILES['file']['tmp_name'];
        // $file_name = $_FILES['file']['name'];
        // $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
        // $publicDir = getcwd() . "/assets/inventory/upload/" . $file_encrypt_name . strstr($file_name, '.');
        // $cat_img = $file_encrypt_name . strstr($file_name, '.');
        // $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);
    }

    /*
    * insertOrUpdate - find user id if exist
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function insertOrUpdateInventory($data)
    {
        foreach ($data as $data_val) {
            if (isset($data_val['ProdId']) && !empty($data_val['ProdId'])) {
                $data_val['AddtionalData'] = json_encode($data_val['AddtionalData']);
                $data_val['UserId'] = Session::get('auth_user_id');
                $is_exist = (new Product($this->db))->findByUserProd(Session::get('auth_user_id'), $data_val['ProdId'], [0, 1]);
                $data = (isset($is_exist) && !empty($is_exist)) ? (new Product($this->db))->updateProdInventory($is_exist['Id'], $data_val) : (new Product($this->db))->addProdInventory($data_val);
            }
        }
        return true;
    }

    private function mapFieldsAttributes($marketPlaceName = "", $fileHeader = array(), $fileData = array())
    {
        if (empty($marketPlaceName))
            return false;

        $market_place_map = Config::get('market_place_map');
        $new_arr = [];
        $map_arr = [];
        foreach ($fileData as $file_key => $file_val) {
            for ($i = 0; $i < 35; $i++) {
                if (in_array($fileHeader[$i], array_keys($market_place_map[$marketPlaceName]))) { // *found                    
                    $map_arr[$file_key][$market_place_map[$marketPlaceName][$fileHeader[$i]]] = $fileData[$file_key][$i];
                } else { // ! not found
                    if (isset($market_place_map[$marketPlaceName]['AddtionalData'][$fileHeader[$i]]))
                        $map_arr[$file_key]['AddtionalData'][$market_place_map[$marketPlaceName]['AddtionalData'][$fileHeader[$i]]] = $fileData[$file_key][$i];
                }
            }
        }
        return $map_arr;
    }

    public function importInventoryFTP(ServerRequest $request)
    {
        $form = $request->getUploadedFiles();
        $form_2 = $request->getParsedBody();
        // $form2 = $request->getUploadedFiles($form['InventoryUpload']);
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        unset($form_2['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        try {

            $validate2 = new ValidateSanitize();
            $form_2 = $validate2->sanitize($form_2); // only trims & sanitizes strings (other filters available)
            $validate2->validation_rules(array(
                'MarketName'    => 'required'
            ));
            $validated = $validate2->run($form_2, true);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please Select Marketplace...!", 301);
            }

            if (isset($_FILES['InventoryUpload']['error']) && $_FILES['InventoryUpload']['error'] > 0) {
                throw new Exception("Please Upload Inventory file...!", 301);
            }

            $validator = new FilesSize([
                'min' => '1kB',  // minimum of 1kB
                'max' => '10MB', // maximum of 10MB
            ]);

            // if false than throw Size error 
            if (!$validator->isValid($_FILES)) {
                throw new Exception("File upload size is too large...!", 301);
            }
            // Using an options array:
            $validator2 = new Extension(['docs,jpg,xlsx,csv']);
            // if false than throw type error
            if (!$validator2->isValid($_FILES['InventoryUpload'])) {
                throw new Exception("Please upload valid file type docs, jpg and xlsx...!", 301);
            }

            $is_valid = $this->fileExtenstion($form);
            if (!$is_valid) {
                throw new Exception("Please upload file type as per Inventory Settings...!", 301);
            }
            $ftp_details = (new Marketplace($this->db))->findFtpDetails($form_2['MarketName'], Session::get('auth_user_id'), 1);

            if (is_array($ftp_details) && empty($ftp_details)) {
                throw new Exception("Ftp Details are not available in database...!", 301);
            }

            $ftp_connect = ftp_connect($ftp_details['FtpAddress']);
            $ftp_username = $ftp_details['FtpUserId'];
            $ftp_password = $ftp_details['FtpPassword'];
            if (false === $ftp_connect) {
                throw new Exception("FTP connection error!");
            }

            $ftp_login = ftp_login($ftp_connect, $ftp_username, $ftp_password);

            if (!$ftp_login)
                throw new Exception("Ftp Server connection fails...!", 400);

            $file_stream = $_FILES['InventoryUpload']['tmp_name'];
            $file_name = $_FILES['InventoryUpload']['name'];

            $is_file_upload = ftp_put($ftp_connect, 'Inventory/' . $file_name, $file_stream, FTP_ASCII);

            if (!$is_file_upload)
                throw new Exception("Ftp File upload fails...! Please try again", 651);

            $validated['alert'] = 'Inventory File is uploaded into FTP Server successully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/import');
        } catch (Exception $e) {
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Inventory File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/import');
        }
        ftp_close($ftp_connect);
        exit;
    }
    /*
    * updateInventory - Update inventory file via csv upload
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function updateCsvInventory(ServerRequest $request)
    {
        $form = $request->getUploadedFiles();
        $form_2 = $request->getParsedBody();
        // $form2 = $request->getUploadedFiles($form['InventoryUpload']);
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        unset($form_2['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        try {

            $validate2 = new ValidateSanitize();
            $form_2 = $validate2->sanitize($form_2); // only trims & sanitizes strings (other filters available)
            $validate2->validation_rules(array(
                'MarketName'    => 'required'
            ));
            $validated = $validate2->run($form_2, true);

            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please Select Marketplace...!", 301);
            }

            if (isset($_FILES['InventoryUpload']['error']) && $_FILES['InventoryUpload']['error'] > 0) {
                throw new Exception("Please Upload Inventory file...!", 301);
            }

            $validator = new FilesSize([
                'min' => '0kB',  // minimum of 1kB
                'max' => '10MB', // maximum of 10MB
            ]);

            // if false than throw Size error 
            if (!$validator->isValid($_FILES)) {
                throw new Exception("File upload size is too large...!", 301);
            }
            // Using an options array:
            $validator2 = new Extension(['csv']);
            // if false than throw type error
            if (!$validator2->isValid($_FILES['InventoryUpload'])) {
                throw new Exception("Please upload valid file type csv...!", 301);
            }
            $check_priority = $this->CheckPriorityUpdate();

            $ftp_details = (new Marketplace($this->db))->findFtpDetails($form_2['MarketName'], Session::get('auth_user_id'), 1);

            if (is_array($ftp_details) && empty($ftp_details)) {
                throw new Exception("Ftp Details are not available in database...!", 301);
            }

            $ftp_connect = ftp_connect($ftp_details['FtpAddress']);
            $ftp_username = $ftp_details['FtpUserId'];
            $ftp_password = $ftp_details['FtpPassword'];
            if (false === $ftp_connect) {
                throw new Exception("FTP connection error!");
            }

            $ftp_login = ftp_login($ftp_connect, $ftp_username, $ftp_password);

            if (!$ftp_login)
                throw new Exception("Ftp Server connection fails...!", 400);

            $file_stream = $_FILES['InventoryUpload']['tmp_name'];
            $file_name = $_FILES['InventoryUpload']['name'];

            $is_file_upload = ftp_put($ftp_connect, 'Inventory/' . $file_name, $file_stream, FTP_ASCII);

            if (!$is_file_upload)
                throw new Exception("Ftp File upload fails...! Please try again", 651);

            $validated['alert'] = 'Inventory File is uploaded into FTP Server successully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/update');
        } catch (Exception $e) {
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Inventory File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/update');
        }
        ftp_close($ftp_connect);
        exit;
    }

    /*
    * fileExtenstion - file extenstion logic
    * @param  - none
    * @return boolean
    */
    private function fileExtenstion($files)
    {
        $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
        if ($user_details['FileType'] == str_replace("/", "", strstr($files['InventoryUpload']->getClientMediaType(), "/")))
            return true;

        return false;
    }

    /*
    * CheckPriorityUpdate - Check Inventory Table IsUpdating Status
    * @param  - none
    * @return boolean
    */
    private function CheckPriorityUpdate()
    {
        return $this->view->buildResponse('inventory/view', []);
    }

    /*
    * view - Load inventory view file
    * @param  - none
    * @return view
    */
    public function view()
    {
        return $this->view->buildResponse('inventory/view', []);
    }



    /*
    * updateInventoryView - Load inventory update view file
    * @param  - none
    * @return view
    */
    public function updateInventoryView()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/inventory_update', ['market_places' => $market_places]);
    }

    /*
    * add - Load inventory add view file
    * @param  - none
    * @return view
    */
    public function add()
    {
        return $this->view->buildResponse('inventory/item', []);
    }
    public function defaults()
    {
        $settings = require __DIR__ . '/../../../config/inventory.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'),
            ['Defaults']
        );
        $defaults = json_decode($default['Defaults'], true);
        return $this->view->buildResponse('inventory/defaults', [
            'types'     => $types,
            'settings'  => $settings,
            'defaults'  => $defaults
        ]);
    }
    public function updateDefaults(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        if (!Cookie::exists('tracksz_active_store')) {
            $this->view->flash([
                'alert'     => _('Sorry we encountered an issue.  Please try again.'),
                'alert_type' => 'danger',
                'defaults'  => $form,
            ]);
            return $this->view->redirect('/inventory/defaults');
        }
        $updated = (new Store($this->db))->updateColumns(
            Cookie::get('tracksz_active_store'),
            ['Defaults' => json_encode($form)]
        );
        var_dump($updated);
        exit();
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function inventorySettingsBrowse()
    {
        $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
        return $this->view->buildResponse('inventory/settings/inventory', ['all_settings' => $user_details]);
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function repriceInventoryBrowse()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/defaults', ['market_places' => $market_places]);
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function advancedSettingsBrowse()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/defaults', ['market_places' => $market_places]);
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function exportInventoryBrowse()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/defaults', ['market_places' => $market_places]);
    }

    /*
    * uploadInventory - Upload inventory file via ftp
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function conditionPriceBrowse()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/defaults', ['market_places' => $market_places]);
    }

    /*
    * updateSettings - Update Inventory Settings
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateSettings(ServerRequest $request)
    {

        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data['UserId'] = Session::get('auth_user_id');
            $update_data['FileType'] = $methodData['FileName'];

            $is_data = $this->insertOrUpdate($update_data);

            if (isset($is_data) && !empty($is_data)) {
                $this->view->flash([
                    'alert' => 'Settings updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
                return $this->view->buildResponse('inventory/settings/inventory', ['all_settings' => $user_details]);
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
            $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
            return $this->view->buildResponse('inventory/settings/inventory', ['all_settings' => $user_details]);
        }
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
        $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
        if (isset($user_details) && !empty($user_details)) { // update
            $data['Updated'] = date('Y-m-d H:i:s');
            $result = (new InventorySetting($this->db))->editInventorySettings($data);
        } else { // insert
            $result = (new InventorySetting($this->db))->addInventorySettings($data);
        }

        return $result;
    }



    /*********** Save for Review - Delete if Not Used ************/
    /***********        Keep at End of File           ************/
    /*
    public function map_market_products(ServerRequest $request)
    {
        
        $form = $request->getParsedBody();
        $form_files = $request->getUploadedFiles();
        $files_handle = get_class_methods(get_class($form_files['productImage']));
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        $market_stores = Config::get('market_stores');
        $market_attributes = Config::get('market_attributes');
        
        
        if(is_array($market_stores) && (count($market_stores)>0 || !empty($market_stores))) {
            foreach($market_stores as $store_value ) {
                $market_wise_data = $this->map_market_attributes($form,$market_attributes,$store_value);
                echo '<pre>';
                print_r($market_wise_data);
                echo '</pre>';
                
            } // Loops Ends
        }
        
        die('result ends');
    }
    */
    /*
     @author    :: Tejas
     @task_id   :: product attributes map
     @task_desc :: product attributes mapping for different market stores
     @params    :: product details array
    */
    /* public function map_market_attributes($product_details = array(), $predefined_attr = array(), $store_name = ''){
        $res['status'] = false;
        $res['data'] = null;
        $res['store'] = $store_name;
        $res['message'] = 'Market attributes is not set..!';
        if((is_array($product_details) && is_array($predefined_attr)) &&
            (!empty($product_details) && !empty($predefined_attr) &&
                !empty($store_name))){
            $set_attr = array();
            $set_mrg_attr = array();
            foreach($product_details as $prod_key=>$prod_val) {
                $set_attr[$predefined_attr[$prod_key][$store_name]] = $prod_val;
            } // Loops Ends
            $res['status'] = true;
            $res['data'] = $set_attr;
            $res['message'] = 'Market attributes is set successfully..!';
        }
        return $res;
    } */
    /*********** End Save for Review Delete ************/
}
