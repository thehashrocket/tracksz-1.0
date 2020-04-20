<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use App\Models\Inventory\Recurring;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class RecurringController
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
    * add - Load Add Category View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        $all_recurring = [['name' => 'day'], ['name' => 'week'], ['name' => 'semi_month'], ['name' => 'month'], ['name' => 'year']];
        return $this->view->buildResponse('inventory/recurring/add', ['all_recurring' => $all_recurring]);
    }

    /*
    * addRecurring - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addRecurringData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      

        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'RecurringName'    => 'required',
                'RecurringPrice' => 'required',
                'RecurringFrequency' => 'required',
                'RecurringDuration' => 'required',
                'RecurringCycle' => 'required',
                'TrailStatus' => 'required',
                'RecurringTrialPrice' => 'required',
                'RecurringTrailFrequency' => 'required',
                'RecurringTrailDuration' => 'required',
                'RecurringTrailCycle' => 'required',
                'RecurringStatus' => 'required',
                'SortOrder' => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }
            $insert_data = $this->PrepareInsertData($form);
            $rec_obj = new Recurring($this->db);
            $all_recurring = $rec_obj->addRecurring($insert_data);
            if (isset($all_recurring) && !empty($all_recurring)) {
                $this->view->flash([
                    'alert' => _('Recurring added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/recurring/add');
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

            $all_recurring = [['name' => 'day'], ['name' => 'week'], ['name' => 'semi_month'], ['name' => 'month'], ['name' => 'year']];
            return $this->view->buildResponse('/inventory/recurring/add', ['all_recurring' => $all_recurring, 'form' => $form]);
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
        $form_data['Name'] = (isset($form['RecurringName']) && !empty($form['RecurringName'])) ? $form['RecurringName'] : null;
        $form_data['Price'] = (isset($form['RecurringPrice']) && !empty($form['RecurringPrice'])) ? $form['RecurringPrice'] : null;
        $form_data['Frequency'] = (isset($form['RecurringFrequency']) && !empty($form['RecurringFrequency'])) ? trim($form['RecurringFrequency']) : 0;
        $form_data['Duration'] = (isset($form['RecurringDuration']) && !empty($form['RecurringDuration'])) ? $form['RecurringDuration'] : 0;
        $form_data['Cycle'] = (isset($form['RecurringCycle']) && !empty($form['RecurringCycle'])) ? $form['RecurringCycle'] : 0;
        $form_data['TrialStatus'] = (isset($form['TrailStatus']) && !empty($form['TrailStatus'])) ? $form['TrailStatus'] : 0;
        $form_data['TrialPrice'] = (isset($form['RecurringTrialPrice']) && !empty($form['RecurringTrialPrice'])) ? $form['RecurringTrialPrice'] : 0;
        $form_data['TrialFrequency'] = (isset($form['RecurringTrailFrequency']) && !empty($form['RecurringTrailFrequency'])) ? trim($form['RecurringTrailFrequency']) : 0;
        $form_data['TrialDuration'] = (isset($form['RecurringTrailDuration']) && !empty($form['RecurringTrailDuration'])) ? $form['RecurringTrailDuration'] : 0;
        $form_data['TrialCycle'] = (isset($form['RecurringTrailCycle']) && !empty($form['RecurringTrailCycle'])) ? $form['RecurringTrailCycle'] : 0;
        $form_data['Status'] = (isset($form['RecurringStatus']) && !empty($form['RecurringStatus'])) ? $form['RecurringStatus'] : 0;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        return $form_data;
    }

    /*
    * view - Load List Category View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $rec_obj = new Recurring($this->db);
        $all_recurring = $rec_obj->all();
        return $this->view->buildResponse('inventory/recurring/view', ['all_recurring' => $all_recurring]);
    }

    /*
    * deleteRecurringData - Delete Recurring Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteRecurringData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Recurring($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Recurring record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Recurring records not deleted..! Please try again.';
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
    * editRecurring - Load Edit Recurring View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editRecurring(ServerRequest $request, $Id = [])
    {
        $form = (new Recurring($this->db))->findById($Id['Id']);
        $all_recurring = [['name' => 'day'], ['name' => 'week'], ['name' => 'semi_month'], ['name' => 'month'], ['name' => 'year']];
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/recurring/edit', [
                'form' => $form, 'all_recurring' => $all_recurring
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Recurring details. Please try again.',
                'alert_type' => 'danger'
            ]);
            $rec_obj = new Recurring($this->db);
            $all_recurring = $rec_obj->all();
            return $this->view->buildResponse('inventory/recurring/list', ['all_recurring' => $all_recurring]);
        }
    }

    /*
    * updateRecurring - Update Recurring data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateRecurring(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');

            $is_updated = (new Recurring($this->db))->editRecurring($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Recurring record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $cat_obj = new Recurring($this->db);
                $all_recurring = $cat_obj->all();
                return $this->view->buildResponse('inventory/recurring/view', ['all_recurring' => $all_recurring]);
            } else {
                throw new Exception("Failed to update recurring. Please ensure all input is filled out correctly.", 301);
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
            $all_recurring = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->view->buildResponse('inventory/recurring/edit', [
                'form' => $methodData, 'all_recurring' => $all_recurring
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
        $form_data['Name'] = (isset($form['RecurringName']) && !empty($form['RecurringName'])) ? $form['RecurringName'] : null;
        $form_data['Price'] = (isset($form['RecurringPrice']) && !empty($form['RecurringPrice'])) ? $form['RecurringPrice'] : null;
        $form_data['Frequency'] = (isset($form['RecurringFrequency']) && !empty($form['RecurringFrequency'])) ? $form['RecurringFrequency'] : 0;
        $form_data['Duration'] = (isset($form['RecurringDuration']) && !empty($form['RecurringDuration'])) ? $form['RecurringDuration'] : 0;
        $form_data['Cycle'] = (isset($form['RecurringCycle']) && !empty($form['RecurringCycle'])) ? $form['RecurringCycle'] : 0;
        $form_data['TrialStatus'] = (isset($form['TrailStatus']) && !empty($form['TrailStatus'])) ? $form['TrailStatus'] : 0;
        $form_data['TrialPrice'] = (isset($form['RecurringTrialPrice']) && !empty($form['RecurringTrialPrice'])) ? $form['RecurringTrialPrice'] : 0;
        $form_data['TrialFrequency'] = (isset($form['RecurringTrailFrequency']) && !empty($form['RecurringTrailFrequency'])) ? $form['RecurringTrailFrequency'] : 0;
        $form_data['TrialDuration'] = (isset($form['RecurringTrailDuration']) && !empty($form['RecurringTrailDuration'])) ? $form['RecurringTrailDuration'] : 0;
        $form_data['TrialCycle'] = (isset($form['RecurringTrailCycle']) && !empty($form['RecurringTrailCycle'])) ? $form['RecurringTrailCycle'] : 0;
        $form_data['Status'] = (isset($form['RecurringStatus']) && !empty($form['RecurringStatus'])) ? $form['RecurringStatus'] : 0;
        $form_data['SortOrder'] = (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : 0;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        return $form_data;
    }
}
