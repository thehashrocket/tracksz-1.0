<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class ShippingZone
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * find - Find shipping zone by ID
     *
     * @param  $id   - ID of zone
     * @return array - Shipping zone
    */
    public function find($id)
    {
        $query = 'SELECT * FROM ShippingZone WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * findByStore - Find all shipping zones tied to store
     *
     * @param  $memberId  - ID of store
     * @return array      - Shipping zones
    */
    public function findByStore($storeId)
    {
        $query = 'SELECT * FROM ShippingZone WHERE StoreId = :storeId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *  create - Create a shipping zone
     * 
     *  @param  $data - Array containing zone information
     *  @return bool  - Indicates success
     */
    public function create(array $data)
    {
        $query =  'INSERT INTO ShippingZone (StoreId, `Name`) ';
        $query .= 'VALUES (:storeId, :name)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':storeId', $data['StoreId'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['Name']);
        return $stmt->execute();
    }

    /**
     *  delete - Delete a shipping zone
     * 
     *  @param  $id  - Zone ID
     *  @return bool - Indicates success
     */
    public function delete($id)
    {
        $query = 'DELETE FROM ShippingZone WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     *  update - Update a shipping zone
     * 
     *  @param  $data - Zone data
     *  @return bool  - Indicates success
     */
    public function update($data)
    {
        $query = 'UPDATE ShippingZone SET `Name` = :_name WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $data['Id'], PDO::PARAM_INT);
        $stmt->bindParam(':_name', $data['Name'], PDO::PARAM_STR);
        return $stmt->execute();
    }
}