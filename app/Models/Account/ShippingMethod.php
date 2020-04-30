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
        $query .= 'WHERE Id = ' . $data['update_id'];
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':_name', $data['Name']);
        $stmt->bindParam(':delivery', $data['DeliveryTime']);
        $stmt->bindParam(':fee', $data['InitialFee']);
        $stmt->bindParam(':discount', $data['DiscountFee']);
        $stmt->bindParam(':minimum', $data['Minimum']);
        return $stmt->execute();
    }

    /**
     *  getAssigned - Get a store's assigned methods
     * 
     *  @param  $storeId - Store ID
     *  @return list - Assigned shipping methods
     */
    public function getAssigned($storeId)
    {
        $query = 'SELECT * FROM ShippingMethod INNER JOIN ShippingMethodToZone ON ShippingMethod.Id = ShippingMethodToZone.MethodId WHERE ShippingMethod.StoreId = :storeId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *  getUnassigned - Get a store's unassigned methods
     * 
     *  @param  $storeId - Store ID
     *  @return list - Unassigned shipping methods
     */
    public function getUnassigned($storeId)
    {
        
    }
}