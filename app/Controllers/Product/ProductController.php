<?php declare(strict_types = 1);

namespace App\Controllers\Product;

use App\Library\Views;
use App\Models\Inventory\Category;
use App\Models\Product\Product;
use Delight\Cookie\Session;
use Laminas\Diactoros\ServerRequest;
use PDO;
use App\Library\Config;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Library\ValidateSanitize\ValidateSanitize;
use Exception;

class ProductController
{
    private $view;
    private $db;
    
    /*
    * __construct - 
    * @param  $form  - Default View, PDO db   
    * @return set data
    */
    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db   = $db;
    }
    
    /*
    * add - Load Add Product View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {        
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
        return $this->view->buildResponse('product/add', ['all_category' => $all_category]);
    }
  
    /*
    @author    :: Tejas
    @task_id   :: product attributes map
    @task_desc :: product attributes mapping for different market stores
    @params    :: product details array
    */
    public function MapMarketProducts(ServerRequest $request)
    {   
        try{
            $form = $request->getParsedBody();        
            unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
            
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
        
            $validate->validation_rules(array(
                'ProductNameInput'    => 'required',
                'ProductSKUInput' => 'required',
                'ProductIdInput' => 'required',
                'ProductBasePriceInput' => 'required',
                'ProductCondition' => 'required'
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            } 
            
             /* File upload validation starts */
             if(isset($_FILES['ProdImage']['error']) && $_FILES['ProdImage']['error'] > 0){
                throw new Exception("Please Upload Product Image...!", 301);
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
            $validator_ext = new Extension(['png,jpg']);         
            // if false than throw type error
            if (!$validator_ext->isValid($_FILES['ProdImage'])) {
                throw new Exception("Please upload valid file type JPG & PNG...!", 301);
            }  
            /* File upload validation ends */    

            $file_stream = $_FILES['ProdImage']['tmp_name'];
            $file_name = $_FILES['ProdImage']['name'];
            $file_encrypt_name = strtolower(str_replace(" ","_",strstr($file_name,'.',true).date('Ymd_his')));
            $publicDir = getcwd()."/assets/images/product/".$file_encrypt_name.strstr($file_name,'.');
            $prod_img = $file_encrypt_name.strstr($file_name,'.');
            $is_file_uploaded = move_uploaded_file($file_stream,$publicDir); 
           
            $insert_data = $this->PrepareInsertData($form);         
            $insert_data['Image'] = $prod_img;          
            $prod_obj = new Product($this->db);

            $all_product = $prod_obj->addProduct($insert_data);

            if (isset($all_product) && !empty($all_product)) {
                $this->view->flash([
                    'alert' => _('Product added successfully..!'),
                    'alert_type' => 'success'
                ]);
                $cat_obj = new Category($this->db);
                $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
                return $this->view->buildResponse('product/add', ['all_category' => $all_category]);
            } else {
                throw new Exception("Sorry we encountered an issue.  Please try again.", 301);
            }  

            /* Mapping marketplace attributes Starts */
            $market_stores = Config::get('market_stores');
            $market_attributes = Config::get('market_attributes');          
            if(is_array($market_stores) && (count($market_stores)>0 || !empty($market_stores))) {
                foreach($market_stores as $store_value ) {
                        $market_wise_data = $this->MapMarketAttributes($form,$market_attributes,$store_value);
                            // echo '<pre> martplaces';
                            // print_r($market_wise_data);
                            // echo '</pre>';
                            // exit;                        
                } // Loops Ends                
            }
            /* Mapping marketplace attributes Ends */

            
        }catch (Exception $e){    

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

            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
            return $this->view->buildResponse('product/add', ['all_category' => $all_category,'form' => $form]);
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
        $form_data['Name'] = (isset($form['ProductNameInput']) && !empty($form['ProductNameInput'])) ? $form['ProductNameInput'] : null;
        $form_data['Notes'] = (isset($form['ProductNote']) && !empty($form['ProductNote'])) ? $form['ProductNote'] : null;
        $form_data['SKU'] = (isset($form['ProductSKUInput']) && !empty($form['ProductSKUInput'])) ? $form['ProductSKUInput'] : 0;
        $form_data['ProdId'] = (isset($form['ProductIdInput']) && !empty($form['ProductIdInput'])) ? $form['ProductIdInput'] : 0;
        $form_data['BasePrice'] = (isset($form['ProductBasePriceInput']) && !empty($form['ProductBasePriceInput'])) ? $form['ProductBasePriceInput'] : 0;                
        $form_data['ProdCondition'] = (isset($form['ProductCondition']) && !empty($form['ProductCondition'])) ? $form['ProductCondition'] : 0;
        $form_data['ProdActive'] = (isset($form['ProductActive']) && !empty($form['ProductActive'])) ? $form['ProductActive'] : 0;
        $form_data['InternationalShip'] = (isset($form['ProdInterShip']) && !empty($form['ProdInterShip'])) ? $form['ProdInterShip'] : 0;
        $form_data['ExpectedShip'] = (isset($form['ProdExpectedShip']) && !empty($form['ProdExpectedShip'])) ? $form['ProdExpectedShip'] : 0;
        $form_data['EbayTitle'] = (isset($form['ProdTitleBayInput']) && !empty($form['ProdTitleBayInput'])) ? $form['ProdTitleBayInput'] : null;
        $form_data['Qty'] = (isset($form['ProdQtyInput']) && !empty($form['ProdQtyInput'])) ? $form['ProdQtyInput'] : 0;
        $form_data['CategoryId'] = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : 0;
        
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;      
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }

    /*
        @author    :: Tejas
        @task_id   :: product attributes map
        @task_desc :: product attributes mapping for different market stores
        @params    :: product details array
    */
        public function MapMarketAttributes($product_details = array(), $predefined_attr = array(), $store_name = ''){
        $res['status'] = false;
        $res['data'] = null;
        $res['store'] = $store_name;
        $res['message'] = 'Market attributes is not set..!';            
        if((is_array($product_details) && is_array($predefined_attr)) && 
                (!empty($product_details) && !empty($predefined_attr) && 
                !empty($store_name))){
                $set_attr = array();                      
                foreach($product_details as $prod_key=>$prod_val) {
                    if(isset($predefined_attr[$prod_key][$store_name])){
                        $set_attr[$predefined_attr[$prod_key][$store_name]] = $prod_val;
                    }                       
                } // Loops Ends                    
                $res['status'] = true;
                $res['data'] = $set_attr;
                $res['message'] = 'Market attributes is set successfully..!';
        }            
        return $res;
    }
}
