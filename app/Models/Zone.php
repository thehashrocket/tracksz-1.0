<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

class Zone
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /**
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, CountryId, `Name` FROM Zone');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
    * all - Get all countries
    *
    * @return array of arrays
    */
    public function find($CountryId)
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Zone WHERE CountryId = :CountryId');
        if (!$stmt->execute(['CountryId' => $CountryId])) {
            return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
    * all - Get all countries
    *
    * @return array of arrays
    */
    public function findByZone($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM `Zone` WHERE Id = :Id');
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * columnsByIndex - get one or more columns from zone table
     *                  based on $index = value;
     *                  and Id = CountryId
     *
     * @param $index  - The column to Select by
     * @param $value  - The value of the select column
     * @param $columns - an array of one or or more columns from zone
     * @return associative array of columns (fields)
     */
    public function columnsByIndex($index, $value, $countryId, $columns)
    {
        $select = '';
        foreach ($columns as $column) {
            $select .= $column.', ';
        }
        $select = substr($select, 0, -2);
        $query = 'SELECT '. $select . ' FROM Zone WHERE ' . $index .' = :'.$index . ' AND CountryId = :CountryId';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute([$index => $value, 'CountryId' => $countryId])) {
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     *  getName - Get name of zone
     *  @param $id - ID of zone
     *  @return string - Name of zone
     */
    public function getName($id)
    {
        $query = 'SELECT `Name` FROM `Zone` WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['Name'];
    }

    /**
     *  getCountry - Get country ID of zone
     *  @param $id - ID of zone
     *  @return string - ID of country
     */
    public function getCountryId($id)
    {
        $query = 'SELECT CountryId FROM `Zone` WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['CountryId'];
    }
}