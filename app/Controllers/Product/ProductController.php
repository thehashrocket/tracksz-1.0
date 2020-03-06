<?php declare(strict_types = 1);

namespace App\Controllers\Product;

use App\Library\Views;
use App\Models\Account\Store;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;

class ProductController
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
        return $this->view->buildResponse('product/view', []);
    }
    
    public function add()
    {        
        return $this->view->buildResponse('product/add', []);
    }
    
    public function defaults()
    {
        $settings = require __DIR__.'/../../../config/product.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'), ['Defaults']
        );
        $defaults = json_decode($default['Defaults'], true);
        
        return $this->view->buildResponse('product/defaults', [
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
            return $this->view->redirect('/product/defaults');
        }
        $updated = (new Store($this->db))->updateColumns(
            Cookie::get('tracksz_active_store'),
            ['Defaults' => json_encode($form)]
        );
        var_dump($updated);
        exit();
    }
}
