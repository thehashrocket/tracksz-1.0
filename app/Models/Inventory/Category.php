<?php declare(strict_types = 1);

namespace App\Models\Inventory;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Category
{
    // Contains Resources
    private $db;
    private $adapter;
    
    public function __construct(PDO $db,Adapter $adapter = null)
    {
        $this->db = $db;
        $this->adapter = new Adapter([
            'driver'   => 'Mysqli',
            'database' => getenv('DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD')
        ]);   
    }


    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function count_all_records()
    {
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM Category');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name`,`Description`,`Image` FROM Category ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function categoryJoinAll()
    {
        $stmt = $this->db->prepare('SELECT `cat`.`Id` as `CatId`,`Category`.`Name` as `ParentName`,`Category`.`Id`, `Category`.`Name` as `Name`,`Category`.`Description`, `cat`.`ParentId` as `ParentCategory`,`Category`.`Image` FROM `Category` LEFT JOIN `Category` as `cat` ON 
        `Category`.`Id` =  `cat`.`ParentId`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    * all records - get all marketplace records
    *
    * @param  
    * @return associative array.
    */
    public function getActiveUserAll($UserId = 0, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM category WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * findParents - get top level categories
     *
     * @return array of arrays
    */
    public function findParents()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    
    /*
    * addCateogry - add a new cateogry for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addCateogry($form = array())
    {          
        $query  = 'INSERT INTO category (ParentId, Name, Description, Image, Status, UserId';        
        $query .= ') VALUES (';
        $query .= ':ParentId, :Name, :Description, :Image, :Status, :UserId';
        $query .= ')';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }        
        return true;
    }


    /*
    * delete - delete a category records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM category WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

      /*
    * find - Find category by category record Id
    *
    * @param  Id  - Table record Id of category to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM category WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


      /*
    * editCategory - Find category by category record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editCategory($form)
    {
        $query  = 'UPDATE category SET ';
        $query .= 'Name = :Name, ';
        $query .= 'Description = :Description, ';
        $query .= 'ParentId = :ParentId, ';
        $query .= 'Image = :Image, ';
        $query .= 'Status = :Status, ';
        $query .= 'UserId = :UserId, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id ';    
                
        $stmt = $this->db->prepare($query);  
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    } 

    
}
