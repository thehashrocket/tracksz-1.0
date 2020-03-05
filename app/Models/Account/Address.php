<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class Address
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * find - Find a member addresses based on member id
    *
    * @param  $MemberId  - User Id provided by AUTH after registration
    * @return send array of associative arrays back.
    */
    public function find($MemberId)
    {
        $stmt = $this->db->prepare('SELECT * FROM Address WHERE MemberId = :MemberId AND IsDeleted = :Value');
        $stmt->execute(['MemberId' => $MemberId, 'Value' => 0]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
    * find - Find address by address record Id
    *
    * @param  Id  - Table record Id of address to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM Address WHERE Id = :Id AND IsDeleted = :Value');
        $stmt->execute(['Id' => $Id, 'Value' => 0]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    /*
     * addOrUpdate - credit card updated.  If card used elsewhere, add New Address
     *               else update old card address
     *
     * @param  $form  - Array of form fields, name match Database Fields
     * @return boolean
   */
    public function addOrUpdate($form, $address_id)
    {
        $query  = 'SELECT Id, FullName, Address1, Address2, City, PostCode, ZoneId, CountryId, MemberId, Company ';
        $query .= 'FROM Address WHERE MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam('MemberId', $form['MemberId'], PDO::PARAM_INT);
        $stmt->execute();
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //  See if we already have that address
        $add_address = 0;
        foreach($addresses as $address) {
            // save and remove Id as Not in Form and would not be equal arrays
            $id = $address['Id'];
            unset($address['Id']);
            if ($form == $address) {
                $add_address = $id;
                break;
            }
        }

        if (!$add_address) {
            return $this->addAddress($form);
        } else {
            // add back address ID for edit
            $form['Id'] = $add_address;
            return $this->editAddress($form);
        }
    }
    
    /*
    * editAddress - Find address by address record Id
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editAddress($form)
    {
        $query  = 'UPDATE Address SET ';
        $query .= 'FullName = :FullName, ';
        $query .= 'Address1 = :Address1, ';
        $query .= 'Address2 = :Address2, ';
        $query .= 'Company = :Company, ';
        $query .= 'City = :City, ';
        $query .= 'PostCode = :PostCode, ';
        $query .= 'CountryId = :CountryId, ';
        $query .= 'ZoneId = :ZoneId, ';
        $query .= 'IsDeleted = 0, ';
        $query .= 'MemberId = :MemberId, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id';
        $form['Updated'] = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    }
    
    /*
    * addAddress - add a new address for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addAddress($form)
    {
        $query  = 'INSERT INTO Address (MemberId, FullName, Company, Address1, Address2, ';
        $query .= 'City, PostCode, CountryId, ZoneId) VALUES(';
        $query .= ':MemberId, :FullName, :Company, :Address1, :Address2, ';
        $query .= ':City, :PostCode, :CountryId, :ZoneId)';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }
    
    /*
    * delete - delete a members address, by Address Table ID and User Id
    *
    * @param  $id = table record numbver of card
    * @param $memberId - member Id of Logged in User
    * @return boolean
    */
    public function delete($Id, $memberId)
    {
        $query  = 'UPDATE Address SET IsDeleted = :Value WHERE Id = :Id AND MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute(['Value' => 1, 'Id' => $Id, 'MemberId' => $memberId])) {
            return false;
        }
        $stmt = null;
        return true;
    }
}