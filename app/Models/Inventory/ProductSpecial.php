<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class ProductSpecial
{
    // Contains Resources
    private $db;
    private $adapter;

    public function __construct(PDO $db, Adapter $adapter = null)
    {
        $this->db = $db;
    }


    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function count_all_records()
    {
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM ProductSpecial');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM ProductSpecial ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function ProductSpecialJoinAll()
    {
        $stmt = $this->db->prepare('SELECT `ProductSpecial`.`Id` AS `ProdSpecId`,
        `ProductSpecial`.`ProductId` AS `SpecialProductId`,
        `ProductSpecial`.`CustomerGroupId` AS `CustGroupId`,       
        `ProductSpecial`.`Priority` AS `ProdPriority`,
        `ProductSpecial`.`Price` AS `ProdPrice`,
        `ProductSpecial`.`DateStart` AS `ProdStartDate`,
        `ProductSpecial`.`DateEnd` AS `ProdEndDate`,
        `CustomerGroup`.`Id` AS `CustGroupId`,
        `CustomerGroup`.`Name` AS `CustGroupName`,
        `Product`.`Id` AS `ProdId`,
        `Product`.`Name` AS `ProdName`
FROM `ProductSpecial`
INNER JOIN `CustomerGroup` AS `CustomerGroup`
   ON `CustomerGroup`.`Id` = `ProductSpecial`.`CustomerGroupId`
INNER JOIN `Product` AS `Product`
   ON `Product`.`Id` = `ProductSpecial`.`ProductId`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    * all records - get all special products records
    *
    * @param  
    * @return associative array.
    */
    public function getActiveUserAll($UserId = 0, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM ProductSpecial WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * findParents - get top level categories
     *
     * @return array of arrays
    */
    public function findParents()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM ProductSpecial WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*
    * addProductSpecial - add a new special product for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProductSpecial($form = array())
    {
        $query  = 'INSERT INTO ProductSpecial (`ProductId`, CustomerGroupId, Priority, Price,';
        $query  .= 'DateStart, DateEnd';
        $query .= ') VALUES (';
        $query .= ':ProductId, :CustomerGroupId, :Priority,';
        $query .= ':Price, :DateStart, :DateEnd';
        $query .= ')';

        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        return true;
    }


    /*
    * delete - delete a ProductSpecial records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM ProductSpecial WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*
    * find - Find ProductSpecial by ProductSpecial record Id
    *
    * @param  Id  - Table record Id of ProductSpecial to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM ProductSpecial WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    * editProductSpecial - Find ProductSpecial by ProductSpecial record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editProductSpecial($form)
    {
        $query  = 'UPDATE ProductSpecial SET ';
        $query .= 'ProductId = :ProductId, ';
        $query .= 'CustomerGroupId = :CustomerGroupId, ';
        $query .= 'Priority = :Priority, ';
        $query .= 'Price = :Price, ';
        $query .= 'DateStart = :DateStart, ';
        $query .= 'DateEnd = :DateEnd, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id ';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    }
}
