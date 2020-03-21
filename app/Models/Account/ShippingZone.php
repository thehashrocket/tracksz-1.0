<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class ShippingZone
{
    private $db;
    private $countryIDs = ['US' => 223, 'CA' => 38, 'AU' => 13, 'GB' => 222];
    
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
     *  getRegionAssignments - Check if zone has already been assigned to region(s)
     * 
     *  @param $countryCode - Shorthand country code
     *  @param $zoneId - Shipping zone ID
     *  @return array - List of country IDs the zone is assigned to
     */
    private function getRegionAssignments($countryCode, $zoneId)
    {
        if ($countryCode === '*')
        {
            $query = 'SELECT Id FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId IN (13, 38, 222, 223)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else if ($countryCode === 'US_CA')
        {
            $query = 'SELECT Id FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId IN (38, 223)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $query = 'SELECT Id FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId = :countryId';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':countryId', $this->countryIDs[$countryCode], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     *  bulkAssign - Assign a shipping zone to a whole country
     * 
     *  @param $country - Shorthand country code
     *  @param $zoneId - Shipping zone ID
     *  @return bool - Indicates success
     */
    public function bulkAssign($countryCode, $zoneId)
    {
        $currentAssignments = $this->getRegionAssignments($countryCode, $zoneId);

        if (sizeof($currentAssignments) === 0) // No current assignments for zone, do one insert (optimally efficient)
        {
            if ($countryCode === '*')
            {
                $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :US), (:zoneId, :CA), (:zoneId, :AU), (:zoneId, :GB)';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
                foreach ($this->countryIDs as $code => $id) 
                    $stmt->bindParam(':' . $code, $id, PDO::PARAM_INT);
                return $stmt->execute();
            }
            else if ($countryCode === 'US_CA')
            {
                $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :US), (:zoneId, :CA)';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
                $stmt->bindParam(':US', $this->countryIDs['US'], PDO::PARAM_INT);
                $stmt->bindParam(':CA', $this->countryIDs['CA'], PDO::PARAM_INT);
                return $stmt->execute();
            }
            else
            {
                $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :countryId)';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
                $stmt->bindParam(':countryId', $this->countryIDs[$countryCode], PDO::PARAM_INT);
                return $stmt->execute();
            }
        }
        else // Assignments exist for 1+ zones, update/insert accordingly
        {
            $assignedRegions = array_map($currentAssignments, function($assignment) {
                return intval($assignment['Id']);
            });

            if ($countryCode === '*')
            {
                $unassignedRegions = array_filter(array_values($this->countryIDs), function($id) {
                    return !in_array($id, $assignedRegions);
                });

                // Update assigned regions

                // Insert unassigned regions
                if ($unassignedRegions)
                {
                    $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES ';
                    foreach ($unassignedRegions as $region)
                    {
                        $query .= '(:zoneId, ' . $region . '),';
                    }
                }
            }
        }
    }
}