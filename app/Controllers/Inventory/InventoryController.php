<?php declare(strict_types = 1);

namespace App\Controllers\Inventory;

use App\Library\Views;
use App\Models\Account\Store;
use App\Models\Inventory\Category;
use Delight\Cookie\Cookie;
use Laminas\Diactoros\ServerRequest;
use PDO;

class InventoryController
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
        return $this->view->buildResponse('inventory/view', []);
    }
    
    public function add()
    {
        return $this->view->buildResponse('inventory/item', []);
    }
    
    public function defaults()
    {
        $settings = require __DIR__.'/../../../config/inventory.php';
        $types = (new Category($this->db))->findParents();
        $default = (new Store($this->db))->columns(
            Cookie::get('tracksz_active_store'), ['Defaults']
        );
        $defaults = json_decode($default['Defaults'], true);
        return $this->view->buildResponse('inventory/defaults', [
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
            return $this->view->redirect('/inventory/defaults');
        }
        $updated = (new Store($this->db))->updateColumns(
            Cookie::get('tracksz_active_store'),
            ['Defaults' => json_encode($form)]
        );
        var_dump($updated);
        exit();
    }
}
