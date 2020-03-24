<?php declare (strict_types = 1);

namespace App\Controllers\Marketplace;

use App\Library\Config;
use App\Library\Views;
use App\Library\ValidateSanitize\ValidateSanitize;
use Laminas\Diactoros\ServerRequest;
use PDO;

class MarketplaceController
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
        return $this->view->buildResponse('marketplace/view', []);
    }

    public function add()
    {       
       
        $market_places = Config::get('market_places');
        return $this->view->buildResponse('marketplace/add', ['market_places' => $market_places]);
    }

    public function add_second($marketplace_name = null)
    {   
        $marketplace_name ='Amazone';
        
        $market_price = Config::get('market_price');
        return $this->view->buildResponse('marketplace/add_step_second', ['market_price' => $market_price]);
    }

    public function add_three()
    {
        return $this->view->buildResponse('marketplace/add_step_three');
    }

}
