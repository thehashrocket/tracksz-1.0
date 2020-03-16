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
     * @param  $storeId  - ID of zone
     * @return array     - Shipping zones
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
     *  getStoreId - Get store tied to shipping zone
     * 
     *  @param  $id - ID of shipping zone
     *  @return int - Store ID
     */
    public function getStoreId($id)
    {
        $query = 'SELECT StoreId FROM ShippingZone WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return intval($stmt->fetch(PDO::FETCH_ASSOC)['StoreId']);
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
        $stmt->bindParam(':id', $data['update_id']);
        $stmt->bindParam(':_name', $data['Name']);
        return $stmt->execute();
    }

    /**
     *  bulkAssign - Assign a shipping zone to a whole country
     * 
     *  @param $country - Shorthand country code
     *  @param $zoneId - Shipping zone ID
     *  @return bool - Indicates success
     */
    public function bulkAssign($country, $zoneId)
    {
        $countryIDs = ['US' => 223, 'CA' => 38, 'AU' => 13, 'GB' => 222];

        if ($country === '*') {
            $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES ';
            $query .= '(:zoneId, :US), (:zoneId, :CA), (:zoneId, :AU), (:zoneId, :GB)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':US', $countryIDs['US'], PDO::PARAM_INT);
            $stmt->bindParam(':CA', $countryIDs['CA'], PDO::PARAM_INT);
            $stmt->bindParam(':AU', $countryIDs['AU'], PDO::PARAM_INT);
            $stmt->bindParam(':GB', $countryIDs['GB'], PDO::PARAM_INT);
        }
        else if ($country === 'US_CA') {
            $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :US), (:zoneId, :CA)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':US', $countryIDs['US'], PDO::PARAM_INT);
            $stmt->bindParam(':CA', $countryIDs['CA'], PDO::PARAM_INT);
        }
        else {
            $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :country)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':country', $countryIDs[$country], PDO::PARAM_INT);
        }

        return $stmt->execute();
    }
}