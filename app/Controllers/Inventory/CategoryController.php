<?php declare (strict_types = 1);

namespace App\Controllers\Category;

use App\Library\Config;
use App\Library\Views;
use App\Models\Account\Store;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\UploadedFile;
use Laminas\Diactoros\Stream;
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

    public function get_list_records()
    {
    
    }

    public function defaults()
    {
        $settings = require __DIR__ . '/../../../config/inventory.php';
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
    
    
    /*********** Save for Review - Delete if Not Used ************/
    /***********        Keep at End of File           ************/
    /*
    public function add_Category(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        // $files = $request->getUploadedFiles();
        $file_stream = $_FILES['productImage']['tmp_name'];
        $file_name = $_FILES['productImage']['name'];
        // $file_size = $files['productImage']->getSize();
        // $file_client_name = $files['productImage']->getClientFilename();
        // $file_client_media_type = $files['productImage']->getClientMediaType();
        // $file_get_error = $files['productImage']->getError();
        // $upload_file = new UploadedFile($file_stream,$file_size,$file_get_error,$file_client_name, $file_client_media_type);
        $file_encrypt_name = strtolower(str_replace(" ","_",strstr($file_name,'.',true).date('Ymd_his')));
        //$publicDir = 'D:\xampp\htdocs\tracksz\public\assets\images\product\\'.$file_encrypt_name;
        $publicDir = getcwd().'\assets\images\product\\'.$file_encrypt_name.strstr($file_name,'.');
        $cat_img = $file_encrypt_name.strstr($file_name,'.');
        $is_file_uploaded = move_uploaded_file($file_stream,$publicDir);
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        $cat_name = (isset($form['categoryNameInput']) && !empty($form['categoryNameInput'])) ? $form['categoryNameInput'] : null;
        $cat_desc = (isset($form['categoryDesc']) && !empty($form['categoryDesc'])) ? $form['categoryDesc'] : null;
        $parent_cat = (isset($form['parentCategory']) && !empty($form['parentCategory'])) ? $form['parentCategory'] : null;
        $cat_level = (isset($form['categoryLevel']) && !empty($form['categoryLevel'])) ? $form['categoryLevel'] : 0;
        $cat_obj = new Category($this->db);
        $all_category = $cat_obj->insert_category($parent_cat, $cat_level, $cat_name, $cat_desc, $cat_img, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
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
    } */

    /*
    @author    :: Tejas
    @task_id   ::
    @task_desc ::
    @params    ::
     */
    /* public function _get_class_info(Request $request)
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
    } */

    /*
    @author    :: Tejas
    @task_id   :: product attributes map
    @task_desc :: product attributes mapping for different market stores
    @params    :: product details array
     */
    /* public function map_market_attributes($product_details = array(), $predefined_attr = array(), $store_name = '')
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
    } */
    /*********** End Save for Review Delete ************/
}
