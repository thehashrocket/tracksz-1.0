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
     * findByMember - Find all shipping zones tied to member
     *
     * @param  $memberId  - ID of zone
     * @return array      - Shipping zones
    */
    public function findByMember($memberId)
    {
        $query = 'SELECT * FROM ShippingZone WHERE MemberId = :memberId';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':memberId', $memberId, PDO::PARAM_INT);
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
        $query =  'INSERT INTO ShippingZone (MemberId, `Name`) ';
        $query .= 'VALUES (:memberId, :name)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':memberId', $data['MemberId'], PDO::PARAM_INT);
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
        $query = 'UPDATE ShippingZone SET `Name` = :_name';
        $query .= 'WHERE Id = ' . $data['update_id'];
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':_name', $data['Name']);
        return $stmt->execute();
    }
}