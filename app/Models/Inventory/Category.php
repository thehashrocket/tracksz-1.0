<?php declare(strict_types = 1);

namespace App\Models\Inventory;

use PDO;

class Category
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function categorySelect($form=[])
    {
        $start = 'a';
        if (isset($form['cat'])) {
            $start = $form['cat'];
        }
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category WHERE `Name` LIKE ":name'.'%"');
        $stmt->bindParam(':name', $start, PDO::PARAM_STR);
        $stmt->execute();
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
     * findParents - get top level categories
     *
     * @return array of arrays
    */
    public function insert_category($ParentId = null, $Level= null, $Name= null, $Description= null, $Image= null,$Created= null, $Update= null)
    {   
        $res['status'] = false;
        $res['message'] = 'record not inserted';
        $res['data'] = array();
        $stmt = $this->db->prepare("INSERT INTO `category` (`Id`, `ParentId`, `Level`, `Name`, `Description`, `Image`, `Created`, `Update`) VALUES (NULL, $ParentId, $Level, '$Name', '$Description', '$Image', '$Created', '$Update')");        
        $stmt->execute();
        if($stmt){
            $res['status'] = true;
            $res['message'] = 'insert success';
            $res['data'] = array();
        }
        return $res;
    }

    
}