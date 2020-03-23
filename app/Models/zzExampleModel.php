<?php declare(strict_types = 1);

namespace App\Models;

// Add only the Classes you require in this current class
use App\Library\Paginate;
use PDO;

class zzExampleModel
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * @return array of arrays
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
     * @return array of arrays
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