<?php

declare(strict_types=1);

namespace App\Models\Account;

use PDO;

class Store
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
     * find - Find a member stores based on member id
     *
     * @param  $MemberId  - User Id provided by AUTH after registration
     * @param  $value     - IsActive = 1 is an active store,  0 = deleted.
     * @return send array of associative arrays back.
    */
    public function find($MemberId, $value)
    {
        $stmt = $this->db->prepare('SELECT * FROM Store WHERE MemberId = :MemberId AND IsActive = :Value');
        $stmt->bindParam(':MemberId', $MemberId, PDO::PARAM_INT);
        $stmt->bindParam(':Value', $value, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * findId - Find a member stores based on member id
     *
     * @param  $id  - Find an Open store by Store Id and MemberId
     * @param  $MemberId  - User Id provided by AUTH after registration
     * @return send array of associative arrays back.
    */
    public function findId($id,$MemberId)
    {
        $query  = 'SELECT * FROM Store WHERE Id = :Id AND MemberId = :MemberId ';
        $query .= 'AND IsActive = 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':MemberId', $MemberId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
     * addStore - add a new store for member
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addStore($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key.', ';
            $values .= ':'.$key.', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);
        $query  = 'INSERT INTO Store ('.$insert.') ';
        $query .= 'VALUES('.$values.')';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }
    
    /*
    * updateColumns - update one or more columns to Member Table
    *
    * @param  $id  - store Id from form
    * @param  $columns  - an array of one or or more columns from member
    *                     Form Field Names MUST MATCH Database Column Names
    * @return Sends back to login or profile page
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
        
        $query  = 'UPDATE Store SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';
        
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            var_dump($stmt->debugDumpParams());
            exit();
            return false;
        };
        
        $stmt = null;
        return true;
    }
    
    /*
     * columns - get one or more columns from Member Table
     *
     * @param  $id  - store Id from tracksz_active_store cookie
     * @param  $columns  - an array of one or or more columns from store
     * @return Sends back to login or profile page
    */
    public function columns($Id, $columns)
    {
        $select = '';
        foreach ($columns as $column) {
            $select .= $column.', ';
        }
        $select = substr($select, 0, -2);
        $query = 'SELECT '. $select . ' FROM Store WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        };
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
    * isUnique - check if it is unique. Legal Name must be unique per user.
    *
    * @param  id  - MemberId
    * @param  LegalName  - LegalName from Form
    * @param  DisplayName  - DisplayName from Form
    * @return associative array.
    */
    public function isUnique($id, $legal_name, $display_name)
    {
        $query  = 'SELECT COUNT(*) AS stores FROM Store WHERE MemberId = :Id AND ';
        $query .= '(LegalName = :LegalName OR DisplayName = :DisplayName)';
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'Id' => $id,
            'LegalName' => $legal_name,
            'DisplayName' => $display_name,
        ]);
        if ($unique = $stmt->fetch()) {
            if  ($unique['stores'] > 0) {
                return false;
            }
        }
        return true;
    }
    
    /*
     * delete - delete a members address, by Address Table ID and User Id
     *
     * @param  $id = table record numbver of card
     * @param $memberId - member Id of Logged in User
     * @return boolean
    */
    public function deleteRestore($Id, $memberId, $value)
    {
        $query  = 'UPDATE Store SET IsActive = :Value WHERE Id = :Id AND MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute(['Value' => $value, 'Id' => $Id, 'MemberId' => $memberId])) {
            return false;
        }
        $stmt = null;
        return true;
    }
    
    /*
    * stripeSetup - check if store has stripe setup or not.
    *
    * @param  id  - store Id
    * @param  memberId = Member Id current logged in
    * @return boolean true it has been setup, false it has not
    */
    public function stripeSetup($id, $memberId)
    {
        $query  = 'SELECT StripeSetup FROM Store WHERE Id = :Id AND ';
        $query .= 'MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'Id' => $id,
            'MemberId' => $memberId
        ]);
        $stripe_setup = $stmt->fetch();
        if ($stripe_setup['StripeSetup']) {
            return true;
        }
        return false;
    }
}
