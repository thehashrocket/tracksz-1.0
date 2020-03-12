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
     * find - Find shipping method by ID
     *
     * @param  $id   - ID of method
     * @return array - Shipping method
    */
    public function find($id)
    {
        $query = 'SELECT * FROM ShippingMethod WHERE Id = ' . $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * findByStore - Find all shipping methods tied to store
     *
     * @param  $storeId - ID of store
     * @return array    - Shipping methods
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
     *  getStoreId - Get store tied to shipping method
     * 
     *  @param  $id - ID of shipping method
     *  @return int - Store ID
     */
    public function getStoreId($id)
    {
        $query = 'SELECT StoreId FROM ShippingMethod WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return intval($stmt->fetch(PDO::FETCH_ASSOC)['StoreId']);
    }

    /**
     *  create - Create a shipping method
     * 
     *  @param  $data - Array containing method information
     *  @return bool  - Indicates success
     */
    public function create(array $data)
    {
        $query =  'INSERT INTO ShippingMethod (StoreId, `Name`, DeliveryTime, InitialFee, DiscountFee, Minimum) ';
        $query .= 'VALUES (:storeId, :name, :deliveryTime, :initialFee, :discountFee, :minimum)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $data['StoreId'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['Name']);
        $stmt->bindParam(':deliveryTime', $data['DeliveryTime']);
        $stmt->bindParam(':initialFee', $data['InitialFee']);
        $stmt->bindParam(':discountFee', $data['DiscountFee']);
        $stmt->bindParam(':minimum', $data['Minimum']);
        return $stmt->execute();
    }

    /**
     *  delete - Delete a shipping method
     * 
     *  @param  $id  - Method ID
     *  @return bool - Indicates success
     */
    public function delete($id)
    {
        $query = 'DELETE FROM ShippingMethod WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     *  update - Update a shipping method
     * 
     *  @param  $data - Method data
     *  @return bool  - Indicates success
     */
    public function update($data)
    {
        $query = 'UPDATE ShippingMethod SET `Name` = :_name, DeliveryTime = :delivery, InitialFee = :fee, DiscountFee = :discount, Minimum = :minimum ';
        $query .= 'WHERE Id = ' . $data['updateId'];
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':_name', $data['Name']);
        $stmt->bindParam(':delivery', $data['DeliveryTime']);
        $stmt->bindParam(':fee', $data['InitialFee']);
        $stmt->bindParam(':discount', $data['DiscountFee']);
        $stmt->bindParam(':minimum', $data['Minimum']);
        return $stmt->execute();
    }

    /**
     *  assign - Assign a shipping method to a zone
     * 
     *  @param $methodId - Shipping method ID
     *  @param $zoneId - Shipping zone ID
     *  @return bool - Indicates success
     */
    public function assign($methodId, $zoneId)
    {
        $query = 'INSERT INTO ShippingMethodToZone (ZoneId, MethodId) VALUES (:zoneId, :methodId)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->bindParam(':methodId', $methodId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     *  unassign - Unassign a shipping method from a zone
     * 
     *  @param $methodId - Shipping method ID
     *  @param $zoneId - Shipping zone ID
     *  @return bool - Indicates success
     */
    public function unassign($methodId, $zoneId)
    {
        $query = 'DELETE FROM ShippingMethodToZone WHERE ZoneId = :zoneId AND MethodId = :methodId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->bindParam(':methodId', $methodId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     *  getUnassignedByStore - Get unassigned shipping methods under a store
     * 
     *  @param $storeId - Store ID
     *  @return list - Shipping methods 
     */
    public function getUnassignedByStore($storeId)
    {
        $query = 'SELECT * FROM ShippingMethod WHERE StoreId = :storeId AND ShippingMethod.Id NOT IN (SELECT MethodId FROM ShippingMethodToZone)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *  getAssignedByStore - Get shipping methods assigned to store
     * 
     *  @param $storeId - Store ID
     *  @return list - Shipping methods 
     */
    public function getAssignedByStore($storeId)
    {
        $query = 'SELECT * FROM ShippingMethod INNER JOIN ShippingMethodToZone ON (ShippingMethod.StoreId = :storeId AND ShippingMethod.Id = ShippingMethodToZone.MethodId)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}