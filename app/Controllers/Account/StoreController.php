<?php

declare(strict_types=1);

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

class StoreController
{
    private $view;
    private $auth;
    private $db;

    private $storeTypes = [
        'SOLE'  => 'Sole Proprietor/Individual',
        'LLC'   => 'Limited Liability Company',
        'CCORP' => 'C Corporation',
        'SCORP' => 'S Corporation',
        'PART'  => 'Partnership',
        'TRUST' => 'Trust/Estate',
        'EXEMPT' => 'Tax Exempt',

    ];
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
     * stripeConnect - Display the page to allow the store to connect Stripe
     *
     * @return  view - account/stores
     * @throws \Exception
     */
    public function stripeConnect(ServerRequest $request, $form)
    {
        $store = (new Store($this->db))->findId($form['Id'], $this->auth->getUserId());
        if (!$store) {
            $this->view->flash([
                'alert'     => _('There was an issue retrieving this store for Stripe Connect.'),
                'alert_type' => 'danger',
            ]);
            return $this->view->redirect('/account/stores');
        }
        // store state for Connect Link
        Session::set('connect_state', $this->auth->createRandomString(24));
        return $this->view->buildResponse('account/stripe_connect', [
            'StoreName' => $store['DisplayName'],
            'alert'     => false,
            'alert_type' => '',
            'Id'        => $form['Id'],
            'StripeSetup' => $store['StripeSetup']
        ]);
    }
    /**
     * stores - List of all stores owned by this member
     *
     * @return  view - account/stores
     * @throws \Exception
     */
    public function stores()
    {
        $store = new Store($this->db);
        $stores = $store->find($this->auth->getUserId(), 1);
        $deleted = $store->find($this->auth->getUserId(), 0);
        $cards = (new MemberCard($this->db))->find($this->auth->getUserId());
        return $this->view->buildResponse('account/stores', [
            'stores'  => $stores,
            'deleted' => $deleted,
            'cards'   => $cards
        ]);
    }
    /**
     * store - One Store Add form
     *
     * @return  view - account/store
     */
    public function store()
    {
        // Decrypt TaxId
        $encrypt = new Encryption();
        $keys = (new Member($this->db))->gateway($this->auth->getUserId());
        if (trim($keys->GatewayUserId)) {
            $gateway_id = $encrypt->safeDecrypt($keys->GatewayUserId, $keys->MemberKey);
            $cards = (new MemberCard($this->db))->getSyncedCards(
                $this->auth->getUserId(),
                $encrypt,
                new StripeController(),
                $gateway_id,
                $keys->MemberKey
            );
        } else {
            $gateway_id = false;
            $cards = false;
        }
        $store['CountryId'] = Config::get('default_country');
        $store['ZoneId']    = Config::get('default_zone');
        $countries = (new Country($this->db))->all();
        $categories = (new Category($this->db))->all();
        return $this->view->buildResponse('account/store', [
            'MemberId'   => $this->auth->getUserId(),
            'storeTypes' => $this->storeTypes,
            'store'      => $store,
            'cards'     => $cards['card_data'],
            'card_rows' => $cards['card_rows'],
            'countries'  => $countries,
            'categories' => $categories,
        ]);
    }

