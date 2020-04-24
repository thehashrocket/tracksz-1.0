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
use App\Models\Inventory\ProductDiscount;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;
use Stripe\Product as StripeProduct;

class ProductDiscountController
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
    * add - Load Add Discount View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        $prod_obj = new product($this->db);
        $product_list = $prod_obj->getAll();
        $custgroup_obj = new CustomerGroup($this->db);
        $customergroup_list = $custgroup_obj->all();
        return $this->view->buildResponse('inventory/productdiscount/add', ['product_list' => $product_list, 'customergroup_list' => $customergroup_list]);
    }

    /*
    * addProductDiscountData - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProductDiscountData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      

        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'DiscountProductId'    => 'required',
                'DiscountCustomerGroup'    => 'required',
                'DiscountQuantity'    => 'required',
                'DiscountPriority'    => 'required',
                'DiscountPrice'    => 'required',
                'DiscountStartDate'    => 'required',
                'DiscountEndDate'    => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }
            $insert_data = $this->PrepareInsertData($form);
            $rec_obj = new ProductDiscount($this->db);
            $all_productdiscount = $rec_obj->addProductDiscount($insert_data);
            if (isset($all_productdiscount) && !empty($all_productdiscount)) {
                $this->view->flash([
                    'alert' => _('Product Discount added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/productdiscount/add');
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
            return $this->view->buildResponse('inventory/productdiscount/add', ['product_list' => $product_list, 'customergroup_list' => $customergroup_list, 'form' => $form]);
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
        $form_data['ProductId'] = (isset($form['DiscountProductId']) && !empty($form['DiscountProductId'])) ? $form['DiscountProductId'] : 0;
        $form_data['CustomerGroupId'] = (isset($form['DiscountCustomerGroup']) && !empty($form['DiscountCustomerGroup'])) ? $form['DiscountCustomerGroup'] : 0;
        $form_data['Quantity'] = (isset($form['DiscountQuantity']) && !empty($form['DiscountQuantity'])) ? $form['DiscountQuantity'] : 0;
        $form_data['Priority'] = (isset($form['DiscountPriority']) && !empty($form['DiscountPriority'])) ? $form['DiscountPriority'] : 0;
        $form_data['Price'] = (isset($form['DiscountPrice']) && !empty($form['DiscountPrice'])) ? $form['DiscountPrice'] : 0;
        $form_data['DateStart'] = (isset($form['DiscountStartDate']) && !empty($form['DiscountStartDate'])) ? date("Y-m-d H:m:s", strtotime($form['DiscountStartDate'])) : null;
        $form_data['DateEnd'] = (isset($form['DiscountEndDate']) && !empty($form['DiscountEndDate'])) ? date("Y-m-d H:m:s", strtotime($form['DiscountEndDate'])) : null;
        return $form_data;
    }

    /*
    * view - Load List Category View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $prodisc_obj = new ProductDiscount($this->db);
        $all_productdiscount = $prodisc_obj->ProductDiscountJoinAll();
        return $this->view->buildResponse('inventory/productdiscount/view', ['all_productdiscount' => $all_productdiscount]);
    }

    /*
    * deleteProductDiscountData - Delete Product Discount Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteProductDiscountData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new ProductDiscount($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Product Discount record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Product Discount records not deleted..! Please try again.';
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
    * editProductDiscount - Load Edit ProductDiscount View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editProductDiscount(ServerRequest $request, $Id = [])
    {
        $form = (new ProductDiscount($this->db))->findById($Id['Id']);
        $prod_obj = new product($this->db);
        $product_list = $prod_obj->getAll();
        $custgroup_obj = new CustomerGroup($this->db);
        $customergroup_list = $custgroup_obj->all();
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/productdiscount/edit', [
                'form' => $form, 'product_list' => $product_list, 'customergroup_list' => $customergroup_list
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Product Discount details. Please try again.',
                'alert_type' => 'danger'
            ]);
            $prodisc_obj = new ProductDiscount($this->db);
            $all_productdiscount = $prodisc_obj->ProductDiscountJoinAll();
            return $this->view->buildResponse('inventory/productdiscount/view', ['all_productdiscount' => $all_productdiscount]);
        }
    }

    /*
    * updateRecurring - Update Recurring data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateProductDiscount(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');

            $is_updated = (new ProductDiscount($this->db))->editProductDiscount($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Product Discount record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $prodisc_obj = new ProductDiscount($this->db);
                $all_productdiscount = $prodisc_obj->ProductDiscountJoinAll();
                return $this->view->buildResponse('inventory/productdiscount/view', ['all_productdiscount' => $all_productdiscount]);
            } else {
                throw new Exception("Failed to update product discount. Please ensure all input is filled out correctly.", 301);
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
            return $this->view->buildResponse('inventory/productdiscount/edit', [
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
        $form_data['ProductId'] = (isset($form['DiscountProductId']) && !empty($form['DiscountProductId'])) ? $form['DiscountProductId'] : 0;
        $form_data['CustomerGroupId'] = (isset($form['DiscountCustomerGroup']) && !empty($form['DiscountCustomerGroup'])) ? $form['DiscountCustomerGroup'] : 0;
        $form_data['Quantity'] = (isset($form['DiscountQuantity']) && !empty($form['DiscountQuantity'])) ? $form['DiscountQuantity'] : 0;
        $form_data['Priority'] = (isset($form['DiscountPriority']) && !empty($form['DiscountPriority'])) ? $form['DiscountPriority'] : 0;
        $form_data['Price'] = (isset($form['DiscountPrice']) && !empty($form['DiscountPrice'])) ? $form['DiscountPrice'] : 0;
        $form_data['DateStart'] = (isset($form['DiscountStartDate']) && !empty($form['DiscountStartDate'])) ? date("Y-m-d H:m:s", strtotime($form['DiscountStartDate'])) : null;
        $form_data['DateEnd'] = (isset($form['DiscountEndDate']) && !empty($form['DiscountEndDate'])) ? date("Y-m-d H:m:s", strtotime($form['DiscountEndDate'])) : null;
        return $form_data;
    }
}
