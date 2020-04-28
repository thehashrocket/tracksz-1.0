<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Models\Inventory\Category;
use App\Models\Product\Product;
use App\Models\Account\Store;
use Delight\Cookie\Session;
use Laminas\Diactoros\ServerRequest;
use PDO;
use App\Library\Config;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Library\ValidateSanitize\ValidateSanitize;
use Exception;
use App\Models\Marketplace\Marketplace;

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
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->view->buildResponse('/inventory/product/add', ['all_category' => $all_category]);
    }

    /*
    * add - Load Add Product View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add_2()
    {
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        $store_obj = new Store($this->db);
        $all_store = $store_obj->findId(2, 23);
        return $this->view->buildResponse('/inventory/product/add_2', ['all_category' => $all_category, 'all_store' => $all_store]);
    }
    /*
    @author    :: Tejas
    @task_id   :: product attributes map
    @task_desc :: product attributes mapping for different market stores
    @params    :: product details array
    */
    public function MapMarketProducts(ServerRequest $request)
    {
        try {
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
            if (isset($_FILES['ProdImage']['error']) && $_FILES['ProdImage']['error'] > 0) {
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
            $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG']);
            // if false than throw type error
            if (!$validator_ext->isValid($_FILES['ProdImage'])) {
                throw new Exception("Please upload valid file type JPG & PNG...!", 301);
            }
            /* File upload validation ends */

            $file_stream = $_FILES['ProdImage']['tmp_name'];
            $file_name = $_FILES['ProdImage']['name'];
            $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
            $publicDir = getcwd() . "/assets/images/product/" . $file_encrypt_name . strstr($file_name, '.');
            $prod_img = $file_encrypt_name . strstr($file_name, '.');
            $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);

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
                $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
                return $this->view->buildResponse('/inventory/product/add', ['all_category' => $all_category]);
            } else {
                throw new Exception("Sorry we encountered an issue.  Please try again.", 301);
            }

            /* Mapping marketplace attributes Starts */
            $market_stores = Config::get('market_stores');
            $market_attributes = Config::get('market_attributes');
            if (is_array($market_stores) && (count($market_stores) > 0 || !empty($market_stores))) {
                foreach ($market_stores as $store_value) {
                    $market_wise_data = $this->MapMarketAttributes($form, $market_attributes, $store_value);
                    // echo '<pre> martplaces';
                    // print_r($market_wise_data);
                    // echo '</pre>';
                    // exit;                        
                } // Loops Ends                
            }
            /* Mapping marketplace attributes Ends */
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

            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->view->buildResponse('/inventory/product/add', ['all_category' => $all_category, 'form' => $form]);
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
    public function MapMarketAttributes($product_details = array(), $predefined_attr = array(), $store_name = '')
    {
        $res['status'] = false;
        $res['data'] = null;
        $res['store'] = $store_name;
        $res['message'] = 'Market attributes is not set..!';
        if ((is_array($product_details) && is_array($predefined_attr)) &&
            (!empty($product_details) && !empty($predefined_attr) &&
                !empty($store_name))
        ) {
            $set_attr = array();
            foreach ($product_details as $prod_key => $prod_val) {
                if (isset($predefined_attr[$prod_key][$store_name])) {
                    $set_attr[$predefined_attr[$prod_key][$store_name]] = $prod_val;
                }
            } // Loops Ends                    
            $res['status'] = true;
            $res['data'] = $set_attr;
            $res['message'] = 'Market attributes is set successfully..!';
        }
        return $res;
    }

    /*
    * view - Load List Category View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function browse()
    {
        $prod_obj = new Product($this->db);
        $all_product = $prod_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->view->buildResponse('/inventory/product/view', ['all_product' => $all_product]);
    }

    /*
    * DeleteProductData - Delete Product Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function DeleteProductData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Product($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Product record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Product records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';
            echo json_encode($res);
            exit;
        }
    }



    /*
    * editProduct - Load Edit Product View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editProduct(ServerRequest $request, $Id = [])
    {
        $form = (new Product($this->db))->findById($Id['Id']);
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('/inventory/product/edit', [
                'form' => $form, 'all_category' => $all_category
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Product details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('/inventory/product/view', ['all_product' => $all_product]);
        }
    }

    /*
    * updateProduct - Update Product data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateProduct(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        
            $prod_img = $methodData['ProductImageHidden'];

            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $methodData = $validate->sanitize($methodData); // only trims & sanitizes strings (other filters available)

            $validate->validation_rules(array(
                'ProductNameInput'    => 'required',
                'ProductSKUInput' => 'required',
                'ProductIdInput' => 'required',
                'ProductBasePriceInput' => 'required',
                'ProductCondition' => 'required'
            ));

            $validated = $validate->run($methodData);

            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            /* File upload validation starts */
            if (isset($_FILES['ProdImage']['error']) && $_FILES['ProdImage']['error'] == 0) {

                $validator = new FilesSize([
                    'min' => '0kB',  // minimum of 1kB
                    'max' => '10MB', // maximum of 10MB
                ]);

                // if false than throw Size error 
                if (!$validator->isValid($_FILES)) {
                    throw new Exception("File upload size is too large...!", 301);
                }

                // Using an options array:
                $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG']);
                // if false than throw type error
                if (!$validator_ext->isValid($_FILES['ProdImage'])) {
                    throw new Exception("Please upload valid file type JPG & PNG...!", 301);
                }
                /* File upload validation ends */
                $file_stream = $_FILES['ProdImage']['tmp_name'];
                $file_name = $_FILES['ProdImage']['name'];
                $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
                $publicDir = getcwd() . "/assets/images/product/" . $file_encrypt_name . strstr($file_name, '.');
                $prod_img = $file_encrypt_name . strstr($file_name, '.');
                $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);
            }

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');
            $update_data['Image'] = $prod_img;
            $is_updated = (new Product($this->db))->editProduct($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Product record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $prod_obj = new Product($this->db);
                $all_product = $prod_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
                return $this->view->buildResponse('/inventory/product/view', ['all_product' => $all_product]);
                unlink(getcwd() . "/assets/images/product/" . $methodData['ProductImageHidden']);
            } else {
                throw new Exception("Failed to update category. Please ensure all input is filled out correctly.", 301);
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
            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            $methodData['Image'] = $prod_img;
            return $this->view->buildResponse('/inventory/product/edit', [
                'form' => $methodData, 'all_category' => $all_category
            ]);
        }
    }

    /*
    * PrepareUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareUpdateData($form = array())
    {
        $form_data = array();
        $form_data['Id'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : null;
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
        // $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }

    /*
    * UploadProduct -  Load Edit Product View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function UploadProduct()
    {
        $market_places = (new Marketplace($this->db))->findByUserId(Session::get('auth_user_id'), 1);
        return $this->view->buildResponse('inventory/product/upload', ['market_places' => $market_places]);
    }


    /*
    * UploadInventoryFTP - Upload Product csv file to ftp server
    * @param  $form  - marketplace of ftp server details, product cs file      
    * @return boolean 
    */
    public function UploadInventoryFTP(ServerRequest $request)
    {
        $form = $request->getUploadedFiles();
        $form_2 = $request->getParsedBody();

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

            if (isset($_FILES['ProductUpload']['error']) && $_FILES['ProductUpload']['error'] > 0) {
                throw new Exception("Please Upload Product file...!", 301);
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
            $validator2 = new Extension(['docs,jpg,xlsx,csv']);
            // if false than throw type error
            if (!$validator2->isValid($_FILES['ProductUpload'])) {
                throw new Exception("Please upload valid file type docs, jpg and xlsx...!", 301);
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

            $file_stream = $_FILES['ProductUpload']['tmp_name'];
            $file_name = $_FILES['ProductUpload']['name'];

            $is_file_upload = ftp_put($ftp_connect, 'Inventory/' . $file_name, $file_stream, FTP_ASCII);

            if (!$is_file_upload)
                throw new Exception("Ftp File upload fails...! Please try again", 651);


            $validated['alert'] = 'Product File is uploaded into FTP Server successully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);
            return $this->view->redirect('/product/upload');
        } catch (Exception $e) {
            $res['status'] = false;
            $res['data'] = [];
            $res['message'] = 'Product File not uploaded into server...!';
            $res['ex_message'] = $e->getMessage();
            $res['ex_code'] = $e->getCode();
            $res['ex_file'] = $e->getFile();
            $res['ex_line'] = $e->getLine();

            $validated['alert'] = $e->getMessage();
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/product/upload');
        }
        ftp_close($ftp_connect);
        exit;
    }
}
