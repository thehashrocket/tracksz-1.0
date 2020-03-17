<?php declare(strict_types = 1);

namespace App\Controllers\Category;

use App\Library\Views;
use App\Models\Account\Store;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;
use App\Library\Config;
use Laminas\Diactoros\UploadedFile;

class CategoryController
{
    private $view;
    private $db;
    
    public function __construct(Views $view, PDO $db)
    {        
        $this->view = $view;
        $this->db   = $db;
    }
    
    public function view()
    {
        return $this->view->buildResponse('category/view', []);
    }
    
    public function add()
    {       
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->all();  
        return $this->view->buildResponse('category/add', ['all_category' => $all_category]);
    }
    
    public function defaults()
    {
        $settings = require __DIR__.'/../../../config/category.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'), ['Defaults']
        );
        $defaults = json_decode($default['Defaults'], true);
        
        return $this->view->buildResponse('category/defaults', [
            'types'     => $types,
            'settings'  => $settings,
            'defaults'  => $defaults
        ]);
    }

    public function add_Category(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
             $cat_name = (isset($form['categoryNameInput']) && !empty($form['categoryNameInput']))?$form['categoryNameInput']:null;
             $cat_desc = (isset($form['categoryDesc']) && !empty($form['categoryDesc']))?$form['categoryDesc']:null;
             $parent_cat = (isset($form['parentCategory']) && !empty($form['parentCategory']))?$form['parentCategory']:null;
             $cat_level = (isset($form['categoryLevel']) && !empty($form['categoryLevel']))?$form['categoryLevel']:null;
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->insert_category($parent_cat, $cat_level, $cat_name, $cat_desc,null,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'));       
        if (isset($all_category['status']) && $all_category['status'] == true)  {
            $this->view->flash([
                'alert'     => _('Category added successfully..!'),
                'alert_type'=> 'success',
                'defaults'  => $form,
            ]);
        }else{
            $this->view->flash([
                'alert'     => _('Sorry we encountered an issue.  Please try again.'),
                'alert_type'=> 'danger',
                'defaults'  => $form,
            ]);            
        }
        return $this->view->redirect('/category/add');
        exit();
    }
    
    public function updateDefaults(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        if (!Cookie::exists('tracksz_active_store')) {
            $this->view->flash([
                'alert'     => _('Sorry we encountered an issue.  Please try again.'),
                'alert_type'=> 'danger',
                'defaults'  => $form,
            ]);
            return $this->view->redirect('/category/defaults');
        }
        $updated = (new Store($this->db))->updateColumns(
            Cookie::get('tracksz_active_store'),
            ['Defaults' => json_encode($form)]
        );
        var_dump($updated);
        exit();
    }


    public function map_market_products(ServerRequest $request)
    {
        
        $form = $request->getParsedBody();
        $form_files = $request->getUploadedFiles();
        $files_handle = get_class_methods(get_class($form_files['productImage']));
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        $market_stores = Config::get('market_stores');
        $market_attributes = Config::get('market_attributes');
        
        
        if(is_array($market_stores) && (count($market_stores)>0 || !empty($market_stores))) {
            foreach($market_stores as $store_value ) {
                    $market_wise_data = $this->map_market_attributes($form,$market_attributes,$store_value);
                    	echo '<pre>';
                        print_r($market_wise_data);
                        echo '</pre>';
                       
            } // Loops Ends
        }

        die('result ends');
    }

    	/*
         @author    :: Tejas
         @task_id   :: product attributes map
         @task_desc :: product attributes mapping for different market stores
         @params    :: product details array
        */
         public function map_market_attributes($product_details = array(), $predefined_attr = array(), $store_name = ''){
            $res['status'] = false;
            $res['data'] = null;
            $res['store'] = $store_name;
            $res['message'] = 'Market attributes is not set..!';            
            if((is_array($product_details) && is_array($predefined_attr)) && 
                    (!empty($product_details) && !empty($predefined_attr) && 
                    !empty($store_name))){
                    $set_attr = array();  
                    $set_mrg_attr = array();    
                    foreach($product_details as $prod_key=>$prod_val) {
                        $set_attr[$predefined_attr[$prod_key][$store_name]] = $prod_val;
                    } // Loops Ends
                    $res['status'] = true;
                    $res['data'] = $set_attr;
                    $res['message'] = 'Market attributes is set successfully..!';
            }            
            return $res;
        }
}
