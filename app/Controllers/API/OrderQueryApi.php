<?php declare(strict_types = 1);

namespace App\Controllers\Api;

use PDO;

class OrderQueryApi
{
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db   = $db;
    }
    
    public function queryMarketOrders()
    {
    
    }
    
    public function updateStoreOrders()
    {
    
    }
    
}
