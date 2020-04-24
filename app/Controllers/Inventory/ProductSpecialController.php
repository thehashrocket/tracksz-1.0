<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use App\Models\Product\product;
use App\Models\Inventory\CustomerGroup;
use App\Models\Inventory\Recurring;
use App\Models\Inventory\ProductSpecial;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;
use Stripe\Product as StripeProduct;

class ProductSpecialController
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
        $this->db = $db;
    }

    /*
    * add - Load Add Special View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        $prod_obj = new product($this->db);
        $product_list = $prod_obj->getAll();
        $custgroup_obj = new CustomerGroup($this->db);
        $customergroup_list = $custgroup_obj->all();
        return $this->view->buildResponse('inventory/productspecial/add', ['product_list' => $product_list, 'customergroup_list' => $customergroup_list]);
    }

    /*
    * addProductSpecialData - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProductSpecialData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      

        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'SpecialProductId'    => 'required',
                'SpecialCustomerGroup'    => 'required',
                'SpecialPriority'    => 'required',
                'SpecialPrice'    => 'required',
                'SpecialStartDate'    => 'required',
                'SpecialEndDate'    => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }
            $insert_data = $this->PrepareInsertData($form);
            $rec_obj = new ProductSpecial($this->db);
            $all_productspecial = $rec_obj->addProductSpecial($insert_data);
            if (isset($all_productspecial) && !empty($all_productspecial)) {
                $this->view->flash([
                    'alert' => _('Product Special added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/productspecial/add');
            } else {
                throw new Exception("Sorry we encountered an issue.  Please try again.", 301);
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

            $prod_obj = new product($this->db);
            $product_list = $prod_obj->getAll();
            $custgroup_obj = new CustomerGroup($this->db);
            $customergroup_list = $custgroup_obj->all();
            return $this->view->buildResponse('inventory/productspecial/add', ['product_list' => $product_list, 'customergroup_list' => $customergroup_list, 'form' => $form]);
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
        $form_data['ProductId'] = (isset($form['SpecialProductId']) && !empty($form['SpecialProductId'])) ? $form['SpecialProductId'] : 0;
        $form_data['CustomerGroupId'] = (isset($form['SpecialCustomerGroup']) && !empty($form['SpecialCustomerGroup'])) ? $form['SpecialCustomerGroup'] : 0;
        $form_data['Priority'] = (isset($form['SpecialPriority']) && !empty($form['SpecialPriority'])) ? $form['SpecialPriority'] : 0;
        $form_data['Price'] = (isset($form['SpecialPrice']) && !empty($form['SpecialPrice'])) ? $form['SpecialPrice'] : 0;
        $form_data['DateStart'] = (isset($form['SpecialStartDate']) && !empty($form['SpecialStartDate'])) ? date("Y-m-d H:m:s", strtotime($form['SpecialStartDate'])) : null;
        $form_data['DateEnd'] = (isset($form['SpecialEndDate']) && !empty($form['SpecialEndDate'])) ? date("Y-m-d H:m:s", strtotime($form['SpecialEndDate'])) : null;
        return $form_data;
    }

    /*
    * view - Load List Category View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $prodisc_obj = new ProductSpecial($this->db);
        $all_productspecial = $prodisc_obj->ProductSpecialJoinAll();
        return $this->view->buildResponse('inventory/productspecial/view', ['all_productspecial' => $all_productspecial]);
    }

    /*
    * deleteProductSpecialData - Delete Product Special Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteProductSpecialData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new ProductSpecial($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Product Special record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Product Special records not deleted..! Please try again.';
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
    * editProductSpecial - Load Edit Product Special View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editProductSpecial(ServerRequest $request, $Id = [])
    {
        $form = (new ProductSpecial($this->db))->findById($Id['Id']);
        $prod_obj = new product($this->db);
        $product_list = $prod_obj->getAll();
        $custgroup_obj = new CustomerGroup($this->db);
        $customergroup_list = $custgroup_obj->all();
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/productspecial/edit', [
                'form' => $form, 'product_list' => $product_list, 'customergroup_list' => $customergroup_list
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Product Special details. Please try again.',
                'alert_type' => 'danger'
            ]);
            $prodisc_obj = new ProductSpecial($this->db);
            $all_productspecial = $prodisc_obj->ProductSpecialJoinAll();
            return $this->view->buildResponse('inventory/productspecial/view', ['all_productspecial' => $all_productspecial]);
        }
    }

    /*
    * updateProductSpecial - Update Special data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateProductSpecial(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');

            $is_updated = (new ProductSpecial($this->db))->editProductSpecial($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Product Special record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $prodisc_obj = new ProductSpecial($this->db);
                $all_productspecial = $prodisc_obj->ProductSpecialJoinAll();
                return $this->view->buildResponse('inventory/productspecial/view', ['all_productspecial' => $all_productspecial]);
            } else {
                throw new Exception("Failed to update product special. Please ensure all input is filled out correctly.", 301);
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
            $prod_obj = new product($this->db);
            $product_list = $prod_obj->getAll();
            $custgroup_obj = new CustomerGroup($this->db);
            $customergroup_list = $custgroup_obj->all();
            return $this->view->buildResponse('inventory/productspecial/edit', [
                'form' => $methodData, 'product_list' => $product_list, 'customergroup_list' => $customergroup_list
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
        $form_data['Id'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : 0;
        $form_data['ProductId'] = (isset($form['SpecialProductId']) && !empty($form['SpecialProductId'])) ? $form['SpecialProductId'] : 0;
        $form_data['CustomerGroupId'] = (isset($form['SpecialCustomerGroup']) && !empty($form['SpecialCustomerGroup'])) ? $form['SpecialCustomerGroup'] : 0;
        $form_data['Priority'] = (isset($form['SpecialPriority']) && !empty($form['SpecialPriority'])) ? $form['SpecialPriority'] : 0;
        $form_data['Price'] = (isset($form['SpecialPrice']) && !empty($form['SpecialPrice'])) ? $form['SpecialPrice'] : 0;
        $form_data['DateStart'] = (isset($form['SpecialStartDate']) && !empty($form['SpecialStartDate'])) ? date("Y-m-d H:m:s", strtotime($form['SpecialStartDate'])) : null;
        $form_data['DateEnd'] = (isset($form['SpecialEndDate']) && !empty($form['SpecialEndDate'])) ? date("Y-m-d H:m:s", strtotime($form['SpecialEndDate'])) : null;
        return $form_data;
    }
}
