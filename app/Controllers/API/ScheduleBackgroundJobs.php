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


     /*
         @author    :: Mukesh 
         @task_id   :: 
         @task_desc :: Background Jobs
         @params    :: 
     */

    public function orderBackgroundProcess()
    {
         $ftpHost      = 'ftp.valorebooks.com';
         $ftpUsername  = 'chrislands_348442';
         $ftpPassword  = 'F23MRTJ8';
       
         $file_list = array();
         $count_fail = array();
         $count_success = array();

          $dir_path = getcwd(); // Path variable 

          $new_path = rtrim($dir_path,'public');

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

              $file = fopen($new_path.'orderdata/'.$newname,"w");

              echo fwrite($file,$data);
          
              fclose($file);

              //ftp_delete($ftp_conn, $remoteFile); // Delete file on server 
            }
            
            }

             $path = $new_path.'orderdata';  //  directory's path

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

             if(!empty($fileArray)){   

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
                     
                      $count = count($headerOnly);

                      for($i=0;$i<$count;$i++)
                      {
                        if($i==2)
                        {
                          $insert_order['StoreProductId'] = '';
                        }
                         else if($i==1)
                        {
                          unset($insert_order['MarketPlaceOrder']);
                        }
                        else if($i==3)
                        {
                          $insert_order['Status'] = '';
                        }
                         else if($i==0)
                        {
                          unset($insert_order['MarketPlaceName']);
                        }
                        else
                        {
                          $insert_order[$headerOnly[$i]] = $value[$i];
                        }
                      }
                      

                      $order_id        = $value[1];
                      $product_id      = $value[2];
                      $MarketPlaceName = $value[0];

                      $marketplace_id   =  (new Order($this->db))->get_marketplace_id($MarketPlaceName);

                      $market_id =  $marketplace_id ['Id'];

                      $qty = $value[3];

                      $order_data = (new Order($this->db))->findByorder_id($order_id);

                      $insert_order['OrderId']       = $value[1];
                      $insert_order['MarketPlaceId'] = $market_id;
                      $insert_order['UserId']        = Session::get('auth_user_id');
                      $insert_order['StoreId']       = $this->storeid;
                      $insert_order['Updated']       = date('Y-m-d H:I:s');
                      $insert_order['Created']       = date('Y-m-d H:I:s');

                     if(empty($order_data))
                      { 
                       
                        $order_qty = (new Order($this->db))->select_qty_by_product_id($product_id);

                         if($order_qty['Qty'] > $qty)
                          {
                             $total_qty = 0 ;

                             $update_id = $order_qty['Id'];
                          
                             $total_qty = $order_qty['Qty'] - $qty;

                             $update_qty = (new Order($this->db))->update_qty_by_product_id($total_qty,$update_id);
                        
                             $ins_data = (new Order($this->db))->insert_data_by_crone($insert_order);

                             $count_success[] = $order_id;
                          }
                      }
                     else
                     {
                         $count_fail[] = $order_id;
                     }

                      unlink($filepath);
                  
                  }
           
             }
         }

         else
         {
           echo "No pending file in directory";
         }

         $res['status'] = true;
         $res['message'] = 'Orders are exceuted successfully';
         $res['count_order_success'] = $count_success;
         $res['count_order_fail'] = $count_fail;

         die(json_encode($res));
         
    }

      
    }
  }
}
