<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use App\Models\Inventory\Download;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class DownloadController
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
    * add - Load Add Download View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function add()
    {
        $cat_obj = new Download($this->db);
        $all_download = $cat_obj->getActiveUserAll();
        return $this->view->buildResponse('inventory/download/add', ['all_download' => $all_download]);
    }

    /*
    * addDownload - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addDownload(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)


            $validate->validation_rules(array(
                'DownloadName'    => 'required',
                'DownloadMask'       => 'required',
            ));

            $validated = $validate->run($form);

            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }

            /* File upload validation starts */
            if (isset($_FILES['DownloadFilename']['error']) && $_FILES['DownloadFilename']['error'] > 0) {
                throw new Exception("Please Upload File Image...!", 301);
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
            $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG,gif,svg,txt,pdf,ppt,doc,docx,xls']);
            // if false than throw type error
            if (!$validator_ext->isValid($_FILES['DownloadFilename'])) {
                throw new Exception("Please upload valid file type JPG & PNG...!", 301);
            }
            /* File upload validation ends */



            $file_stream = $_FILES['DownloadFilename']['tmp_name'];
            $file_name = $_FILES['DownloadFilename']['name'];
            $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
            $publicDir = getcwd() . "/assets/inventory/download/" . $file_encrypt_name . strstr($file_name, '.');
            $file_upload = $file_encrypt_name . strstr($file_name, '.');
            $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);

            $insert_data = $this->PrepareInsertData($form);
            $insert_data['Filename'] = $file_upload;

            $attr_obj = new Download($this->db);
            $all_download = $attr_obj->addDownload($insert_data);

            if (isset($all_download) && !empty($all_download)) {
                $this->view->flash([
                    'alert' => _('Download added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->view->redirect('/download/add');
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
            return $this->view->buildResponse('/inventory/download/add', ['form' => $form]);
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
        $form_data['Name'] = (isset($form['DownloadName']) && !empty($form['DownloadName'])) ? $form['DownloadName'] : null;
        $form_data['Mask'] = (isset($form['DownloadMask']) && !empty($form['DownloadMask'])) ? $form['DownloadMask'] : 0;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        return $form_data;
    }

    /*
    * view - Load List Download View
    * @param  none 
    * @return boolean load view with pass data
    */
    public function view()
    {
        $attr_obj = new Download($this->db);
        $all_downloads = $attr_obj->all();
        return $this->view->buildResponse('inventory/download/view', ['all_downloads' => $all_downloads]);
    }

    /*
    * deleteDownloadData - Delete Download Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteDownloadData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Download($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Download record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->view->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Download records not deleted..! Please try again.';
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
    * editDownload - Load Edit Download View
    * @param  $form  - Id    
    * @return boolean load view with pass data
    */
    public function editDownload(ServerRequest $request, $Id = [])
    {
        $form = (new Download($this->db))->findById($Id['Id']);
        if (is_array($form) && !empty($form)) {
            return $this->view->buildResponse('inventory/download/edit', [
                'form' => $form, 'all_download' => $form
            ]);
        } else {
            $this->view->flash([
                'alert' => 'Failed to fetch Download details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->view->buildResponse('inventory/download/view', ['all_download' => $form]);
        }
    }

    /*
    * updateDownload - Update Download data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateDownload(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        
            $file_upload = $methodData['DownloadFilenameHidden'];
            /* File upload validation starts */
            if (isset($_FILES['DownloadFilename']['error']) && $_FILES['DownloadFilename']['error'] == 0) {

                $validator = new FilesSize([
                    'min' => '0kB',  // minimum of 1kB
                    'max' => '10MB', // maximum of 10MB
                ]);

                // if false than throw Size error 
                if (!$validator->isValid($_FILES)) {
                    throw new Exception("File upload size is too large...!", 301);
                }

                // Using an options array:
                $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG,gif,svg,txt,pdf,ppt,doc,docx,xls']);
                // if false than throw type error
                if (!$validator_ext->isValid($_FILES['DownloadFilename'])) {
                    throw new Exception("Please upload valid file type JPG & PNG...!", 301);
                }
                /* File upload validation ends */
                $file_stream = $_FILES['DownloadFilename']['tmp_name'];
                $file_name = $_FILES['DownloadFilename']['name'];
                $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
                $publicDir = getcwd() . "/assets/inventory/download/" . $file_encrypt_name . strstr($file_name, '.');
                $file_upload = $file_encrypt_name . strstr($file_name, '.');
                $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);
            }

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Filename'] = $file_upload;
            $update_data['Updated'] = date('Y-m-d H:i:s');

            $is_updated = (new Download($this->db))->editDownload($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->view->flash([
                    'alert' => 'Download record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $attr_obj = new Download($this->db);
                $all_downloads = $attr_obj->all();
                return $this->view->buildResponse('inventory/download/view', ['all_downloads' => $all_downloads]);
            } else {
                throw new Exception("Failed to update download. Please ensure all input is filled out correctly.", 301);
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
            $cat_obj = new Download($this->db);
            $all_download = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->view->buildResponse('inventory/download/edit', [
                'form' => $methodData, 'all_download' => $all_download
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
        $form_data['Name'] = (isset($form['DownloadName']) && !empty($form['DownloadName'])) ? $form['DownloadName'] : null;
        $form_data['Mask'] = (isset($form['DownloadMask']) && !empty($form['DownloadMask'])) ? $form['DownloadMask'] : null;
        $form_data['StoreId'] = (isset($form['StoreId']) && !empty($form['StoreId'])) ? $form['StoreId'] : 1;
        return $form_data;
    }
}
