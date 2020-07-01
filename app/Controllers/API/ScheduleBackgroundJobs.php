<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use PDO;
use Excel;
use App\Library\Config;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriteXlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriteCsv;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Delight\Cookie\Session;
use Exception;
use Laminas\Diactoros\ServerRequest;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use App\Models\Inventory\Inventory;
use App\Models\Inventory\InventorySetting;
use App\Models\Marketplace\Marketplace;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use Laminas\Log\Formatter\Json;
use App\Library\Email;
use App\Library\Views;

class ScheduleBackgroundJobs
{
  private $view;
  private $db;
  public function __construct(Views $view, PDO $db)
  {
    $this->db   = $db;
    $this->view = $view;
    error_reporting(E_ALL ^ E_DEPRECATED);
    error_reporting(0);
  }

  /*
   @author    :: Mukesh 
   @task_id   :: Background Job
   @task_desc :: ftp files upload for all market place
   @params    :: none
   @return    :: json data
  */
  public function orderBackgroundProcess()
  {
    $ftpHost      = 'ftp.valorebooks.com';
    $ftpUsername  = 'chrislands_348442';
    $ftpPassword  = 'F23MRTJ8';

    $file_list = array();
    $count_fail = array();
    $count_success = array();
    $new_path = getcwd() . '/assets/order/';
    $login = ftp_login($ftp_connect, $ftp_username, $ftp_password);
    $file_list = ftp_nlist($ftp_connect, "/Order");
    if (!empty($file_list)) {
      foreach ($file_list as $value) {
        $remoteFile = $value;
        $newname = str_replace("/Order/", '', $remoteFile);
        ob_start();
        ftp_get($ftp_connect, 'php://output', $remoteFile, FTP_ASCII);
        $data = ob_get_contents();
        ob_end_clean();
        $file = fopen($new_path . 'orderimport/' . $newname, "w");
        echo fwrite($file, $data);
        fclose($file);
        ftp_delete($ftp_connect, $remoteFile); // Delete file on server 
      }
    }
  
   $path = $new_path . 'orderimport';  //  directory's path
    $fileArray = array();
    if ($handle = opendir($path)) {
      while (false !== ($file = readdir($handle))) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == 'csv' || $ext == 'xlsx') {
          $fileArray[] = $path . "/" . $file;
        }
      }
      closedir($handle);
    }

    if (!empty($fileArray)) {
      foreach ($fileArray as $filepath) {
        $ext = pathinfo($filepath, PATHINFO_EXTENSION);
        if ($ext == 'csv') {
          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
          $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($filepath);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $headerOnly = (isset($sheetData) && is_array($sheetData) && !empty($sheetData['0'])) ? $sheetData['0'] : null;
        unset($sheetData[0]);

        foreach ($sheetData as  $value) {
          $count = count($headerOnly);
          for ($i = 0; $i < $count; $i++) {
            if ($i == 2) {
              $insert_order['StoreProductId'] = '';
            } else if ($i == 1) {
              unset($insert_order['MarketPlaceOrder']);
            } else if ($i == 3) {
              $insert_order['Status'] = '';
            } else if ($i == 0) {
              unset($insert_order['MarketPlaceName']);
            } else {
              $insert_order[$headerOnly[$i]] = $value[$i];
            }
          }

          $order_id        = $value[1];
          $product_id      = $value[2];
          $MarketPlaceName = $value[0];
          $marketplace_id   =  (new Order($this->db))->get_marketplace_id($MarketPlaceName);
          $market_id =  $marketplace_id['Id'];
          $qty = $value[3];
          $order_data = (new Order($this->db))->findByorder_id($order_id);
          $insert_order['OrderId']       = $value[1];
          $insert_order['MarketPlaceId'] = $market_id;
          $insert_order['UserId']        = Session::get('auth_user_id');
          $insert_order['StoreId']       = $this->storeid;
          $insert_order['Updated']       = date('Y-m-d H:I:s');
          $insert_order['Created']       = date('Y-m-d H:I:s');

          if (empty($order_data)) {
            $order_qty = (new Order($this->db))->select_qty_by_product_id($product_id);
            if ($order_qty['Qty'] > $qty) {
              $total_qty = 0;
              $update_id = $order_qty['Id'];
              $total_qty = $order_qty['Qty'] - $qty;
              $update_qty = (new Order($this->db))->update_qty_by_product_id($total_qty, $update_id);
              $ins_data = (new Order($this->db))->insert_data_by_crone($insert_order);
              $count_success[] = $order_id;
            }
          } else {
            $count_fail[] = $order_id;
          }
          // unlink($filepath);
        }
      }
    } else {
      echo "No pending file in directory";
    }
    $res['status'] = true;
    $res['message'] = 'Orders are exceuted successfully';
    $res['count_order_success'] = $count_success;
    $res['count_order_fail'] = $count_fail;
    die(json_encode($res));
  }
}


  /*
   @author    :: Tejas
   @task_id   :: 
   @task_desc :: ftp files upload for background jobs
   @params    :: 
   @return    :: 
  */
  public function ftpUploadBackgroundProcess()
  {
    try {
      $file_path = getcwd() . '/assets/inventory/ftpupload/';
      $files = scandir($file_path);

      if (isset($files) && !empty($files)) {
        foreach ($files as $key_data => $val_data) {
          if ($key_data == 0 || $key_data == 1)
            continue;

          if (preg_match('_ftpdetail_', $val_data)) {

            $file = fopen($file_path . "/" . $val_data, "r");

            $readFile = array();
            while (!feof($file)) {
              $readFile[] = fgets($file);
            }

            $connect = str_replace(':', '', strstr($readFile[0], ':', false));
            $user = str_replace(':', '', strstr($readFile[1], ':', false));
            $pwd = str_replace(':', '', strstr($readFile[2], ':', false));
            $ext = str_replace(':', '', strstr($readFile[3], ':', false));
            $file_name = trim(str_replace(':', '', strstr($readFile[4], ':', false)));

            $ftp_connect = ftp_connect(trim($connect));
            $ftp_username = trim($user);
            $ftp_password = trim($pwd);
            if ($ftp_connect) {
              $ftp_login = ftp_login($ftp_connect, $ftp_username, $ftp_password);

              if (!$ftp_login) {
                $file_error_lists[] = 456;
              } else {
                $is_file_upload = ftp_put($ftp_connect, 'Inventory/' . $file_name, $file_path . "$file_name", FTP_ASCII);
                if (!$is_file_upload) {
                  $file_error_lists[] = 8910;
                } else {
                  unlink($file_path . "$file_name");
                  unlink($file_path . "$val_data");
                }
              }
            } else {
              $file_error_lists[] = 123;
            }
          }
        } // Loops Ends
      }
      $res['status'] = true;
      $res['message'] = 'Result found successfully...!';
      $res['data'] = array('data');
      die(json_encode($res));
    } catch (Exception $ex) {

      $error_msg = 'ErrorFile -> ' . $ex->getFile() . '</br> :: ErrorLine -> ' . $ex->getLine() . '
   </br>:: ErrorCode -> ' . $ex->getCode() . '</br> :: ErrorMsg -> ' . $ex->getMessage();
      $res['status'] = false;
      $res['message'] = $error_msg;
      $res['data'] = null;
      die(json_encode($res));
    }
  }

  /*
     @author    :: Tejas
     @task_id   :: browser upload with background job
     @task_desc :: 
     @params    :: 
    */
  public function browseInventoryBackgroundUpload()
  {

    $file_path = getcwd() . '/assets/inventory/browserupload/';
    $files = scandir($file_path);
    $request_name = "";
    $request_type = "";

    if (isset($files) && !empty($files)) {
      foreach ($files as $key_data => $val_data) {
        if ($key_data == 0 || $key_data == 1)
          continue;

        if (preg_match('_browserdetail_', $val_data)) {
          $file = fopen($file_path . "/" . $val_data, "r");
          $readFile = array();
          while (!feof($file)) {
            $readFile[] = fgets($file);
          }
          $request_type = trim(str_replace(':', '', strstr($readFile[0], ':', false)));
          $request_name = trim(str_replace(':', '', strstr($readFile[1], ':', false)));
          $file_name = trim(str_replace(':', '', strstr($readFile[2], ':', false)));
        }
      } // Loops Ends
    }

    try {
      $user_details = (new InventorySetting($this->db))->findByUserId(Session::get('auth_user_id'));

      if ($request_name == 'UIEEFile') {

        $ext = pathinfo($file_path . "/" . $file_name, PATHINFO_EXTENSION);
        if ($ext != 'txt') {
          throw new Exception("Please upload valid file type based on request formate...!", 301);
        }
        $file = fopen($file_path . $file_name, "r");
        $uiee_arr = array();
        while (!feof($file)) {
          $uiee_arr[] = fgets($file);
        }

        $map_data = $this->mapUIEEFieldsAttributes("UIEEFile", $uiee_arr);
        if ($request_type == 'pricing_import') {
          $is_result = $this->insertOrUpdateprice($map_data);
        } elseif ($request_type == 'purge') {
          $is_result = $this->insertOrUpdateInventorywithdelete($map_data);
        } else {
          $is_result = $this->insertOrUpdateInventory($map_data);
        }

        $temo = 'Files for Inventory Import successfully upload..!';
        $email_file = $this->_LogGenerator($map_data);
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
        $validated['alert'] = 'Files for Inventory Import successfully upload..!';
        $validated['alert_type'] = 'success';
        // $this->view->flash($validated);
        // return $this->view->redirect('/inventory/import');
        die(json_encode(['message' => $temo, 'status' => true]));
      }

      if ($request_name == 'HomeBase2File') {


        $ext = pathinfo($file_path . "/" . $file_name, PATHINFO_EXTENSION);
        if ($ext != 'txt') {
          throw new Exception("Please upload valid file type based on request formate...!", 301);
        }
        $file = fopen($file_path . $file_name, "r");
        $uiee_arr = array();
        while (!feof($file)) {
          $uiee_arr[] = fgets($file);
        }
        fclose($file);
        $map_data = $this->mapUIEEFieldsAttributes("HomeBase2File", $uiee_arr);

        if ($request_type == 'pricing_import') {
          $is_result = $this->insertOrUpdateprice($map_data);
        } elseif ($request_type == 'purge') {
          $is_result = $this->insertOrUpdateInventorywithdelete($map_data);
        } else {
          $is_result = $this->insertOrUpdateInventory($map_data);
        }

        $temo = 'Files for Inventory Import successfully upload..!';
        $email_file = $this->_LogGenerator($map_data);
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
        $validated['alert'] = 'Files for Inventory Import successfully upload..!';
        $validated['alert_type'] = 'success';
        $this->view->flash($validated);
        // return $this->view->redirect('/inventory/import');
        die(json_encode(['message' => $temo, 'status' => true]));
      }


      if ($request_name == 'Chrislands.com') {


        $ext = pathinfo($file_path . "/" . $file_name, PATHINFO_EXTENSION);
        if ($ext == 'csv' || $ext == 'xls' || $ext == 'xlsx') {

          if ('csv' == $ext) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
          } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          }

          $spreadsheet = $reader->load($file_path . $file_name);
          $sheetData = $spreadsheet->getActiveSheet()->toArray();
          $headerOnly = (isset($sheetData) && is_array($sheetData) && !empty($sheetData['0'])) ? $sheetData['0'] : null;
          unset($sheetData[0]);
          $map_data = $this->mapFieldsAttributes("Chrislands.com", $headerOnly, $sheetData);

          if ($request_type == 'pricing_import') {
            $is_result = $this->insertOrUpdateprice($map_data);
          } elseif ($request_type == 'purge') {
            $is_result = $this->insertOrUpdateInventorywithdelete($map_data);
          } else {
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
          //     // Email End
          $validated['alert'] = 'Files for Inventory Import successfully upload..!';
          $validated['alert_type'] = 'success';
          die(json_encode(['message' => $temo, 'status' => true]));
        } else {
          throw new Exception("Please upload valid file type based on request formate...!", 301);
        }
      }
    } catch (Exception $e) {
      $res['status'] = false;
      $res['data'] = [];
      $res['message'] = 'Inventory File not uploaded into server...!';
      $res['ex_message'] = $e->getMessage();
      $res['ex_code'] = $e->getCode();
      $res['ex_file'] = $e->getFile();
      $res['ex_line'] = $e->getLine();

      $validated['alert'] = 'Inventory File not uploaded into server...!';
      $validated['alert_type'] = 'danger';
      // $this->view->flash($validated);
      // return $this->view->redirect('/inventory/import');
      die(json_encode($res));
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

  public function insertOrUpdateInventorywithdelete($data)
  {
    foreach ($data as $data_val) {
      if (isset($data_val['ProdId']) && !empty($data_val['ProdId'])) {
        $data_val['AddtionalData'] = json_encode($data_val['AddtionalData']);
        $data_val['UserId'] = Session::get('auth_user_id');
        $is_exist = (new Product($this->db))->findByUserProd(Session::get('auth_user_id'), $data_val['ProdId'], [0, 1]);
        $data = (isset($is_exist) && !empty($is_exist)) ? (new Product($this->db))->updateProdInventorywithdelete($is_exist['Id'], $data_val) : (new Product($this->db))->addProdInventorywithdelete($data_val);
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

  public function insertOrUpdateprice($data)
  {
    foreach ($data as $data_val) {
      if (isset($data_val['ProdId']) && !empty($data_val['ProdId'])) {
        $data_val['AddtionalData'] = json_encode($data_val['AddtionalData']);
        $data_val['UserId'] = Session::get('auth_user_id');
        //echo "<pre>";print_r($data_val);exit;
        $is_exist = (new Product($this->db))->findByUserProd(Session::get('auth_user_id'), $data_val['ProdId'], [0, 1]);
        $data = (isset($is_exist) && !empty($is_exist)) ? (new Product($this->db))->updateProdInventoryPrice($is_exist['Id'], $data_val) : (new Product($this->db))->addProdInventory($data_val);
      }
    }
    return true;
  }

  /*
   @author    :: Tejas
   @task_id   :: map uiee fields attributes
   @task_desc :: 
   @params    :: 
  */
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


  /*
   @author    :: Tejas
   @task_id   :: 
   @task_desc :: browse files remove for background jobs
   @params    :: 
   @return    :: 
  */
  public function browseInventoryBackgroundRemove()
  {
    try {
      $file_path = getcwd() . '/assets/order/orderimport/';
      $files = scandir($file_path);
      if (isset($files) && !empty($files)) {
        foreach ($files as $key_data => $val_data) {
          if ($key_data == 0 || $key_data == 1)
            continue;
          $now = time(); // or your date as well
          $your_date = strtotime(date("d-m-Y", filemtime($file_path . $val_data)));
          $datediff = $now - $your_date;
          die(round($datediff / (60 * 60 * 24)));
          if (round($datediff / (60 * 60 * 24)) > 20) {
            unlink($file_path . $val_data);
          }
        } // Loops Ends
      }
      $res['status'] = true;
      $res['message'] = 'Result found successfully...!';
      $res['data'] = array('data');
      die(json_encode($res));
    } catch (Exception $ex) {
      $error_msg = 'ErrorFile -> ' . $ex->getFile() . '</br> :: ErrorLine -> ' . $ex->getLine() . '
   </br>:: ErrorCode -> ' . $ex->getCode() . '</br> :: ErrorMsg -> ' . $ex->getMessage();
      $res['status'] = false;
      $res['message'] = $error_msg;
      $res['data'] = null;
      die(json_encode($res));
    }
  }
}
