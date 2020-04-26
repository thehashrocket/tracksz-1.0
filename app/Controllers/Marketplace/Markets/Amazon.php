<?php declare(strict_types = 1);

namespace App\Controllers\Marketplace\Markets;

use PDO;

class Amazon
{
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db   = $db;
    }
    
    public function formatUpdateFile()
    {
    
    }
    
    public function sendInventoryUpdate()
    {
    
    }
    
    public function queryOrders()
    {
    
    }
    
}