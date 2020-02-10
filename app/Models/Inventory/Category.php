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
}