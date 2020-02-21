<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Library\Utils;
use App\Library\Views;
use App\Models\Country;
use App\Models\Account\Member;
use Delight\Auth\Auth;
use Laminas\Diactoros\ServerRequest;
use PDO;

class AccountController
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
    
    public function index()
    {
        return $this->view->buildResponse('account/index', []);
    }
    
    public function profile()
    {
        $member  = (new Member($this->db))->find($this->auth->getUserId());
        $countries = (new Country($this->db))->all();
        return $this->view->buildResponse('account/profile', [
                'countries' => $countries,
                'member'    => $member,
                'current_email' => $this->auth->getEmail()
            ]);
    }
    
    public function updateProfile(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        if (!isset($form['Newsletter'])) {
            $form['Newsletter'] = 'off';
        }
        if (!isset($form['Texts'])) {
            $form['Texts'] = 'off';
        }
        
        $member = (new Member($this->db))->updateColumns($form['Id'], $form);
        if (!$member) {
            $data['alert'] = _('Whoops. There was a problem updating your profile. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Your profile is updated.');
            $form['alert_type'] = 'success';
        }
    
        $utils = new Utils();
        $utils->syncLoginSession($this->db, $this->auth->getUserId());
        
        $this->view->flash($form);
        return $this->view->redirect('/account/profile');
    }
    
}