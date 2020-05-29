<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

class Country
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * all - Get all countries
    *      ** IMPORTANT ** Only Showing US, UK, Australia, Canada as of 2019-07-31
    *
    * @return array of arrays
    */
    public function all()
    {
        // Only supporting US, UK, Australia, and Canada for now, 2019-07-31
        $stmt = $this->db->prepare('SELECT Id, `Name`, IsoCode2 FROM Country WHERE Id IN (223,222,13,38)');
        if (!$stmt->execute()) {
            return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllIds()
    {
        $stmt = $this->db->prepare('SELECT Id FROM Country');
        if (!$stmt->execute()) {
            return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
    * find - Find a Country by Record Id
    *
    * @return array of arrays
    */
    public function find($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM Country WHERE Id = :Id');
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * columnsByIndex - get one or more columns from country table
     *                  based on $index = value
     *
     * @param $index  - The column to Select by
     * @param $value  - Teh value of the select column
     * @param $columns - an array of one or or more columns from country
     * @return associative array of columns (fields)
     */
    public function columnsByIndex($index, $value, $columns)
    {
        $select = '';
        foreach ($columns as $column) {
            $select .= $column.', ';
        }
        $select = substr($select, 0, -2);
        $query = 'SELECT '. $select . ' FROM Country WHERE ' . $index .' = :'.$index;
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute([$index => $value])) {
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getStates($countryId)
    {
        $query = 'SELECT * FROM `Zone` WHERE CountryId = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $countryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}