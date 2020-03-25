<?php declare(strict_types = 1);

namespace App\Models;

// Add only the Classes you require in this current class
use App\Library\Paginate;
use PDO;

class zzExampleModel
{
    private $db; // Contains DB resource
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * all - Get all records
     *
     * @param integer current - The current page
     * @param integer perpage - Number of listings per page
     * @return mixed array - Returns rows from table
     */
    public function all($current, $perpage)
    {
        // prepare pagination if necessary
        Paginate::initialize($this->db,'SELECT COUNT(*) AS count FROM zzExample',
            [], $current, $perpage);
        
        $stmt = $this->db->prepare('SELECT * FROM zzExample ORDER BY id' . Paginate::limit());
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * find - Find one record based on Name
     *
     * @param string name - A name to search for
     * @return mixed array - Returns one row or false
    */
    public function find($name)
    {
        $stmt = $this->db->prepare('SELECT * FROM zzExample WHERE ExampleName LIKE :name');
        
        // cannot modify a parameter in bindParam, must do it first
        $modified = $name . '%';
        $stmt->bindParam('name', $modified, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            // often used to debug
            var_dump($stmt->debugDumpParams());
            exit();
            
            // not debugging
            // return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}