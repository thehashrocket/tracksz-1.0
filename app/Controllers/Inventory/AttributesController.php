<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use App\Models\Inventory\Attribute;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class AttributesController
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
    * add - Load Add Attribute View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        $cat_obj = new Attribute($this->db);
        $all_attribute = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->view->buildResponse('inventory/attribute/add', ['all_attribute' => $all_attribute]);
    }

    /*
    * addAttribute - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addAttribute(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)


            $validate->validation_rules(array(
                'AttributeName'    => 'required',
                'AttributeValue'       => 'required',
                'SortOrder'       => 'required',
            ));

            $validated = $validate->run($form);



            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            $insert_data = $this->PrepareInsertData($form);

            $attr_obj = new Attribute($this->db);
            $all_attribute = $attr_obj->addAttribute($insert_data);

            if (isset($all_attribute) && !empty($all_attribute)) {
                $this->view->flash([
                    'alert' => _('Attribute added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/attribute/add');
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
            return $this->view->buildResponse('/inventory/attribute/add', ['form' => $form]);
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
        $form_data['Name'] = (isset($form['AttributeName']) && !empty($form['AttributeName'])) ? $form['AttributeName'] : null;
        $form_data['Value'] = (isset($form['AttributeValue']) && !empty($form['AttributeValue'])) ? $form['AttributeValue'] : null;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        return $form_data;
    }

    /*
    * view - Load List Attribute View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $attr_obj = new Attribute($this->db);
        $all_attributes = $attr_obj->all();
        return $this->view->buildResponse('inventory/attribute/view', ['all_attributes' => $all_attributes]);
    }

    /*
    * deleteAttributeData - Delete Attribute Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteAttributeData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Attribute($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Attribute record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Attribute records not deleted..! Please try again.';
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
    * editAttribute - Load Edit Attribute View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editAttribute(ServerRequest $request, $Id = [])
    {
        $form = (new Attribute($this->db))->findById($Id['Id']);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/attribute/edit', [
                'form' => $form, 'all_attribute' => $form
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Attribute details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('inventory/attribute/view', ['all_attribute' => $form]);
        }
    }

    /*
    * updateAttribute - Update Attribute data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateAttribute(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');

            $is_updated = (new Attribute($this->db))->editAttribute($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Attribute record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $attr_obj = new Attribute($this->db);
                $all_attributes = $attr_obj->all();
                return $this->view->buildResponse('inventory/attribute/view', ['all_attributes' => $all_attributes]);
            } else {
                throw new Exception("Failed to update attribute. Please ensure all input is filled out correctly.", 301);
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
            $cat_obj = new Attribute($this->db);
            $all_attribute = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->view->buildResponse('inventory/attribute/edit', [
                'form' => $methodData, 'all_attribute' => $all_attribute
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
        $form_data['Name'] = (isset($form['AttributeName']) && !empty($form['AttributeName'])) ? $form['AttributeName'] : null;
        $form_data['Value'] = (isset($form['AttributeValue']) && !empty($form['AttributeValue'])) ? $form['AttributeValue'] : null;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        return $form_data;
    }
}