    /**
     * edit - Edit a Store - same form as add (function store above)
     *
     * @param  $request - Sent by router automatically, not used
     * @param  $form - contains ID variable from get string /:Id
     * @return  view - account/stores
     * @throws \Exception
     */
    public function edit(ServerRequest $request, $form = [])
    {
        $store = (new Store($this->db))->findId($form['Id'], $this->auth->getUserId());
        // Decrypt TaxId
        $encrypt = new Encryption();
        $keys = (new Member($this->db))->gateway($this->auth->getUserId());
        $store['TaxId'] = $encrypt->safeDecrypt($store['TaxId'], $keys->MemberKey);
        $gateway_id = $encrypt->safeDecrypt($keys->GatewayUserId, $keys->MemberKey);
        // add links to returned store array
        $store_links = (new StoreLinks($this->db))->find($form['Id']);
        foreach ($store_links as $key => $val) {
            $store[$key] = $val;
        }

        $cards = (new MemberCard($this->db))->getSyncedCards(
            $this->auth->getUserId(),
            $encrypt,
            new StripeController(),
            $gateway_id,
            $keys->MemberKey
        );
        $countries = (new Country($this->db))->all();
        $categories = (new Category($this->db))->all();
        return $this->view->buildResponse('account/store', [
            'MemberId'   => $this->auth->getUserId(),
            'storeTypes' => $this->storeTypes,
            'store'      => $store,
            'cards'     => $cards['card_data'],
            'card_rows' => $cards['card_rows'],
            'countries'  => $countries,
            'categories' => $categories,
        ]);
    }
    /**
     * storeUpdate - Add/Edit a store after form submission
     *
     * @param  $request - Sent by router on form submit
     * @return  view - account/store
     */
    public function storeUpdate(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do every time.
        // Check if Unique Legal Name, Must be unique for a Member
        $store = new Store($this->db);
        if (!isset($form['Id']) && !$store->isUnique($this->auth->getUserId(), $form['LegalName'], $form['DisplayName'])) {
            $this->view->flash([
                'store'     => $form,
                'alert'     => _('The Legal Name and Display Name must be unique for your account. Check these names and try again.'),
                'alert_type' => 'danger',
            ]);
            return $this->view->redirect('/account/store');
        }
        if (!isset($form['Newsletter'])) {
            $form['Newsletter'] = 'off';
        }
        if (!isset($form['Texts'])) {
            $form['Texts'] = 'off';
        }
        if (!isset($form['TrackszListing'])) {
            $form['TrackszListing'] = 'off';
        }
        // pull links out, they go in StoreLinks table
        $link_form = [];
        foreach (Config::get('link_types') as $key => $val) {
            if (isset($form[$key]) && trim($form[$key])) {
                $link_form[$key] = $form[$key];
            }
            unset($form[$key]);
        }
        // encrypt TaxId
        $member = new Member($this->db);
        $keys = $member->gateway($this->auth->getUserId());
        $form['TaxId'] = (new Encryption())->safeEncrypt($form['TaxId'], $keys->MemberKey);
        // add or edit
        $store_id = '';
        $store_action = 'add';
        if (isset($form['Id'])) {
            $store_action = 'updat';
            $store_id = $form['Id'];
            $new_store = $store->updateColumns($form['Id'], $form);
        } else {
            $new_store = $store->addStore($form);
            $store_id = $new_store;
            // Make New Store Active Store
            $member->updateColumns($this->auth->getUserId(), ['ActiveStore' => $store_id]);
            Cookie::setcookie('tracksz_active_store', $store_id, time() + 2419200, '/');
            Cookie::setcookie('tracksz_active_name', $form['DisplayName'], time() + 2419200, '/');
        }
        if (!$new_store) {
            $form['alert'] = _('Whoops. There was a problem ' . $store_action . 'ing your store. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Your store was ' . $store_action . 'ed.');
            $form['alert_type'] = 'success';
        }
        // update links
        $links = (new StoreLinks($this->db))->replaceLinks($store_id, $link_form);
        if ($form['TrackszListing'] == 'on') {
            // check if Store has Stripe Setup
            if (!$store->stripeSetup($store_id, $this->auth->getUserId())) {
                // store state for Connect Link
                Session::set('connect_state', $this->auth->createRandomString(24));
                return $this->view->buildResponse('account/stripe_connect', [
                    'StoreName' => $form['DisplayName'],
                    'alert'     => $form['alert'],
                    'alert_type' => $form['alert_type'],
                    'Id'        => $store_id,
                    'StripeSetup' => 0
                ]);
            }
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/stores');
    }
    /**
     * delete - Delete a Store - changes IsActive to 0 then do not show in lists.
     *
     * @param  $request - Sent by router,not used
     * @param  $form - contains ID variable from get string /:Id
     * @return Sends to Store Listing Page
     */
    public function delete(ServerRequest $request, $form = [])
    {
        if (Cookie::get('tracksz_active_store') == $form['Id']) {
            $form['alert'] = _('Cannot Delete Active Store. Change active store and try again.');
            $form['alert_type'] = 'danger';
            $this->view->flash($form);
            return $this->view->redirect('/account/stores');
        }
        $delete = (new Store($this->db))->deleteRestore($form['Id'], $this->auth->getUserId(), 0);
        if (!$delete) {
            $form['alert'] = _('There was a problem deleting this store. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Store deleted.');
            $form['alert_type'] = 'success';
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/stores');
    }
    /**
     * restore - Restore a Store - changes IsActive to 1 then show on list again.
     *
     * @param  $request - Sent by router,not used
     * @param  $form - contains ID variable from get string /:Id
     * @return Sends to Store Listing Page
     */
    public function restore(ServerRequest $request, $form = [])
    {
        $delete = (new Store($this->db))->deleteRestore($form['Id'], $this->auth->getUserId(), 1);
        if (!$delete) {
            $form['alert'] = _('There was a problem restoriong this store. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Store Restored and Is Active Again.');
            $form['alert_type'] = 'success';
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/stores');
    }
    /**
     * setActive - Set Member's Active Store - all administration applies to current active store
     *
     * @param  $request - Sent by router,not used
     * @param  $form - contains ID variable from get string /:Id
     * @return Sends to Store Listing Page
     */
    public function setActive(ServerRequest $request, $form = [])
    {
        $active = (new Member($this->db))->updateColumns($this->auth->getUserId(), ['ActiveStore' => $form['Id']]);
        if (!$active) {
            $form['alert'] = _('There was a problem making htis store active. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Active Store Changed');
            $form['alert_type'] = 'success';
        }
        Cookie::setcookie('tracksz_active_store', $form['Id'], time() + 2419200, '/');
        $active_store = (new Store($this->db))->findId($form['Id'], $this->auth->getUserId());
        if ($active_store) {
            Cookie::setcookie('tracksz_active_name', $active_store['DisplayName'], time() + 2419200, '/');
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/stores');
    }
    /**
     * connect - Finalize the Stripe Connect to a new account
     *
     * @param  $request - Sent by router with form fields.
     * @param  $form - array of arguments sent by router
     * @return boolean -
     */
    public function connect(ServerRequest $request, $args)
    {
        parse_str($args['response'], $form);
        if (isset($form['error']) || !isset($form['code'])) {
            $data['alert'] = _('There was a problem with the response from Stripe Connect, please try again.');
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('/account/stripe_connect');
        };
        $utils = new Utils();
        $data = [
            'client_secret' => getenv('STRIPE_SECRET'),
            'code'          => $form['code'],
            'grant_type'    => 'authorization_code'
        ];
        $response = $utils->curlPost(getenv('STRIPE_OAUTH_URL'), $data);
        if (!$response) {
            $data['alert'] = _('There was a problem with the response from Stripe Connect, please try again.');
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('/account/stripe_connect');
        }
        // need customer key to encrypt results and save in Store Table
        $encrypt   = new Encryption();
        $keys      = (new Member($this->db))->gateway($this->auth->getUserId());
        $response_values = json_decode($response);
        $stripe_data = [
            'StripeSetup'        => 1,
            'StripeAccessToken'  => $encrypt->safeEncrypt($response_values->access_token, $keys->MemberKey),
            'StripeTokenType'    => $response_values->token_type,
            'StripeRefreshToken' => $encrypt->safeEncrypt($response_values->refresh_token, $keys->MemberKey),
            'StripePublishableKey' => $encrypt->safeEncrypt($response_values->stripe_publishable_key, $keys->MemberKey),
            'StripeUserId'       => $encrypt->safeEncrypt($response_values->stripe_user_id, $keys->MemberKey),
        ];
        $store = new Store($this->db);
        if (!$store->updateColumns(Cookie::get('tracksz_active_store'), $stripe_data)) {
            $data['alert'] = _('There was a problem recording your Stripe Connect information. Please contact us.');
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('/account/stripe_connect');
        }
        $data['alert'] = _('You have successfully setup Stripe Connect. It is time to get going.');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/stores');
    }
}
