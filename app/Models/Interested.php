<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

class Interested
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * add - add interested from home page
    *
    * @param  $form  - Array of form fields, name match Database Fields
    * @return boolean
    */
    public function add($form)
    {
        
        $query  = 'INSERT INTO Interested (FullName, Telephone, Email, Features, Markets) ';
        $query .= 'VALUES(:FullName, :Telephone, :Email, :Features, :Markets)';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }
    
    /*
    * findEmail - Find Interested by Email address
    *
    * @param  email - Email address of form submission
    * @return boolean
    */
    public function findEMail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM Interested WHERE Email = :Email');
        if (!$stmt->execute(['Email' => $email])){
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
