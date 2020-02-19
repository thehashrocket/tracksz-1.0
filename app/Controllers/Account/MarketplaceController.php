<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Controllers\Payments\StripeController;
use App\Library\Config;
use App\Library\Encryption;
use App\Library\Utils;
use App\Library\Views;
use App\Models\Account\Member;
use App\Models\Account\MemberCard;
use App\Models\Account\Store;
use App\Models\Account\StoreLinks;
use App\Models\Inventory\Category;
use App\Models\Country;
use Delight\Auth\Auth;
use Delight\Cookie\Cookie;
use Delight\Cookie\Session;
use Laminas\Diactoros\ServerRequest;
use PDO;

class MarketplaceController
{
    private $view;
    private $auth;
    private $db;
    
    /**
     * _construct - create object
     *
     * @param  $view PHP Plates template object
     * @param  $auth Delight Auth authorization object
     * @param  $db PDO object from service provider
     * @return no return
     */
    public function __construct(Views $view, Auth $auth, PDO $db)
    {
        $this->view = $view;
        $this->auth = $auth;
        $this->db   = $db;
    }
    
    /**
     *  marketplaces - Show list of marketplaces associated with this member
     * 
     *  @return view - /account/marketplaces
     */
    public function marketplaces()
    {
        return $this->view->buildResponse('account/marketplaces', []);
    }
}