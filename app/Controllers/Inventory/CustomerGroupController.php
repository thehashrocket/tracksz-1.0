<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use App\Models\Inventory\CustomerGroup;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class CustomerGroupController
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
    * add - Load Add CustomerGroup View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        return $this->view->buildResponse('inventory/customergroup/add');
    }

    /*
    * addCustomerGroupData - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addCustomerGroupData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)


            $validate->validation_rules(array(
                'CustomerGroupName'    => 'required',
                'CustomerGroupDescription'  => 'required',
                // 'CustomerGroupApproval'  => 'required',
                'SortOrder'       => 'required',
            ));
            $validated = $validate->run($form);

            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            $insert_data = $this->PrepareInsertData($form);
            $attr_obj = new CustomerGroup($this->db);
            $all_customergroup = $attr_obj->addCustomerGroup($insert_data);

            if (isset($all_customergroup) && !empty($all_customergroup)) {
                $this->view->flash([
                    'alert' => _('CustomerGroup added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/customergroup/add');
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
            return $this->view->buildResponse('/inventory/customergroup/add', ['form' => $form]);
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
        $form_data['Name'] = (isset($form['CustomerGroupName']) && !empty($form['CustomerGroupName'])) ? $form['CustomerGroupName'] : null;
        $form_data['Description'] = (isset($form['CustomerGroupDescription']) && !empty($form['CustomerGroupDescription'])) ? $form['CustomerGroupDescription'] : null;
        $form_data['Approval'] = (isset($form['CustomerGroupApproval']) && !empty($form['CustomerGroupApproval'])) ? $form['CustomerGroupApproval'] : 0;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        return $form_data;
    }

    /*
    * view - Load List CustomerGroup View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $attr_obj = new CustomerGroup($this->db);
        $all_customergroup = $attr_obj->all();
        return $this->view->buildResponse('inventory/customergroup/view', ['all_customergroup' => $all_customergroup]);
    }

    /*
    * deleteCustomerGroupData - Delete CustomerGroup Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteCustomerGroupData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new CustomerGroup($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'CustomerGroup record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, CustomerGroup records not deleted..! Please try again.';
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
    * editCustomerGroup - Load Edit CustomerGroup View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editCustomerGroup(ServerRequest $request, $Id = [])
    {
        $form = (new CustomerGroup($this->db))->findById($Id['Id']);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/customergroup/edit', [
                'form' => $form, 'all_customergroup' => $form
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch CustomerGroup details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('inventory/customergroup/view', ['all_customergroup' => $form]);
        }
    }

    /*
    * updateCustomerGroup - Update CustomerGroup data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateCustomerGroup(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');
            $is_updated = (new CustomerGroup($this->db))->editCustomerGroup($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'CustomerGroup record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $attr_obj = new CustomerGroup($this->db);
                $all_customergroup = $attr_obj->all();
                return $this->view->buildResponse('inventory/customergroup/view', ['all_customergroup' => $all_customergroup]);
            } else {
                throw new Exception("Failed to update customergroup. Please ensure all input is filled out correctly.", 301);
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
            $cat_obj = new CustomerGroup($this->db);
            $all_customergroup = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->view->buildResponse('inventory/customergroup/edit', [
                'form' => $methodData, 'all_customergroup' => $all_customergroup
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
        $form_data['Name'] = (isset($form['CustomerGroupName']) && !empty($form['CustomerGroupName'])) ? $form['CustomerGroupName'] : null;
        $form_data['Description'] = (isset($form['CustomerGroupDescription']) && !empty($form['CustomerGroupDescription'])) ? $form['CustomerGroupDescription'] : null;
        $form_data['Approval'] = (isset($form['CustomerGroupApproval']) && !empty($form['CustomerGroupApproval'])) ? $form['CustomerGroupApproval'] : 0;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        return $form_data;
    }
}
