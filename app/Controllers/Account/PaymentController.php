<?php declare(strict_types = 1);

namespace App\Controllers\Account;

use App\Controllers\Payments\StripeController;
use App\Library\Config;
use App\Library\Encryption;
use App\Library\Views;
use App\Models\Account\Address;
use App\Models\Account\Member;
use App\Models\Account\MemberCard;
use App\Models\Country;
use App\Models\Zone;
use Delight\Auth\Auth;
use Laminas\Diactoros\ServerRequest;
use PDO;

class PaymentController
{
    private $view;
    private $auth;
    private $db;
    private $gateway;      // link to the Payments controller we are using.
    private $gateway_id;   // member's user id at Payment Gateway
    private $member_key; // Encryption key for this member
    private $member;     // Member Model for data
    private $encrypt;      // Encryption Library for data encrypt/decrypt
    
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
    
        // currently using Stripe. Can add options for gateways in the future
        $this->gateway = new StripeController();
        $this->encrypt = new Encryption();
    
        // get gateway id and member key
        $this->member = new Member($this->db);
        $keys = $this->member->gateway($this->auth->getUserId());
        if ($keys) {
            $this->member_key = $keys->MemberKey;
            if ($keys->GatewayUserId) {
                $this->gateway_id = $this->encrypt->safeDecrypt($keys->GatewayUserId, $this->member_key);
            }
        }
    }
    
    public function payment()
    {
        $member = (new Member($this->db))->find($this->auth->getUserId());
        $countries = (new Country($this->db))->all();
        $addresses = (new Address($this->db))->find($this->auth->getUserId());
        $cards = (new MemberCard($this->db))->getSyncedCards(
            $this->auth->getUserId(), $this->encrypt,
            $this->gateway, $this->gateway_id,
            $this->member_key
        );
        
        // show delete button for address if not used on a card
        $address_used = [];
        if (isset($cards['card_rows'])) {
            foreach ($cards['card_rows'] as $card) {
                $address_used[$card['AddressId']] = 1;
            }
        }

        $region['CountryId'] = Config::get('default_country');
        $region['ZoneId']    = Config::get('default_zone');
        return $this->view->buildResponse('account/payment', [
            'countries' => $countries,
            'member'    => $member,
            'cards'     => $cards['card_data'],
            'card_rows' => $cards['card_rows'],
            'addresses' => $addresses,
            'address_used' => $address_used,
            'region'    => $region
        ]);
    }
    
    /**
     * addPaymentMethod - IMPORTANT! ADDING A NEW CARD IS A THREE STEP PROCESS!
     *           CreateMember if no Gateway Id in Member Record
     *           Add card using token from form
     *           Update Card Details with name and address to get appropriate risk checks
     *
     * @return  createMember if needed, then go to addMemberCard
     * @throws \Exception
     */
    public function addPaymentMethod(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
        
        // Get full address information
        // IF not a new address, get address from Address Table
        $addresses = new Address($this->db);
        if (isset($form['NewAddressId'])) {
            $address = $addresses->findById($form['NewAddressId']);
            $form['Address1'] = $address['Address1'];
            $form['Address2'] = $address['Address2'];
            $form['City']     = $address['City'];
            $form['PostCode'] = $address['PostCode'];
            $form['ZoneId']   = $address['ZoneId'];
            $form['CountryId']= $address['CountryId'];
            $form['FullName'] = $address['FullName'];
            $form['AddressId'] = $form['NewAddressId'];
        } else {
            // Add New Address to Address Table
            // Have to create new array so we do not loose form data
            // and do not get PDO error with too many bound arguments.
            $address['MemberId'] = $this->auth->getUserId();
            $address['Company']  = '';
            $address['Address1'] = trim($form['Address1']);
            $address['Address2'] = trim($form['Address2']);
            $address['City']     = trim($form['City']);
            $address['PostCode'] = trim($form['PostCode']);
            $address['ZoneId']   = trim($form['ZoneId']);
            $address['CountryId']= trim($form['CountryId']);
            $address['FullName'] = trim($form['FullName']);
            
            $form['AddressId'] = $addresses->addAddress($address);
        }
        
        // Get State Code and Country Abbreviation
        $values = $this->getStateCountry($form['CountryId'],$form['ZoneId']);
        $form['Zone']    = $values['Zone'];
        $form['Country'] = $values['Country'];
        
        // if no GatewayUserID in Member table, member never added card yet
        // create a new member at gateway
        if (!$this->gateway_id ) {
            $result = $this->gateway->createMember($form, $this->auth->getEmail());
            if (!$result) {
                $form['Id'] = 'add';
                $this->view->flash(['card' => $form,
                    'alert' => _('There was a problem adding your card. Please try again.'),
                    'alert_type' => 'danger']);
                return $this->view->redirect('/account/payment');
                
            }
            // add gateway_user_id to Member
            $this->gateway_id = $result->id;
            $this->member->addGatewayId($this->auth->getUserId(), $this->encrypt->safeEncrypt($result->id, $this->member_key));
        }
        return $this->addMemberCard($form);
    }
    
    /**
     * addMemberCard - Member has gateway user id, add card
     *
     * @param $form - from form on Member Add Card page
     * @return either error to add card form or updateCardDetails
     */
    public function addMemberCard($form)
    {
        $result = $this->gateway->addMemberCard($form, $this->gateway_id);
        if (!$result) {
            $form['Id'] = 'add';
            $this->view->flash(['card' => $form,
                'alert' => _('There was a problem adding your card or the card is already saved. Please check your information and try again.'),
                'alert_type' => 'danger']);
            return $this->view->redirect('/account/payment');
        } else {
            if (!is_object($result) && substr($result, 0, 4) == 'card') {
                // Card not added, duplicate card id returned.
                // check if card in table. If deleted, turn back on
                $member_card = new MemberCard($this->db);
                $cards = $member_card->findAll($this->auth->getUserId());
                foreach ($cards as $card) {
                    $gatewayId = $this->encrypt->safeDecrypt($card['GatewayPaymentId'], $this->member_key);
                    if ($gatewayId == $result) {
                        if ($card['IsDeleted'] !== 0) {
                            // found card, turn off IsDeleted
                            $member_card->updateColumns($card['Id'], ['IsDeleted' => 0]);
                            return $this->updateCardDetails($form, $gatewayId, true, $card['Id']);
                        }
                    }
                } // end foreach
            }
        }
        
        return $this->updateCardDetails($form, $result->id, false, 0);
    }
    
    /**
     * updateCardDetails - Card Added to stripe now add Address Info
     *                     ** cannot find a way to add details without
     *                        have to update details after card created
     *
     * @param $form - from form on Member Add Card page
     * @param $result - results from gateway create card functino
     * @return view - member/card - failure or member/cards success
     */
    public function updateCardDetails($form, $card_id, $edit=false, $current_membercard=0)
    {
        $result = $this->gateway->updateCardDetails($this->gateway_id, $form, $card_id);
        if (!$result) {
            // delete card at payment gateway, send back to add form
            $this->gateway->deleteMemberCard($this->gateway_id, $card_id);
            $this->view->flash(['card' => $form,
                'alert' => _('There was a problem adding your card. ' . $result['error']['message']),
                'alert_type' => 'danger']);
            return $this->view->redirect('/account/payment');
        }
        // add new card to payments table
        $card_values = [
            'GatewayPaymentId' => $this->encrypt->safeEncrypt($card_id, $this->member_key),
            'CvcCheck'   => $result->cvc_check,
            'StreetCheck'=> $result->address_line1_check,
            'ZipCheck'   => $result->address_zip_check,
            'MemberId'   => $this->auth->getUserId(),
            'AddressId'  => $form['AddressId'],
        ];
        if ($edit) {
            // was duplicate card, let's update it
            return $this->editCardTable($form, $card_values, $current_membercard);
        }
        return $this->addToTable($form, $card_values, $card_id);
    }
    
    /**
     * addToTable - Add a new card to the MemberCardTable
     *
     * @param $form - original form name, address, zip
     * @param $card_values - values from stripe api results
     * @return view - member/card - failure or member/cards success
     */
    public function addToTable($form, $card_values, $payment_id)
    {
        $card  = new MemberCard($this->db);
        if (!$card->addCard($card_values)) {
            $this->gateway->deleteMemberCard($this->gateway_id, $payment_id);
            // have to delete source at payment gateway (stripe?)
            $this->view->flash(['card' => $form,
                'alert' => _('There was a problem adding your card. Please try again.'),
                'alert_type' => 'danger']);
            return $this->view->redirect('/account/payment');
        }
        $data['alert']      = _('Your card is added.');
        $address_valid = true;
        if ($card_values['ZipCheck'] !== 'pass') {
            $data['alert'] .= _(' Zip/Code did not pass validation.');
            $address_valid = false;
        }
        if ($card_values['StreetCheck'] !== 'pass') {
            $data['alert'] .= _(' Street address did not pass validation. ');
            $address_valid = false;
        }
        if (!$address_valid) {
            $data['alert'] .= _(' <br>Although this card was added, address validation must pass before you may use this card.  Please correct the address information. If it looks correct, check your billing address with the credit card company.');
            $data['alert_type'] = 'warning';
            $this->view->flash($data);
            return $this->view->redirect('/account/payment');
        }
    
        $data['alert_type']= 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/payment');
    }
    
    
    /**
     * editCardTable - Edit a card that was entered twice
     *              duplicated at gateway
     *
     * @param $form - original form name, address, zip
     * @param $card_values - values from stripe api results
     * @return view - member/card - failure or member/cards success
     */
    public function editCardTable($form, $card_values, $payment_id)
    {
        $card_values['Updated'] = date('Y-m-d H:i:s');
        $card_values['Id']      = $payment_id;
        $card_values['MemberId']= $this->auth->getUserId();
        $card  = new MemberCard($this->db);
        if (!$card->editCard($card_values)) {
            $this->view->flash(['card' => $form,
                'alert' => _('This card was already entered and was updated at the processor.  It did not update local information. Please check your data and ensure it is accurate.'),
                'alert_type' => 'warning']);
            return $this->view->redirect('/account/payment');
        }
        
        $address = new Address($this->db);
        $data['alert']      = _('Card and address updated.');
        $address_valid = true;
        if ($card_values['ZipCheck'] !== 'pass') {
            $data['alert'] .= _(' Zip/Code did not pass validation.');
            $address_valid = false;
        }
        if ($card_values['StreetCheck'] !== 'pass') {
            $data['alert'] .= _(' Street address did not pass validation. ');
            $address_valid = false;
        }
        if (!$address_valid) {
            $data['alert'] .= _(' <br>Although this card was edited, address validation must pass before you may use this card.  Please correct the address information. If it looks correct, check your billing address with the credit card company.');
            $data['alert_type'] = 'warning';
            $this->view->flash($data);
            return $this->view->redirect('/account/payment');
        }
        
        $data['alert_type']= 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/payment');
    }
    
    /**
     * delete - Delete a User's Payment Card - changes IsDeleted to 1 then do not show
     *          members in any list.  Save for historical records (orders mainly).
     *          Do not delete from payment gateway.
     *
     * @param  $request - Sent by router,not used
     * @param  $form - contains ID variable from get string /:Id
     * @return Sends to Card Listing page
     */
    public function delete(ServerRequest $request, $form=[])
    {
        $deleted = true;
        $cards = new MemberCard($this->db);
        $card  = $cards->findByCardId($form['Id']);
        if (!$card) {
            $deleted = false;
        }
        if ($deleted && $card['IsBilling'] > 0) {
            $form['alert'] = _('Cannot deleted a store\'s billing cards. Change the billing card for your store first.');
            $form['alert_type'] = 'danger';
            $this->view->flash($form);
            return $this->view->redirect('/account/payment');
        }
        
        if (!$cards->delete($form['Id'], $this->auth->getUserId())) {
            $deleted = false;
        }
        if (!$deleted) {
            $form['alert'] = _('There was a problem deleting this card. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Card deleted.');
            $form['alert_type'] = 'success';
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/payment');
    }
    
    /**
     * deleteAddress - Delete an Address - changes IsDeleted to 1 then do not show
     *                 in lists.
     *
     * @param  $request - Sent by router,not used
     * @param  $form - contains ID variable from get string /:Id
     * @return Sends to Card Listing page
     */
    public function deleteAddress(ServerRequest $request, $form=[])
    {
        $delete = true;
        $cards = (new MemberCard($this->db))->find($this->auth->getUserId());
        foreach($cards as $card) {
            if ($card['AddressId'] == $form['Id']) {
                $delete = false;
                break;
            }
        }
        
        if ($delete) {
            $delete = (new Address($this->db))->delete($form['Id'], $this->auth->getUserId());
        }
        if (!$delete) {
            $form['alert'] = _('There was a problem deleting this address or it is still being used for a payment method. Please try again.');
            $form['alert_type'] = 'danger';
        } else {
            $form['alert'] = _('Address deleted.');
            $form['alert_type'] = 'success';
        }
        $this->view->flash($form);
        return $this->view->redirect('/account/payment');
    }
    
    /**
     * edit - Card updated at stripe
     *
     * @param  $request - Sent by router,not used
     * @param  args - Array of input from url /payment/edit/{Id}....this is credit card record ID
     * @return view - customer/card - failure or customer/cards success
     */
    public function edit(ServerRequest $request, $args)
    {
        $form = $request->getParsedBody();
        unset($form['__token']); // remove CSRF token or PDO bind fails, too many arguments, Need to do everytime.
    
        $address['FullName'] = trim($form['FullName']);
        $address['Company']  = '';
        $address['Address1'] = trim($form['Address1']);
        $address['Address2'] = trim($form['Address2']);
        $address['City']     = trim($form['City']);
        $address['PostCode'] = trim($form['PostCode']);
        $address['ZoneId']   = trim($form['ZoneId']);
        $address['CountryId']= trim($form['CountryId']);
        $address['MemberId'] = $this->auth->getUserId();
        
        $cards = (new MemberCard($this->db));
        $cards_using = $cards->countAddressId($form['AddressId']);
        if ($cards_using > 1) {
            // if card is used by another card, check if any changes,
            // if changes, add new address and return new address Id
            $form['AddressId'] = (new Address($this->db))->addOrUpdate($address, $form['AddressId']);
        } else {
            $address['Id'] = $form['AddressId'];
            $form['AddressId'] = (new Address($this->db))->editAddress($address);
        }
    
        if (!$form['AddressId']) {
            $form['alert'] = _('Unable to update Address.  Please try again.');
            $form['alert_type'] = 'danger';
            $this->view->flash($form);
            return $this->view->redirect('/account/payment');
        }
    
        // Get State Code and Country Abbreviation
        $values = $this->getStateCountry($form['CountryId'],$form['ZoneId']);
        $form['Zone'] = $values['Zone'];
        $form['Country'] = $values['Country'];
        
        $card = $cards->findByCardId($args['Id']);
        $paymentId = $this->encrypt->safeDecrypt($card['GatewayPaymentId'], $this->member_key);
        
        return $this->updateCardDetails($form, $paymentId, true, $args['Id']);
    }
    
    /**
     * getStateCountryIds - get state and country format required for
     *                      gateway (stripe connect);
     *
     * @param  $countryId - value of Id column.
     * @param  $zoneId - value of Zone Id Column
     * @return array - contains Country IsoCode2 and Zone Code (AL, AR, MO, etc)
     * @throws \Exception
     */
    public function getStateCountry($countryId, $zoneId)
    {
        $values = [];
        // Get State Code and Country Abbreviation
        $zones = new Zone($this->db);
        $zone  = $zones->findByZone($zoneId);
        $values['Zone'] = $zone[0]['Code'];
        
        $countries = new Country($this->db);
        $country   = $countries->find($countryId);
        $values['Country'] = $country[0]['IsoCode2'];
        
        return $values;
    }
}