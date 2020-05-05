<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

class ShippingZone
{
    // Contains Resources
    private $db;
    private $countryCodes = [
        'US' => 223, 
        'CA' => 38, 
        'GB' => 222, 
        'AU' => 13
    ];
    
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
        // TODO: Pre-prepare these queries to reduce overhead
        
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

    /**
     *  isAssignedToRegion - Check if a zone is already assigned to a region
     * 
     *  @param $zoneId - Shipping zone ID
     *  @param $countryCode - Shorthand code of region
     *  @return bool - Zone is assigned to region
     */
    private function isAssignedToRegion($zoneId, $countryId)
    {
        $query = 'SELECT Id FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId = :countryId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
        $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return boolval($result);
    }

    /**
     *  bulkAssign - Assign a shipping zone to an entire region
     * 
     *  @param $zoneId - Shipping zone ID
     *  @param $countryCode - Shorthand code of region
     *  @return bool - Indicates success
     */
    public function bulkAssign($zoneId, $countryCode)
    {
        if (array_key_exists($countryCode, $this->countryCodes))
        {
            $countryId = $this->countryCodes[$countryCode];

            // Delete redundant specific entries then bulk assign (save lots of space)
            $query = 'DELETE FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND CountryId = :countryId;';
            $query .= 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :countryId)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);

            $stmt->execute();
        }
        else if ($countryCode === 'US_CA')
        {
            $unitedStatesId = $this->countryCodes['US'];
            $canadaId = $this->countryCodes['CA'];

            // Delete redundant specific entries then bulk assign (save lots of space)
            $query = 'DELETE FROM ShippingZoneToRegion WHERE ZoneId = :zoneId AND (CountryId = :usId OR CountryId = :caId);';
            $query .= 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :usId), (:zoneId, :caId)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
            $stmt->bindParam(':usId', $unitedStatesId, PDO::PARAM_INT);
            $stmt->bindParam(':caId', $canadaId, PDO::PARAM_INT);

            $stmt->execute();
        }
        else if ($countryCode === '*')
        {
            foreach (['GB', 'AU'] as $country)
            {
                $isAssigned = $this->isAssignedToRegion($zoneId, $countryId);
                if ($isAssigned)
                {
                    $query = 'UPDATE ShippingZoneToRegion SET ZipCodeMin = NULL, ZipCodeMax = NULL WHERE ZoneId = :zoneId';
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
                }
                else
                {
                    $query = 'INSERT INTO ShippingZoneToRegion (ZoneId, CountryId) VALUES (:zoneId, :countryId)';
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':zoneId', $zoneId, PDO::PARAM_INT);
                    $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);
                }

                $stmt->execute();
            }
        }
        else
        {
            return false;
        }

        return true;
    }

    public function getCountryAssignments()
    {
        $map = [];
        $query = 'SELECT ZoneId FROM ShippingZoneToRegion WHERE CountryId = :countryId';
        $stmt = $this->db->prepare($query);

        $countryIds = (new Country($this->db))->getAllIds();
        foreach ($countryIds as $countryId)
        {
            $stmt->bindParam(':countryId', $countryId, PDO::PARAM_INT);

        }
    }
}