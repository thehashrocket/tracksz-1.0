<?php declare(strict_types = 1);

namespace App\Controllers\Category;

use App\Library\Views;
use App\Models\Account\Store;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;
use App\Library\Config;

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
        return $this->view->buildResponse('category/add', []);
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
