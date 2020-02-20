<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class ShippingMethod
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * find - Find all shipping methods tied to store
     *
     * @param  $storeId  - ID of store
     * @return array     - Shipping methods
    */
    public function findByStore($storeId)
    {
        $query = 'SELECT * FROM ShippingMethod WHERE StoreId = :storeId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * find - Find all shipping methods tied to member
     *
     * @param  $memberId  - ID of member
     * @return array      - Shipping methods
    */
    public function findByMember($memberId)
    {
        $query = 'SELECT * FROM ShippingMethod WHERE MemberId = :memberId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':memberId', $memberId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}