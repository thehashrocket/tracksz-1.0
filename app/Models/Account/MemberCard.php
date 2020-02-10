<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class MemberCard
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * find - Find all payment method based on MemberId Active Cards, IsDeleted = 0
    *
    * @param  $id  - Provided by AUTH after registration
    * @return Sends back to login or profile page
    */
    public function find($memberId)
    {
        $stmt = $this->db->prepare('SELECT * FROM MemberCard WHERE MemberId = :MemberId AND IsDeleted = :Value');
        $stmt->execute(['MemberId' => $memberId, 'Value' => 0]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
    * findAll - Find all payment method based on MemberId including previously marked as deleted.
    *
    * @param  $id  - Provided by AUTH after registration
    * @return Sends back to login or profile page
    */
    public function findAll($memberId)
    {
        $stmt = $this->db->prepare('SELECT * FROM MemberCard WHERE MemberId = :MemberId AND IsDeleted = 0');
        $stmt->execute(['MemberId' => $memberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * countAddressId - Find all payment methods using a certain address ID
     *
     * @param  $address_id  - Id of address record
     * @return Sends back to login or profile page
    */
    public function countAddressId($address_id)
    {
        $query = 'SELECT COUNT(*) AS cards FROM MemberCard WHERE ';
        $query.= 'AddressId = :AddressId';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['AddressId' => $address_id]);
        
        $cards = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cards['cards'];
    }
    
    /*
    * findWithAddress - Find all payment method based on MemberId and include the Billing address
    *
    * @param  $id  - member id
    * @return Sends back to login or profile page
    */
    public function findWithAddress($memberId)
    {
        $query = 'SELECT mc.Id, GatewayPaymentId, IsBilling, StreetCheck, ZipCheck, FullName, Address1, Address2, ';
        $query .= 'City, PostCode, ZoneId, CountryId, ad.Id as AddressId FROM MemberCard mc, Address ad WHERE ';
        $query.= 'mc.MemberId = :MemberId AND mc.IsDeleted = :Value AND mc.AddressId = ad.Id ';
        $stmt = $this->db->prepare($query);
        $stmt->execute(['MemberId' => $memberId, 'Value' => 0]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
    * find - Find a payment method based on MemberCard Id
    *
    * @param  $id  - Id of card in table
    * @return Sends back to login or profile page
    */
    public function findByCardId($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM MemberCard WHERE Id = :Id');
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
    * add Card  - Add Payment Card for Member
    *
    * @param  $form  - Data entered from form
    *                  Form Field Names MUST MATCH Database Column Names
    * @return Sends back to login or profile page
    */
    public function addCard($form)
    {
        $stmt = $this->db->prepare('INSERT INTO MemberCard (GatewayPaymentId, CvcCheck, StreetCheck, ZipCheck, MemberId, AddressId) VALUES (:GatewayPaymentId, :CvcCheck, :StreetCheck, :ZipCheck, :MemberId, :AddressId)');
        // for new card everything else has default values
        if (!$stmt->execute($form)) {
            return false;
        };
        $stmt = null;
        return true;
    }
    
    
    /*
     * edit Member - Edit payment Method for member
     *
     * @param  $form  - Data entered from form
     *                  Form Field Names MUST MATCH Database Column Names
     * @return Sends back to login or profile page
     */
    public function editCard($form)
    {
        $query  = 'UPDATE MemberCard SET ';
        $query .= 'GatewayPaymentId = :GatewayPaymentId, ';
        $query .= 'CvcCheck = :CvcCheck, ';
        $query .= 'StreetCheck = :StreetCheck, ';
        $query .= 'ZipCheck = :ZipCheck, ';
        $query .= 'AddressId = :AddressId, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id ';
        $query .= 'AND MemberId = :MemberId ';
        $stmt = $this->db->prepare($query);
        
        // for new card everything else has default values
        if (!$stmt->execute($form)) {
            return false;
        };
        $stmt = null;
        return true;
    }
    
    /*
    * delete - delete a members payment card, by MemberCard Table ID and User Id
     *         Set IsDeleted = 1, to save for historical records
    *
    * @param  $id = table record numbver of card
    * @param $memberId - member Id of Logged in User
    * @return boolean
    */
    public function delete($Id, $memberId)
    {
        $query  = 'UPDATE MemberCard SET IsDeleted = :Value WHERE Id = :Id AND MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute(['Value' => 1, 'Id' => $Id, 'MemberId' => $memberId])) {
            return false;
        }
        $stmt = null;
        return true;
    }
    
    /*
    * billing - Set a card as the billing card for a store by seller
    *
    * @param  $id = table record number of card
    * @param $memberId - member Id of Logged in User
    * @return boolean
    */
    public function billing($Id, $memberId)
    {
        // change all other cards to not default (DefaultCard = 0:
        $query  = 'UPDATE MemberCard SET `IsBilling` = :Value, Updated = :Today WHERE MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Value' => 0, 'Today' => date('Y-m-d H:i:s'), 'MemberId' => $memberId])) {
            return false;
        }
        
        // Change Current Card to Default
        $query  = 'UPDATE MemberCard SET `IsBilling` = :Value, Updated = :Today WHERE Id = :Id AND MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Value' => 1, 'Today' => date('Y-m-d H:i:s'), 'Id' => $Id, 'MemberId' => $memberId])) {
            return false;
        }
        $stmt = null;
        return true;
    }
    
    /**
     * getSyncedCards - Get All card payment methods for a member from gateway
     *                  synced DB rows withe Gateway records
     *
     * @param userId - the member userId from auth->getUserId();
     * @param encrypt - App\Library\Encryption to decrypt PaymentId
     * @param gateway - Payment Gateway used (Stripe Connect)
     * @param gateway_id - Member's Payment Gateway User Id
     * @param key - Encryption key unique to member
     * @return array - Card_rows from db and card_Data from Gateway
     * @throws \Exception
     */
    public function getSyncedCards($userId, $encrypt, $gateway, $gateway_id, $key)
    {
        // need to get ids to match with cards received from gateway
        $cards = $this->findWithAddress($userId);
        if (count($cards) <= 0) {
            return false;
        }
        
        // we have active cards, let's show them.
        $card_rows = [];
        foreach ($cards as $card) {
            $card_rows[$encrypt->safeDecrypt($card['GatewayPaymentId'], $key)] = $card;
        }
        $result = $gateway->retrieveCards($gateway_id);
        
        // build array with only active cards (IsDeleted = 0), maybe
        // not all that are returned from Payment Gateway
        $card_data = [];
        foreach ($result->data as $card) {
            if (array_key_exists($card['id'], $card_rows)) {
                $card_data[] = $card;
            }
        }
        return ['card_data' => $card_data, 'card_rows' => $card_rows];
        
    }
    
    /*
    * updateColumns - update one or more columns to Card Table
    *
    * @param  $id  - Card Id from record
    * @param  $columns  - an array of one or or more columns from member
    *                    Form Field Names MUST MATCH Database Column Names
    * @return Sends bac
    */
    public function updateColumns($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column. ' = :'.$column .', ';
            $values[$column] = $value;
        }
        $update = substr($update, 0, -2);
        $query  = 'UPDATE MemberCard SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            return false;
        };
        return true;
    }
}