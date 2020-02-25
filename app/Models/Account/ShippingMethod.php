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
     * findByStore - Find all shipping methods tied to store
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
     * findByMember - Find all shipping methods tied to member
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

    /**
     *  addShippingMethod - Add shipping method
     * 
     *  @param  $data - Array containing method information
     *  @return bool  - Indicates success
     */
    public function addShippingMethod(array $data)
    {
        $query =  'INSERT INTO ShippingMethod (StoreId, MemberId, `Name`, DeliveryTime, FirstItemFee, AdditionalItemFee, MinOrderAmount) ';
        $query .= 'VALUES (:storeId, :memberId, :name, :deliveryTime, :firstItemFee, :additionalItemFee, :minOrderAmount)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $data['storeId'], PDO::PARAM_INT);
        $stmt->bindParam(':memberId', $data['memberId'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':deliveryTime', $data['deliveryTime']);
        $stmt->bindParam(':firstItemFee', $data['firstItemFee']);
        $stmt->bindParam(':additionalItemFee', $data['additionalItemFee']);
        $stmt->bindParam(':minOrderAmount', $data['minOrderAmount']);
        return $stmt->execute();
    }
}