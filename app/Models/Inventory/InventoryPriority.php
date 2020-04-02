<?php declare(strict_types = 1);

namespace App\Models\Inventory;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class InventoryPriority
{
    // Contains Resources
    private $db;
    private $adapter;
    
    public function __construct(PDO $db,Adapter $adapter = null)
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
        $stmt = $this->db->prepare('SELECT Id, `UserId`, `IsUpdating` FROM InventoryPriority');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
