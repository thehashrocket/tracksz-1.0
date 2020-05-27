<?php declare(strict_types = 1);

namespace App\Models\Account;

use App\Models\Country;
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
     * @return array - Shipping zones
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
     * belongsToMember - Check if shipping zone belongs to member
     *
     * @param $zoneId - Shipping zone ID
     * @param $memberId - Member ID
     * @return bool
    */
    public function belongsToMember($zoneId, $memberId)
    {
        $query = 'SELECT StoreId FROM ShippingZone WHERE Id = :zoneId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->execute();
        $storeId = $stmt->fetch(PDO::FETCH_ASSOC)['StoreId'];

        if ($storeId == NULL)
        {
            return false;
        }
        else
        {
            $query = 'SELECT MemberId FROM Store WHERE Id = :storeId';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC)['MemberId'];
            return $result == $memberId;
        }
    }

    /**
     *  getStore - Get ID of store that the zone belongs to
     * 
     *  @param $zoneId - Zone ID
     *  @return 
     */
    public function getStoreID($zoneId)
    {
        $query = 'SELECT StoreId FROM ShippingZone WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $zoneId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()['StoreId'];
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

    public function getCountryAssignments($storeId)
    {
        $countries = (new Country($this->db))->all();
        $map = [];

        $query = 'SELECT ZoneId FROM ShippingZoneToRegion INNER JOIN ShippingZone ON ShippingZoneToRegion.ZoneId = ShippingZone.Id 
                  WHERE ShippingZone.StoreId = :storeId AND ShippingZoneToRegion.CountryId = :countryId';

        $stmt = $this->db->prepare($query);

        foreach ($countries as $country)
        {
            $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
            $stmt->bindParam(':countryId', $country['Id'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (boolval($result))
            {
                $zone = $this->find($result['ZoneId']);
                $map[json_encode($country)] = $zone;
            }
            else
            {
                $map[json_encode($country)] = null;
            }
        }

        return $map;
    }

    /**
     *  isBulkAssignedToRegion - Check if a zone is bulk assigned to a region
     * 
     *  @param $zoneId - Shipping zone ID
     *  @param $countryId - Country ID
     *  @return bool - Zone is assigned to region
     */
    private function isBulkAssignedToRegion($zoneId, $countryId)
    {
        $query = 'SELECT Id FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId = :countryId AND StateId = NULL AND ZipCodeMin = NULL AND ZipCodeMax = NULL';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return boolval($result);
    }

    /**
     *  bulkAssignToCountry - Assign a shipping zone to an entire region
     * 
     *  @param $zoneId - Shipping zone ID
     *  @param $countryIDs - Array of countries to assign zone to
     *  @return bool - Indicates success
     */
    public function bulkAssignToCountry($zoneId, array $countryIDs)
    {
        $storesZones = $this->findByStore($this->getStoreID($zoneId));
        $zoneIDs = array_map(function($zone) {
            return $zone['Id'];
        }, $storesZones);
        $idStr = implode(",", $zoneIDs);

        // Delete redundant specific entries before bulk assigning to save space
        $query = 'DELETE FROM ShippingZoneToRegion WHERE CountryId = :countryId AND ZoneId IN (' . $idStr . ');';
        $query .= 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :countryId)';
        $stmt = $this->db->prepare($query);

        foreach ($countryIDs as $countryId)
        {
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);
            if (!$stmt->execute())
                return false;
        }

        return true;
    }

    /**
     *  bulkAssignToState - Assign a shipping zone to an entire state
     */
    public function bulkAssignToState($zoneId, $countryId, $stateId)
    {
        $storesZones = $this->findByStore($this->getStoreID($zoneId));
        $zoneIDs = array_map(function($zone) {
            return $zone['Id'];
        }, $storesZones);
        $idStr = implode(",", $zoneIDs);

        // Delete redundant specific entries before bulk assigning to save space
        $query = 'DELETE FROM ShippingZoneToRegion WHERE StateId = :stateId AND ZoneId IN (' . $idStr . ');';
        $query .= 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId, StateId) VALUES (:zoneId, :countryId, :stateId)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);
        $stmt->bindParam(':stateId', $stateId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}