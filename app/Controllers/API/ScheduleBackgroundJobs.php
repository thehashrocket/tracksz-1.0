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

class ScheduleBackgroundJobs
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db   = $db;
        error_reporting(E_ALL ^ E_DEPRECATED);
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

          // echo"<pre>";
          // print_r($file_list);
          // die;

          if(!empty($file_list))
           {

                foreach($file_list as $value) {
              
              $remoteFile = $value;

              print_r($remoteFile);
              die('test');
         
              $newname = str_replace("/Order/",'', $remoteFile);
         
              ob_start();
              ftp_get($ftp_conn, 'php://output', $remoteFile, FTP_ASCII);
               
              $data = ob_get_contents();
              ob_end_clean();

              $file = fopen('C:/xamppnew/htdocs/tracksz/orderdata/'.$newname,"w");

              

              echo fwrite($file,$data);

              fclose($file);
            }
            
            }
         
        $path = 'C:/xamppnew/htdocs/tracksz/orderdata';
          $fileArray =array();

        if ($handle = opendir($path)) {
              while (false !== ($file = readdir($handle))) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if($ext=='csv' || $ext=='xls'){
                      $fileArray[] = $path."/".$file;
                }
              
             }
             closedir($handle);
          }

            
            }
        }    
         
  }

