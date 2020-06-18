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

class ScheduleBackgroundJobs
{
  private $db;
  public function __construct(PDO $db)
  {
    $this->db   = $db;
    error_reporting(E_ALL ^ E_DEPRECATED);
    error_reporting(0);
  }

    public function orderBackgroundProcess()
    {
         $ftpHost      = 'ftp.valorebooks.com';
         $ftpUsername  = 'chrislands_348442';
         $ftpPassword  = 'F23MRTJ8';
       
          $file_list = array();

          // open an FTP connection

          $ftp_conn = ftp_connect($ftpHost) or die("Could not connect to $ftp_server");

          $login = ftp_login($ftp_conn, $ftpUsername, $ftpPassword);
         
          $file_list = ftp_nlist($ftp_conn, "/Order");

          if(!empty($file_list))
           {

             foreach($file_list as $value) {
              
              $remoteFile = $value;
         
              $newname = str_replace("/Order/",'', $remoteFile);
         
              ob_start();
              ftp_get($ftp_conn, 'php://output', $remoteFile, FTP_ASCII);
               
              $data = ob_get_contents();
              ob_end_clean();

              $file = fopen('C:/xamppnew/htdocs/tracksz/orderdata/'.$newname,"w");

              echo fwrite($file,$data);
          
              fclose($file);

              ftp_delete($ftp_conn, $remoteFile);
            }
            
            }

             $path = 'C:/xamppnew/htdocs/tracksz/orderdata';

              $fileArray =array();

              if ($handle = opendir($path)) {
                    while (false !== ($file = readdir($handle))) {
                      $ext = pathinfo($file, PATHINFO_EXTENSION);
                      if($ext=='csv' || $ext=='xlsx'){
                            $fileArray[] = $path."/".$file;
                      }
                    
                   }
                   closedir($handle);
                }

             if(!empty($fileArray))

              {   

              foreach($fileArray as $filepath){

               $ext = pathinfo($filepath, PATHINFO_EXTENSION);

               if($ext == 'csv')
               {
                 $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
               }
               else
               {
                 $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
               }

               $spreadsheet = $reader->load($filepath);

               $sheetData = $spreadsheet->getActiveSheet()->toArray();

              $headerOnly = (isset($sheetData) && is_array($sheetData) && !empty($sheetData['0'])) ? $sheetData['0'] : null;

              unset($sheetData[0]);

              foreach ($sheetData as  $value) {
               
               $insert_order = array(

                'MarketPlaceId'   =>$value[0],
                'OrderId'         =>$value[1],
                'StoreProductId'  =>$value[2],
                'Status'          =>$value[3],
                'Currency'        =>$value[4],
                'PaymentStatus'   =>$value[5],
                'PaymentMethod'   =>$value[6],
                'BuyerNote'       =>$value[7],
                'SellerNote'      =>$value[8],
                'ShippingMethod'  =>$value[9],
                'Tracking'        =>$value[10],
                'Carrier'         =>$value[11],
                'ShippingName'    =>$value[12],
                'ShippingPhone'   =>$value[13],
                'ShippingEmail'   =>$value[14],
                'ShippingAddress1'=>$value[15],
                'ShippingAddress2'=>$value[16],
                'ShippingAddress3'=>$value[17],
                'ShippingCity'    =>$value[18],
                'ShippingState'   =>$value[19],
                'ShippingZipCode' =>$value[20],
                'ShippingCountry' =>$value[21],
                'BillingName'     =>$value[22],
                'BillingPhone'    =>$value[23],
                'BillingEmail'    =>$value[24],
                'BillingAddress1' =>$value[25],
                'BillingAddress2' =>$value[26],
                'BillingAddress3' =>$value[27],
                'BillingCity'     =>$value[28],
                'BillingState'    =>$value[29],
                'BillingZipCode'  =>$value[30],
                'BillingCountry'  =>$value[31],
                'UserId'          =>$value[32],
                'StoreId'         =>$value[33],
                'Updated'         =>$value[34],
                'Created'         =>$value[35]
            ); 

               $order_id = $insert_order['OrderId'];

               $order_data = (new Order($this->db))->findByorder_id($order_id);

               if(empty($order_data))
               {   
                 $ins_data = (new Order($this->db))->insertdata_by_crone($insert_order);
               }

                unlink($filepath);
            
            }
           
        }


      }
       else
       {
         echo "No pending file in directory";
       }

         
        }
    }
   
        
         
  

  /*
   @author    :: Tejas
   @task_id   :: 
   @task_desc :: 
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
}
