<?php declare(strict_types = 1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use App\Library\ValidateSanitize\ValidateSanitize;
use Exception;
use PDO;

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
        return $this->view->buildResponse('inventory/upload', []);
    }

    public function uploadInventoryFTP(ServerRequest $request)
    { 
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        try{
            $ftp_connect = ftp_connect("ftp.valorebooks.com");
            $ftp_username = "chrislands_348442";
            $ftp_password = "F23MRTJ8";

            $ftp_login = ftp_login($ftp_connect, $ftp_username, $ftp_password);

            if(!$ftp_login)
                    throw new Exception("Ftp Server connection fails...!", 400);
            
            $file_stream = $_FILES['InventoryUpload']['tmp_name'];
            $file_name = $_FILES['InventoryUpload']['name'];

            $is_file_upload = ftp_put($ftp_connect, 'Inventory/'.$file_name, $file_stream, FTP_ASCII);

            if(!$is_file_upload)
                    throw new Exception("Ftp File upload fails...! Please try again", 651);

            
            $validated['alert'] = 'Inventory File is uploaded into FTP Server successully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/upload');

        }catch (Exception $e){
            
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Inventory File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();            

            $validated['alert'] = 'Sorry, Inventory File is uploaded into FTP Server..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/inventory/upload');
        }
        
        

        if($ftp_login){
            echo "Ftp Connection successfully..!";
        }else{
            echo "Ftp Connection fails..!";
        }
        exit;
        
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
      
        $validate->validation_rules(array(
            'MarketName'    => 'required'
        ));

        $validated = $validate->run($form,true);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            $validated['alert'] = 'Sorry, we could not got to next step.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/marketplace/dashboard');
        }

        $market_price = Config::get('market_price');
        return $this->view->buildResponse('marketplace/add_step_second', ['form' => $form,'market_price' => $market_price]);
    }
   
    public function view()
    {
        return $this->view->buildResponse('inventory/view', []);
    }
    
    public function add()
    {
        return $this->view->buildResponse('inventory/item', []);
    }
    
    public function defaults()
    {
        $settings = require __DIR__.'/../../../config/inventory.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'), ['Defaults']
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
                'alert_type'=> 'danger',
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
