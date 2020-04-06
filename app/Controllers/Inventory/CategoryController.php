<?php declare (strict_types = 1);

namespace App\Controllers\Inventory;

use App\Library\Config;
use App\Library\Views;
use App\Models\Account\Store;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class CategoryController
{
    private $view;
    private $db;

    public function __construct(Views $view, PDO $db)
    {   
        $this->view = $view;
        $this->db = $db;    
    }

    public function view()
    {
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
        return $this->view->buildResponse('inventory/category/view', ['all_category' => $all_category]);
    }

    public function add()
    {
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
        return $this->view->buildResponse('inventory/category/add', ['all_category' => $all_category]);
    }

    public function get_list_records(ServerRequest $request)
    {   

        $params = $request->getQueryParams();
        // TDS :: Server side processing starts  
        $cat_obj = new Category($this->db);
        $totalData = $cat_obj->count_all_records();   
        $totalFiltered = (is_array($totalData) && !empty($totalData))?$totalData['total_records']:0;
        $search_params['search'] = $params['search']['value'];        
        $search_params['start'] = $params['start'];
        $search_params['limit'] = $params['length'];
        $search_params['order'] = $params['columns'][$params['order'][0]['column']]['data'];    
        $search_params['dir'] = $params['order'][0]['dir'];

        $cat_result = $cat_obj->get_all_records($search_params);
        
        if(isset($cat_result) && !empty($cat_result)){
            $res['status'] = true;
            $res['message']['success'] = "Policy result get successfully..!";
            $res['message']['error'] = "";
            $res['data'] = $cat_result; 
           // $res['dir_path'] = Config::get('company_url').Config::get('image_path').'product/';
           $res['dir_path'] = Config::get('company_url').'/assets/images/product/';
            $res['recordsFiltered'] = $totalFiltered;
        }else{
            $res['status'] = false;
            $res['message']['success'] = "Policy result get successfully..!";
            $res['message']['error'] = "";
            $res['data'] = array(); 
            $res['recordsFiltered'] = $totalFiltered;
        }
        return die(json_encode($res));      
    } 
  
    public function addCategory(ServerRequest $request)
    {
        $form = $request->getParsedBody();        
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
      
        try{
        // Sanitize and Validate
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
      
        $validate->validation_rules(array(
            'CategoryName'    => 'required',
            'CategoryDescription'       => 'required',            
            // 'ParentCategory'     => 'required'
        ));

        $validated = $validate->run($form);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            throw new Exception("Please enter required fields...!", 301);
        }
       
        /* File upload validation starts */
        if(isset($_FILES['CategoryImage']['error']) && $_FILES['CategoryImage']['error'] > 0){
            throw new Exception("Please Upload Category Image...!", 301);
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
        if (!$validator_ext->isValid($_FILES['CategoryImage'])) {
            throw new Exception("Please upload valid file type JPG & PNG...!", 301);
        }  
        /* File upload validation ends */    

        $file_stream = $_FILES['CategoryImage']['tmp_name'];
        $file_name = $_FILES['CategoryImage']['name'];
        $file_encrypt_name = strtolower(str_replace(" ","_",strstr($file_name,'.',true).date('Ymd_his')));                
        // $publicDir = getcwd().'\assets\images\category\\'.$file_encrypt_name.strstr($file_name,'.');                
        $publicDir = getcwd()."/assets/images/category/".$file_encrypt_name.strstr($file_name,'.');
        $cat_img = $file_encrypt_name.strstr($file_name,'.');
        $is_file_uploaded = move_uploaded_file($file_stream,$publicDir); 

        $insert_data = $this->PrepareInsertData($form);         
        $insert_data['Image'] = $cat_img;          
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->addCateogry($insert_data);

        if (isset($all_category) && !empty($all_category)) {
            $this->view->flash([
                'alert' => _('Category added successfully..!'),
                'alert_type' => 'success'
            ]);
            return $this->view->redirect('/category/add');
        } else {
            throw new Exception("Sorry we encountered an issue.  Please try again.", 301);
        }               

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

        return $this->view->buildResponse('/inventory/category/add' ,['all_category' => $all_category, 'form' => $form]);
    }
    }

    /*
    * PrepareInsertData - Assign Value to new array and prepare insert data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function PrepareInsertData($form = array())
    {   
        $form_data = array();        
        $form_data['Name'] = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : null;
        $form_data['Description'] = (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : null;
        $form_data['ParentId'] = $form_data['ParentId'] = (isset($form['ParentCategory']) && !empty($form['ParentCategory'])) ? $form['ParentCategory'] : 0;
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;      
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');      
        return $form_data;
    }


     /*
    * PrepareUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    private function PrepareUpdateData($form = array())
    {   
        $form_data = array();   
        $form_data['Id'] = (isset($form['Id']) && !empty($form['Id']))?$form['Id']:null;     
        $form_data['Name'] = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : null;
        $form_data['Description'] = (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : null;
        $form_data['ParentId'] = (isset($form['ParentCategory']) && !empty($form['ParentCategory'])) ? $form['ParentCategory'] : 0; 
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;      
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }

    public function deleteCategoryData(ServerRequest $request){
        $form = $request->getParsedBody();
        $result_data = (new Category($this->db))->delete($form['Id']);        
        if(isset($result_data) && !empty($result_data)){

            $validated['alert'] = 'Marketplace record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';                
            echo json_encode($res);
            exit;
        }else{
            $validated['alert'] = 'Sorry, Marketplace records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';                
            echo json_encode($res);
            exit;
        }
    }

    public function editCategory(ServerRequest $request, $Id = [])
    {          
        $form = (new Category($this->db))->findById($Id['Id']);        
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
        if(is_array($form) && !empty($form)){       
            return $this->view->buildResponse('inventory/category/edit', [
                'form' => $form,'all_category' => $all_category]);
        }else{
            $this->view->flash([
                        'alert' => 'Failed to fetch Category details. Please try again.',
                        'alert_type' => 'danger'
                    ]);
            return $this->view->buildResponse('inventory/category/view', ['all_category' => $all_category]);
        }
    }

    public function updateCategory(ServerRequest $request, $Id = [])
    {      
        try{
        $methodData = $request->getParsedBody();
        unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        
        $cat_img = $methodData['CategoryImageHidden'];
        /* File upload validation starts */
        if(isset($_FILES['CategoryImage']['error']) && $_FILES['CategoryImage']['error'] == 0){
   
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
        if (!$validator_ext->isValid($_FILES['CategoryImage'])) {
            throw new Exception("Please upload valid file type JPG & PNG...!", 301);
        }  
        /* File upload validation ends */ 
        
  

     
        $file_stream = $_FILES['CategoryImage']['tmp_name'];
        $file_name = $_FILES['CategoryImage']['name'];
        $file_encrypt_name = strtolower(str_replace(" ","_",strstr($file_name,'.',true).date('Ymd_his')));                
        $publicDir = getcwd()."/assets/images/category/".$file_encrypt_name.strstr($file_name,'.');
        $cat_img = $file_encrypt_name.strstr($file_name,'.');
       
        $is_file_uploaded = move_uploaded_file($file_stream,$publicDir);  
           
        }

       

        $update_data = $this->PrepareUpdateData($methodData);

      
        $update_data['Updated'] = date('Y-m-d H:i:s');     
        $update_data['Image'] = $cat_img;     
        $is_updated = (new Category($this->db))->editCategory($update_data);

        if(isset($is_updated) && !empty($is_updated)){ 
            $this->view->flash([
                'alert' => 'Category record updated successfully..!',
                'alert_type' => 'success'
            ]);
            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'),[0,1]);
            return $this->view->buildResponse('inventory/category/view', ['all_category' => $all_category]);
            unlink(getcwd()."/assets/images/category/".$methodData['CategoryImageHidden']);
        }else{
            throw new Exception("Failed to update category. Please ensure all input is filled out correctly.", 301);
        }

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
        return $this->view->buildResponse('inventory/category/edit', [
            'form' => $methodData,'all_category' => $all_category]);
    }
    }

    /*
    @author    :: Tejas
    @task_id   ::
    @task_desc ::
    @params    ::
     */
    /* public function _get_class_info(Request $request)
    {
        $form = $request->getParsedBody();
        $class_methods = get_class_methods(get_class($request));
        // [0] => __construct
        // [1] => getServerParams
        // [2] => getUploadedFiles
        // [3] => withUploadedFiles
        // [4] => getCookieParams
        // [5] => withCookieParams
        // [6] => getQueryParams
        // [7] => withQueryParams
        // [8] => getParsedBody
        // [9] => withParsedBody
        // [10] => getAttributes
        // [11] => getAttribute
        // [12] => withAttribute
        // [13] => withoutAttribute
        // [14] => getRequestTarget
        // [15] => withRequestTarget
        // [16] => getMethod
        // [17] => withMethod
        // [18] => getUri
        // [19] => withUri
        // [20] => getProtocolVersion
        // [21] => withProtocolVersion
        // [22] => getHeaders
        // [23] => hasHeader
        // [24] => getHeader
        // [25] => getHeaderLine
        // [26] => withHeader
        // [27] => withAddedHeader
        // [28] => withoutHeader
        // [29] => getBody
        // [30] => withBody
        // $files = $request->getUploadedFiles();

        $files_methods = get_class_methods(get_class($files['productImage']));
        // [0] => __construct
        // [1] => getStream
        // [2] => moveTo
        // [3] => getSize
        // [4] => getError
        // [5] => getClientFilename
        // [6] => getClientMediaType
    // [0] => __construct
        // [1] => setArrayObjectPrototype
        // [2] => getArrayObjectPrototype
        // [3] => getReturnType
        // [4] => current
        // [5] => initialize
        // [6] => buffer
        // [7] => isBuffered
        // [8] => getDataSource
        // [9] => getFieldCount
        // [10] => next
        // [11] => key
        // [12] => valid
        // [13] => rewind
        // [14] => count
        // [15] => toArray
    }

    public function map_market_products(ServerRequest $request)
    {

        $form = $request->getParsedBody();
        $form_files = $request->getUploadedFiles();
        $files_handle = get_class_methods(get_class($form_files['productImage']));
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        $market_stores = Config::get('market_stores');
        $market_attributes = Config::get('market_attributes');

        if (is_array($market_stores) && (count($market_stores) > 0 || !empty($market_stores))) {
            foreach ($market_stores as $store_value) {
                $market_wise_data = $this->map_market_attributes($form, $market_attributes, $store_value);
                echo '<pre>';
                print_r($market_wise_data);
                echo '</pre>';

            } // Loops Ends
        }

        die('result ends');
    } */

    /*
    @author    :: Tejas
    @task_id   :: product attributes map
    @task_desc :: product attributes mapping for different market stores
    @params    :: product details array
     */
    /* public function map_market_attributes($product_details = array(), $predefined_attr = array(), $store_name = '')
    {
        $res['status'] = false;
        $res['data'] = null;
        $res['store'] = $store_name;
        $res['message'] = 'Market attributes is not set..!';
        if ((is_array($product_details) && is_array($predefined_attr)) &&
            (!empty($product_details) && !empty($predefined_attr) &&
                !empty($store_name))) {
            $set_attr = array();
            $set_mrg_attr = array();
            foreach ($product_details as $prod_key => $prod_val) {
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
