<?php

declare(strict_types=1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Delight\Cookie\Session;
use Laminas\Validator\File\FilesSize;
use Laminas\Validator\File\Extension;
use App\Models\Inventory\Category;
use Laminas\Diactoros\ServerRequest;
use PDO;
use Exception;

class CategoryController
{
    private $browse;
    private $db;

    /*
    * __construct - 
    * @param  $form  - Default View, PDO db   
    * @return set data
    */
    public function __construct(Views $browse, PDO $db)
    {
        $this->browse = $browse;
        $this->db = $db;
        error_reporting(0);
    }

    /*
    * add - Load Add Category View
    * @param  $form  - Id    
    * @return boolean load browse with pass data
    */
    public function add()
    {
        $cat_obj = new Category($this->db);
        $all_parent_category = $cat_obj->findParent_id(Session::get('auth_user_id'));

        return $this->browse->buildResponse('inventory/category/add', ['all_parent_category' => $all_parent_category]);
    }

     public function add_parent_category()
    {
        return $this->browse->buildResponse('inventory/category/add_parent_category');
    }

    /*
    * addCategory - 
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addCategory(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'CategoryName'    => 'required',
                'CategoryDescription'       => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }
            /* File upload validation starts */
            if (isset($_FILES['CategoryImage']['error']) && $_FILES['CategoryImage']['error'] > 0) {
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
            $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG']);
            // if false than throw type error
            if (!$validator_ext->isValid($_FILES['CategoryImage'])) {
                throw new Exception("Please upload valid file type JPG & PNG...!", 301);
            }
            /* File upload validation ends */

            $file_stream = $_FILES['CategoryImage']['tmp_name'];
            $file_name = $_FILES['CategoryImage']['name'];
            $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
            $publicDir = getcwd() . "/assets/images/category/" . $file_encrypt_name . strstr($file_name, '.');
            $cat_img = $file_encrypt_name . strstr($file_name, '.');
            $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);

            $insert_data = $this->PrepareInsertData($form);
            $insert_data['Image'] = $cat_img;
            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->addCateogry($insert_data);

            if (isset($all_category) && !empty($all_category)) {
                $this->browse->flash([
                    'alert' => _('Category added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->browse->redirect('/category/add');
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
            $this->browse->flash($validated);

            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->browse->buildResponse('/inventory/category/add', ['all_category' => $all_category, 'form' => $form]);
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
        $form_data['Name'] = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : null;
        $form_data['Description'] = (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : null;
        $form_data['ParentId'] = $form_data['ParentId'] = (isset($form['ParentCategory']) && !empty($form['ParentCategory'])) ? $form['ParentCategory'] : 0;
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }

    /*
    * browse - Load List Category View
    * @param  none 
    * @return boolean load browse with pass data
    */
    public function browse()
    {
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->browse->buildResponse('inventory/category/browse', ['all_category' => $all_category]);
    }
   

   public function parent_category()
   {

        $cat_obj = new Category($this->db);
        $all_parent_category = $cat_obj->get_all_parent_ActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        return $this->browse->buildResponse('inventory/category/parent_category', ['all_parent_category' => $all_parent_category]);

   }

    /*
    * deleteCategoryData - Delete Category Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteCategoryData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Category($this->db))->delete($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Category record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->browse->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Category records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->browse->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';
            echo json_encode($res);
            exit;
        }
    }

    /*
    * editCategory - Load Edit Category View
    * @param  $form  - Id    
    * @return boolean load browse with pass data
    */
    public function editCategory(ServerRequest $request, $Id = [])
    {
        $form = (new Category($this->db))->findById($Id['Id']);
        $cat_obj = new Category($this->db);
      
        $all_parent_category = $cat_obj->findParent_id(Session::get('auth_user_id'));

        $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        if (is_array($form) && !empty($form)) {
            return $this->browse->buildResponse('inventory/category/edit', [
                'form' => $form, 'all_parent_category' => $all_parent_category
            ]);
        } else {
            $this->browse->flash([
                'alert' => 'Failed to fetch Category details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->browse->buildResponse('inventory/category/browse', ['all_category' => $all_category]);
        }
    }

    /*
    * updateCategory - Update Category data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateCategory(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        
            $cat_img = $methodData['CategoryImageHidden'];
            /* File upload validation starts */
            if (isset($_FILES['CategoryImage']['error']) && $_FILES['CategoryImage']['error'] == 0) {

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
                if (!$validator_ext->isValid($_FILES['CategoryImage'])) {
                    throw new Exception("Please upload valid file type JPG & PNG...!", 301);
                }
                /* File upload validation ends */
                $file_stream = $_FILES['CategoryImage']['tmp_name'];
                $file_name = $_FILES['CategoryImage']['name'];
                $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
                $publicDir = getcwd() . "/assets/images/category/" . $file_encrypt_name . strstr($file_name, '.');
                $cat_img = $file_encrypt_name . strstr($file_name, '.');
                $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);
            }

            $update_data = $this->PrepareUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');
            $update_data['Image'] = $cat_img;

            $is_updated = (new Category($this->db))->editCategory($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->browse->flash([
                    'alert' => 'Category record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                $cat_obj = new Category($this->db);
                $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
                return $this->browse->buildResponse('inventory/category/browse', ['all_category' => $all_category]);
                unlink(getcwd() . "/assets/images/category/" . $methodData['CategoryImageHidden']);
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
            $this->browse->flash($validated);
            $cat_obj = new Category($this->db);
            $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
            return $this->browse->buildResponse('inventory/category/edit', [
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
        $form_data['Name'] = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : null;
        $form_data['Description'] = (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : null;
        $form_data['ParentId'] = (isset($form['ParentCategory']) && !empty($form['ParentCategory'])) ? $form['ParentCategory'] : 0;
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }

      public function addParentCategory(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.      
        try {
            // Sanitize and Validate
            $validate = new ValidateSanitize();
            $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
            $validate->validation_rules(array(
                'ParentCategoryDescription'       => 'required',
            ));

            $validated = $validate->run($form);
            // use validated as it is filtered and validated        
            if ($validated === false) {
                throw new Exception("Please enter required fields...!", 301);
            }
            /* File upload validation starts */
            if (isset($_FILES['ParentCategoryImage']['error']) && $_FILES['ParentCategoryImage']['error'] > 0) {
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
            $validator_ext = new Extension(['png,jpg,PNG,JPG,jpeg,JPEG']);
            // if false than throw type error
            if (!$validator_ext->isValid($_FILES['ParentCategoryImage'])) {
                throw new Exception("Please upload valid file type JPG & PNG...!", 301);
            }
            /* File upload validation ends */

            $file_stream = $_FILES['ParentCategoryImage']['tmp_name'];
            $file_name = $_FILES['ParentCategoryImage']['name'];
            $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
            $publicDir = getcwd() . "/assets/images/category/" . $file_encrypt_name . strstr($file_name, '.');
            $cat_img = $file_encrypt_name . strstr($file_name, '.');
            $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);

            $insert_data = $this->PrepareInsertParentData($form);
            $insert_data['Image'] = $cat_img;
             $cat_obj = new Category($this->db);
             $all_category = $cat_obj->addParentCateogry($insert_data);

            if (isset($all_category) && !empty($all_category)) {
                $this->browse->flash([
                    'alert' => _('Parent Category added successfully..!'),
                    'alert_type' => 'success'
                ]);
                return $this->browse->redirect('/category/add_parent_category');
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
            $this->browse->flash($validated);
           return $this->browse->buildResponse('/inventory/category/add_parent_category');
        }
    }
    
    private function PrepareInsertParentData($form = array())
    {
        $form_data = array();
        $form_data['Name'] = (isset($form['ParentCategoryName']) && !empty($form['ParentCategoryName'])) ? $form['ParentCategoryName'] : null;
        $form_data['Description'] = (isset($form['ParentCategoryDescription']) && !empty($form['ParentCategoryDescription'])) ? $form['ParentCategoryDescription'] : null;
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }


     /*
    * editCategory - Load Edit Parent Category View
    * @param  $form  - Id    
    * @return boolean load browse with pass data
    */
    public function editParentCategory(ServerRequest $request, $Id = [])
    {
        $form = (new Category($this->db))->findById_parentcategory($Id['Id']);
       
       // $cat_obj = new Category($this->db);
       // $all_category = $cat_obj->getActiveUserAll(Session::get('auth_user_id'), [0, 1]);
        if (is_array($form) && !empty($form)) {
            return $this->browse->buildResponse('inventory/category/edit_parent_category', [
                'form' => $form]);
        } else {
            $this->browse->flash([
                'alert' => 'Failed to fetch Category details. Please try again.',
                'alert_type' => 'danger'
            ]);
            return $this->browse->buildResponse('inventory/category/parent_category');
        }
    }

     /*
    * updateCategory - Update Parent Category data
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names   
    * @return boolean 
    */
    public function updateParentCategory(ServerRequest $request, $Id = [])
    {
        try {
            $methodData = $request->getParsedBody();
            unset($methodData['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.        
            $cat_img = $methodData['CategoryImageHidden'];
            /* File upload validation starts */
            if (isset($_FILES['ParentCategoryImage']['error']) && $_FILES['ParentCategoryImage']['error'] == 0) {

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
                if (!$validator_ext->isValid($_FILES['ParentCategoryImage'])) {
                    throw new Exception("Please upload valid file type JPG & PNG...!", 301);
                }
                /* File upload validation ends */
                $file_stream = $_FILES['ParentCategoryImage']['tmp_name'];
                $file_name = $_FILES['ParentCategoryImage']['name'];
                $file_encrypt_name = strtolower(str_replace(" ", "_", strstr($file_name, '.', true) . date('Ymd_his')));
                $publicDir = getcwd() . "/assets/images/category/" . $file_encrypt_name . strstr($file_name, '.');
                $cat_img = $file_encrypt_name . strstr($file_name, '.');
                $is_file_uploaded = move_uploaded_file($file_stream, $publicDir);
            }

            $update_data = $this->PrepareParentUpdateData($methodData);
            $update_data['Updated'] = date('Y-m-d H:i:s');
            $update_data['Image'] = $cat_img;

            $is_updated = (new Category($this->db))->editParentCategory($update_data);
            if (isset($is_updated) && !empty($is_updated)) {
                $this->browse->flash([
                    'alert' => 'Parent Category record updated successfully..!',
                    'alert_type' => 'success'
                ]);
                  $cat_obj = new Category($this->db);
                $all_parent_category = $cat_obj->get_all_parent_ActiveUserAll(Session::get('auth_user_id'), [0, 1]);
                return $this->browse->buildResponse('inventory/category/parent_category', ['all_parent_category' => $all_parent_category]);
                unlink(getcwd() . "/assets/images/category/" . $methodData['CategoryImageHidden']);
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
            $this->browse->flash($validated);
            return $this->browse->buildResponse('inventory/category/edit_parent_category', [
            'form' => $methodData]);
        }
    }


     /*
    * PrepareUpdateData - Assign Value to new array and prepare update data    
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return array
    */
    private function PrepareParentUpdateData($form = array())
    {
        $form_data = array();
        $form_data['Id'] = (isset($form['Id']) && !empty($form['Id'])) ? $form['Id'] : null;
        $form_data['Name'] = (isset($form['ParentCategoryName']) && !empty($form['ParentCategoryName'])) ? $form['ParentCategoryName'] : null;
        $form_data['Description'] = (isset($form['ParentCategoryDescription']) && !empty($form['ParentCategoryDescription'])) ? $form['ParentCategoryDescription'] : null;
        $form_data['Status'] = (isset($form['Status']) && !empty($form['Status'])) ? $form['Status'] : 0;
        $form_data['UserId'] = (isset($form['UserId']) && !empty($form['UserId'])) ? $form['UserId'] : Session::get('auth_user_id');
        return $form_data;
    }


     /*
    * deleteCategoryData - Delete Parent Category Data By Id    
    * @param  $form  - Id    
    * @return boolean
    */
    public function deleteParentCategoryData(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $result_data = (new Category($this->db))->delete_parent($form['Id']);
        $parent_data = (new Category($this->db))->delete_parentId($form['Id']);
        if (isset($result_data) && !empty($result_data)) {
            $validated['alert'] = 'Parent Category record deleted successfully..!';
            $validated['alert_type'] = 'success';
            $this->browse->flash($validated);

            $res['status'] = true;
            $res['data'] = array();
            $res['message'] = 'Records deleted successfully..!';
            echo json_encode($res);
            exit;
        } else {
            $validated['alert'] = 'Sorry, Category records not deleted..! Please try again.';
            $validated['alert_type'] = 'danger';
            $this->browse->flash($validated);

            $res['status'] = false;
            $res['data'] = array();
            $res['message'] = 'Records not Deleted..!';
            echo json_encode($res);
            exit;
        }
    }



}
