<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use PDO;

class ScheduleBackgroundJobs
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db   = $db;
          
    }

    public function orderBackgroundProcess()
    {
       $ftpHost      = 'ftp.valorebooks.com';
       $ftpUsername  = 'chrislands_348442';
       $ftpPassword  = 'F23MRTJ8';

        // open an FTP connection
       $ftp_conn = ftp_connect($ftpHost) or die("Could not connect to $ftp_server");
       $login = ftp_login($ftp_conn, $ftpUsername, $ftpPassword);
       
       $file_list = ftp_nlist($ftp_conn, "/Order");

       if(isset($file_list) && count($file_list)>0)
       {
            echo "yes";  
       }

       
       //$contentsArray = ftp_nlist($this->connectionId, $parameters . '  ' . $directory);

       echo"<pre>";
       print_r($file_list);

      // die;
       $fileTo = 'C:/xamppnew/htdocs/tracksz/test';
       $fileFrom = '/Order/';
       $mode = FTP_ASCII;
       if (ftp_get($ftp_conn, $fileTo, $fileFrom, $mode, 0)) {
 
           echo "Successfully";
        } else {
            echo "error";
        }
      
       die('mukesh');
    }
}
