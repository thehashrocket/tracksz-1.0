<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Models\Inventory\Category;
use App\Models\Inventory\Inventory;
use App\Models\Inventory\InventorySetting;
use App\Models\Inventory\OrderSetting;
use App\Models\Product\Product;
use App\Models\Marketplace\Marketplace;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use Exception;
use PDO;
use Excel;
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
// use App\Controllers\Inventory\TestJob1 as TestJob1;
// use \SidekiqJob\Client as SidekiqJobClient;
// use \Predis\Client as PredisClient;
// use App\Models\TestJob as TestJob;
// use App\Models\TestJob1 as TestJob1;

// require 'TestJob1.php';



class InventoryController
{
    private $view;
    private $db;
    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db   = $db;

        // // connect to database 0 on 127.0.0.1
        // $redis = new PredisClient('tcp://127.0.0.1:6379/0');

        // // Instantiate a new client
        // $client = new SidekiqJobClient($redis);

        // // push a job with three arguments - args array needs to be sequential (not associative)
        // $args = [
        //     ['url' => 'http://i.imgur.com/hlAsa4k.jpg'],
        //     true,
        //     70
        // ];

        // $id = $client->push('ProcessImage', $args, true);
        // $this->_print($id, true);

        // $id = $client->push('ProcessImage', $args, false);
        // $this->_print($id, false);
        // exit;
    }

    public function _print($id, $retry)
    {
        var_dump(sprintf('Pushed job with id %s and retry:%d', $id, $retry));
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
        try {
            $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));
            $mime_settings = ['uiee' => 'txt', 'csv' => 'csv', 'xlsx' => 'xlsx'];
            if (isset($mime_settings[$user_details['FileType']])) {
                if ($mime_settings[$user_details['FileType']] != $user_details['FileType'] && $user_details['FileType'] != 'uiee')
                    throw new Exception("Files for Inventory Import are supported as per Inventory Settings...!", 301);

                if ($user_details['FileType'] == 'uiee' && strstr($_FILES['file']['name'], ".", false) != ".txt")
                    throw new Exception("Files for Inventory Import are supported as per Inventory Settings...!", 301);

                $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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
                    $temo = 'Files for Inventory Import successfully upload..!';
                    $email_file = $this->_LogGenerator($map_data);
                    // Email Start
                    $message['html']  = $this->view->make('emails/inventoryupdate');
                    $message['plain'] = $this->view->make('emails/plain/inventoryupdate');
                    $mailer = new Email();
                    $mailer->sendEmailAttachment(
                        Session::get('auth_email'),
                        Config::get('company_name'),
                        _('Inventory Update Details'),
                        $message,
                        ['path' =>  getcwd() . "\logs\\$email_file", 'name' => $email_file, 'encoding' => 'base64', 'type' => 'application/json']
                    );
                    // Email End
                    die(json_encode(['message' => $temo, 'status' => true]));
                } else if ($user_details['FileType'] == 'uiee') { // UIEE Format

                    $file = fopen($_FILES['file']['tmp_name'], "r");
                    $uiee_arr = array();
                    while (!feof($file)) {
                        $uiee_arr[] = fgets($file);
                    }
                    fclose($file);

                    // UIEEFile , HomeBase2File
                    $marketplace_name = "UIEEFile";
                    if ($marketplace_name == "UIEEFile") {
                        // Background Start
                        // $do_jobs['marketplace'] = "UIEEFile";
                        // $do_jobs['data'] = $uiee_arr;
                        // $do_jobs['market_place_map'] = Config::get('market_place_map');
                        // $do_jobs['UserId'] = Session::get('auth_user_id');
                        // $do_jobs['PDO'] = $this->db;
                        // $this->_doBackgroundJobs($do_jobs);
                        // Background Ends 

                        $map_data = $this->mapUIEEFieldsAttributes("UIEEFile", $uiee_arr);
                        $is_result = $this->insertOrUpdateInventory($map_data);
                    } else if ($marketplace_name == "HomeBase2File") {
                        $map_data = $this->mapUIEEFieldsAttributes("HomeBase2File", $uiee_arr);
                        $is_result = $this->insertOrUpdateInventory($map_data);
                    }
                    $temo = 'Files for Inventory Import successfully upload..!';
                    $email_file = $this->_LogGenerator($map_data);
                    // Email Start
                    $message['html']  = $this->view->make('emails/inventoryupdate');
                    $message['plain'] = $this->view->make('emails/plain/inventoryupdate');
                    $mailer = new Email();
                    $mailer->sendEmailAttachment(
                        Session::get('auth_email'),
                        Config::get('company_name'),
                        _('Inventory Update Details'),
                        $message,
                        ['path' =>  getcwd() . "\logs\\$email_file", 'name' => $email_file, 'encoding' => 'base64', 'type' => 'application/json']
                    );
                    // Email End
                    die(json_encode(['message' => $temo, 'status' => true]));
                } else {
                    throw new Exception("Files for Inventory Import are supported as per Inventory Settings...!", 301);
                }
            } else {
                throw new Exception("Files for Inventory Import are supported as per Inventory Settings...!", 301);
            }
        } catch (Exception $e) {
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Inventory File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();
            die(json_encode($res));
        }
    }

    /*
      @author    :: Tejas
      @task_id   :: 
      @task_desc :: 
      @params    :: 
    */
    private function _doBackgroundJobs($jobs_data = array())
    {
        try {
            if (!count($jobs_data))
                throw new Exception('Data is Empty', 401);

            Resque::setBackend('127.0.0.1:6379');
            Resque::enqueue('default', 'App\Controllers\Inventory\TestJob1', $jobs_data);
            // 'App\Models\TestJob'
            // App\Controllers\Inventory\TestJob1
            $res['status'] = true;
            $res['message'] = 'Result found successfully...!';
            $res['data'] = array('data');
            return $res;
        } catch (Exception $ex) {
            $error_msg = 'ErrorFile -> ' . $ex->getFile() . '</br> :: ErrorLine -> ' . $ex->getLine() . '
                 </br>:: ErrorCode -> ' . $ex->getCode() . '</br> :: ErrorMsg -> ' . $ex->getMessage();
            $res['status'] = false;
            $res['message'] = $error_msg;
            $res['data'] = null;
            return $res;
        }
    }

    /*
     @author    :: Tejas
     @task_id   :: generate log of inventory files
     @task_desc :: generate log of inventory files data and time wise
     @params    :: inventory file data
    */
    public function _LogGenerator($log_data = [])
    {
        $log_json = "log_" . date('Y_m_d_his') . ".json";
        $stream = @fopen("logs/$log_json", 'a', false);
        if (!$stream) {
            throw new Exception('Failed to open stream');
        }

        $writer = new Stream($stream);
        $formatter = new Json();
        $writer->setFormatter($formatter);
        $logger = new Logger();
        $logger->addWriter($writer);
        $logger->info(json_encode($log_data));
        return $log_json;
    }

    /*
    * browseInventoryDelete - import inventory file and update inventory table
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function browseInventoryDelete()
    {
        try {
            $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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

                if (empty($sheetData))
                    throw new Exception("Please upload File with SKU Details...!", 301);

                $map_data = $this->mapRemoveFieldsAttributes($sheetData);
                $is_result = $this->deleteImportInventory($map_data);
                $email_file = $this->_LogGenerator($map_data);
                // Email Start
                $message['html']  = $this->view->make('emails/inventorydelete');
                $message['plain'] = $this->view->make('emails/plain/inventorydelete');
                $mailer = new Email();
                $mailer->sendEmailAttachment(
                    Session::get('auth_email'),
                    Config::get('company_name'),
                    _('Inventory Update Details'),
                    $message,
                    ['path' =>  getcwd() . "\logs\\$email_file", 'name' => $email_file, 'encoding' => 'base64', 'type' => 'application/json']
                );
                // Email End
                die(json_encode(['message' => 'Inventory Data are Deleted Successfully..!', 'status' => true]));
            } else {
                throw new Exception("Files for Inventory Import are supported as per Inventory Settings...!", 301);
            }
        } catch (Exception $e) {

            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Inventory File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            die(json_encode(['message' => 'Inventory Data are Not Deleted, Please try again..!', 'status' => false]));
        }
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

    /*
    * deleteImportInventory - find sku if exist
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function deleteImportInventory($data)
    {
        foreach ($data as $data_val) {
            if (isset($data_val['SKU']) && !empty($data_val['SKU'])) {
                $is_exist = (new Product($this->db))->findBySKUProd($data_val['SKU'], [0, 1]);
                if (isset($is_exist) && !empty($is_exist)) {
                    (new Product($this->db))->delete($is_exist['Id']);
                }
            }
        }
        return true;
    }

    private function mapRemoveFieldsAttributes($fileData = array())
    {
        array_walk($fileData, function (&$item) {
            $item['SKU'] = $item[0];
            unset($item[0]);
        });
        return $fileData;
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

    private function mapUIEEFieldsAttributes($marketPlaceName = "", $fileData = array())
    {
        if (empty($marketPlaceName))
            return false;

        $map_uiee = [];
        $map_uiee_set = [];
        $key_counter = 0;
        $market_place_map = Config::get('market_place_map');
        foreach ($fileData as $file_key => $file_val) {
            if (is_string($file_val) && empty(trim($file_val))) {
                $key_counter = $key_counter + 1;
                continue;
            }

            $uiee_key = strstr($file_val, "|", true);
            if (!empty($uiee_key)) {
                $arr_file = explode('|', $file_val);
                if (in_array($uiee_key, array_keys($market_place_map[$marketPlaceName]))) { // *found           
                    $map_uiee[$key_counter][$market_place_map[$marketPlaceName][$uiee_key]] = end($arr_file);
                } else { // ! not found
                    if (isset($market_place_map[$marketPlaceName]['AddtionalData'][$uiee_key]))
                        $map_uiee[$key_counter]['AddtionalData'][$market_place_map[$marketPlaceName]['AddtionalData'][$uiee_key]] = end($arr_file);
                }
            }
        }
        return $map_uiee;
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
                'min' => '0kB',  // minimum of 1kB
                'max' => '10MB', // maximum of 10MB
            ]);

            // if false than throw Size error 
            if (!$validator->isValid($_FILES)) {
                throw new Exception("File upload size is too large...!", 301);
            }
            // Using an options array:
            $validator2 = new Extension(['xlsx,csv,txt']);
            // if false than throw type error
            if (!$validator2->isValid($_FILES['InventoryUpload'])) {
                throw new Exception("Please upload valid file type csv, txt and xlsx...!", 301);
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

        $mime_settings = ['uiee' => 'txt', 'csv' => 'csv', 'xlsx' => 'xlsx'];
        if (isset($mime_settings[$user_details['FileType']])) {

            if ($mime_settings[$user_details['FileType']] != $user_details['FileType'] && $user_details['FileType'] != 'uiee')
                return false;

            if ($user_details['FileType'] == 'uiee' && strstr($files['InventoryUpload']->getclientFilename(), ".", false) != ".txt")
                return false;

            if ($user_details['FileType'] == 'csv' && strstr($files['InventoryUpload']->getclientFilename(), ".", false) != ".csv")
                return false;

            if ($user_details['FileType'] == 'xlsx' && strstr($files['InventoryUpload']->getclientFilename(), ".", false) != ".xlsx")
                return false;

            return true;
        } else {
            return false;
        }
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
        return $this->view->buildResponse('inventory/inventory_export', ['market_places' => $market_places]);
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

    public function exportInventoryData(ServerRequest $request)
    {
        try {
            $form = $request->getParsedBody();
            $export_type = $form['export_format'];

            $product_data = (new Inventory($this->db))->getAll();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Notes');
            $sheet->setCellValue('C1', 'SKU');
            $sheet->setCellValue('D1', 'ProdId');
            $sheet->setCellValue('E1', 'BasePrice');
            $sheet->setCellValue('F1', 'ProdCondition');
            $sheet->setCellValue('G1', 'ProdActive');
            $sheet->setCellValue('H1', 'InternationalShip');
            $sheet->setCellValue('I1', 'ExpectedShip');
            $sheet->setCellValue('J1', 'EbayTitle');
            $sheet->setCellValue('K1', 'Qty');
            $sheet->setCellValue('L1', 'Image');
            $sheet->setCellValue('M1', 'CategoryId');
            $sheet->setCellValue('N1', 'Status');
            $sheet->setCellValue('O1', 'UserId');
            $sheet->setCellValue('P1', 'AddtionalData');
            $rows = 2;
            foreach ($product_data as $product) {
                $sheet->setCellValue('A' . $rows, $product['Name']);
                $sheet->setCellValue('B' . $rows, $product['Notes']);
                $sheet->setCellValue('C' . $rows, $product['SKU']);
                $sheet->setCellValue('D' . $rows, $product['ProdId']);
                $sheet->setCellValue('E' . $rows, $product['BasePrice']);
                $sheet->setCellValue('F' . $rows, $product['ProdCondition']);
                $sheet->setCellValue('G' . $rows, $product['ProdActive']);
                $sheet->setCellValue('H' . $rows, $product['InternationalShip']);
                $sheet->setCellValue('I' . $rows, $product['ExpectedShip']);
                $sheet->setCellValue('J' . $rows, $product['EbayTitle']);
                $sheet->setCellValue('K' . $rows, $product['Qty']);
                $sheet->setCellValue('L' . $rows, $product['Image']);
                $sheet->setCellValue('M' . $rows, $product['CategoryId']);
                $sheet->setCellValue('N' . $rows, $product['Status']);
                $sheet->setCellValue('O' . $rows, $product['UserId']);
                $sheet->setCellValue('P' . $rows, $product['AddtionalData']);
                $rows++;
            }

            if ($export_type == 'xlsx' || $export_type == 'csv') {
                $this->view->flash([
                    'alert' => 'Inventory file sucessfully export..!',
                    'alert_type' => 'success'
                ]);

                if ($export_type == 'xlsx') {
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="inventory.xlsx"');
                    $writer->save("php://output");
                    exit;
                } else if ($export_type == 'csv') {
                    $writer = new WriteCsv($spreadsheet);
                    header('Content-Type: application/csv');
                    header('Content-Disposition: attachment; filename="inventory.csv"');
                    $writer->save("php://output");
                    exit;
                }
            } else {
                throw new Exception("Failed to update Settings. Please ensure all input is filled out correctly.", 301);
                /*$this->view->flash([
                    'alert' => 'Please Select Xlsx or Csv File Format..!',
                    'alert_type' => 'danger'
                ]);*/
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
            return $this->view->redirect('/inventory/export');
        }
        /*if($export_type == 'xlsx')
               {
                     $writer = new WriteXlsx($spreadsheet);
                     $writer->save("inventory.".$export_type);
                     return $this->view->redirect('/inventory/export');
               }
               else if($export_type == 'csv')
               {
                    $writer = new WriteCsv($spreadsheet);
                     $writer->save("inventory.".$export_type);
                     return $this->view->redirect('/inventory/export');
                }*/
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
