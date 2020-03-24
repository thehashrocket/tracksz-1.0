<?php declare (strict_types = 1);

namespace App\Controllers\Category;

use App\Library\Config;
use App\Library\Views;
use App\Models\Account\Store;
use App\Library\ValidateSanitize\ValidateSanitize;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;

class CategoryController
{
    private $view;
    private $db;

    public function __construct(Views $view, PDO $db)
    {
        $this->view = $view;
        $this->db = $db;    
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

    public function get_list_records(ServerRequest $request)
    {   

        $params = $request->getQueryParams();
        // TDS :: Server side processing starts  
        $cat_obj = new Category($this->db);
        $totalData = $cat_obj->count_all_records();   
        $totalFiltered = (is_array($totalData) && !empty($totalData))?$totalData['total_records']:0;

        $search_params['search'] = $params['search']['value'];        
        $search_params['start'] = $params['start'];
        $search_params['limit'] = $params['length'];
        $search_params['order'] = $params['columns'][$params['order'][0]['column']]['data'];    
        $search_params['dir'] = $params['order'][0]['dir'];
        // echo Config::get('company_url');
        // exit;

        $cat_result = $cat_obj->get_all_records($search_params);
        if(isset($cat_result) && !empty($cat_result)){
            $res['status'] = true;
            $res['message']['success'] = "Policy result get successfully..!";
            $res['message']['error'] = "";
            $res['data'] = $cat_result; 
           // $res['dir_path'] = Config::get('company_url').Config::get('image_path').'product/';
           $res['dir_path'] = Config::get('company_url').'/assets/images/product/';
            $res['recordsFiltered'] = $totalFiltered;
        }else{
            $res['status'] = false;
            $res['message']['success'] = "Policy result get successfully..!";
            $res['message']['error'] = "";
            $res['data'] = array(); 
            $res['recordsFiltered'] = $totalFiltered;
        }
        return die(json_encode($res));      
    }

    public function defaults()
    {
        $settings = require __DIR__ . '/../../../config/category.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'), ['Defaults']
        );
        $defaults = json_decode($default['Defaults'], true);

        return $this->view->buildResponse('category/defaults', [
            'types' => $types,
            'settings' => $settings,
            'defaults' => $defaults,
        ]);
    }

    public function add_Category(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        
        // Sanitize and Validate
        // - Sanitize First to Remove "bad" input
        // - Validate Second, if Sanitize empties a field due to
        //   "bad" data that is required then Validate will catch it.
        $validate = new ValidateSanitize();
        $form = $validate->sanitize($form); // only trims & sanitizes strings (other filters available)
      
        $validate->validation_rules(array(
            'CategoryName'    => 'required',
            'CategoryDescription'       => 'required',            
            'ParentCategory'     => 'required'
        ));

        $validated = $validate->run($form);
        // use validated as it is filtered and validated        
        if ($validated === false) {
            $validated['alert'] = 'Sorry, we could not add your cateogry.  Please try again.';
            $validated['alert_type'] = 'danger';
            $this->view->flash($validated);
            return $this->view->redirect('/category/add');
        }
        // if validated !== false then validated has the filtered data.
        // use validated instead of form now as you see below.
        
        $file_stream = $_FILES['ProductImage']['tmp_name'];
        $file_name = $_FILES['ProductImage']['name'];
        $file_encrypt_name = strtolower(str_replace(" ","_",strstr($file_name,'.',true).date('Ymd_his')));                
        $publicDir = getcwd().'\assets\images\product\\'.$file_encrypt_name.strstr($file_name,'.');                
        $cat_img = $file_encrypt_name.strstr($file_name,'.');
        $is_file_uploaded = move_uploaded_file($file_stream,$publicDir);               
        $cat_name = (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : null;
        $cat_desc = (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : null;
        $parent_cat = (isset($form['ParentCategory']) && !empty($form['ParentCategory'])) ? $form['ParentCategory'] : null;        
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->insert_category($parent_cat, $cat_name, $cat_desc, $cat_img, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
        if (isset($all_category['status']) && $all_category['status'] == true) {
            $this->view->flash([
                'alert' => _('Category added successfully..!'),
                'alert_type' => 'success',
                'defaults' => $form,
            ]);
        } else {
            $this->view->flash([
                'alert' => _('Sorry we encountered an issue.  Please try again.'),
                'alert_type' => 'danger',
                'defaults' => $form,
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
                'alert' => _('Sorry we encountered an issue.  Please try again.'),
                'alert_type' => 'danger',
                'defaults' => $form,
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

    /*
    @author    :: Tejas
    @task_id   ::
    @task_desc ::
    @params    ::
     */
    public function _get_class_info(Request $request)
    {
        $form = $request->getParsedBody();
        $class_methods = get_class_methods(get_class($request));
        // [0] => __construct
        // [1] => getServerParams
        // [2] => getUploadedFiles
        // [3] => withUploadedFiles
        // [4] => getCookieParams
        // [5] => withCookieParams
        // [6] => getQueryParams
        // [7] => withQueryParams
        // [8] => getParsedBody
        // [9] => withParsedBody
        // [10] => getAttributes
        // [11] => getAttribute
        // [12] => withAttribute
        // [13] => withoutAttribute
        // [14] => getRequestTarget
        // [15] => withRequestTarget
        // [16] => getMethod
        // [17] => withMethod
        // [18] => getUri
        // [19] => withUri
        // [20] => getProtocolVersion
        // [21] => withProtocolVersion
        // [22] => getHeaders
        // [23] => hasHeader
        // [24] => getHeader
        // [25] => getHeaderLine
        // [26] => withHeader
        // [27] => withAddedHeader
        // [28] => withoutHeader
        // [29] => getBody
        // [30] => withBody
        // $files = $request->getUploadedFiles();

        $files_methods = get_class_methods(get_class($files['productImage']));
        // [0] => __construct
        // [1] => getStream
        // [2] => moveTo
        // [3] => getSize
        // [4] => getError
        // [5] => getClientFilename
        // [6] => getClientMediaType
    }

    public function map_market_products(ServerRequest $request)
    {

        $form = $request->getParsedBody();
        $form_files = $request->getUploadedFiles();
        $files_handle = get_class_methods(get_class($form_files['productImage']));
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        $market_stores = Config::get('market_stores');
        $market_attributes = Config::get('market_attributes');

        if (is_array($market_stores) && (count($market_stores) > 0 || !empty($market_stores))) {
            foreach ($market_stores as $store_value) {
                $market_wise_data = $this->map_market_attributes($form, $market_attributes, $store_value);
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
    public function map_market_attributes($product_details = array(), $predefined_attr = array(), $store_name = '')
    {
        $res['status'] = false;
        $res['data'] = null;
        $res['store'] = $store_name;
        $res['message'] = 'Market attributes is not set..!';
        if ((is_array($product_details) && is_array($predefined_attr)) &&
            (!empty($product_details) && !empty($predefined_attr) &&
                !empty($store_name))) {
            $set_attr = array();
            $set_mrg_attr = array();
            foreach ($product_details as $prod_key => $prod_val) {
                $set_attr[$predefined_attr[$prod_key][$store_name]] = $prod_val;
            } // Loops Ends
            $res['status'] = true;
            $res['data'] = $set_attr;
            $res['message'] = 'Market attributes is set successfully..!';
        }
        return $res;
    }
}
