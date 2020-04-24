<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class ProductDiscount
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
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM ProductDiscount');
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
        $stmt = $this->db->prepare('SELECT Id, `Name`,`Price`,`Frequency`,`Duration`,`Cycle`,`TrialStatus`,`TrialPrice`,`TrialFrequency`,`TrialDuration`,`TrialCycle`,`Status`,`SortOrder` FROM ProductDiscount ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function ProductDiscountJoinAll()
    {
        $stmt = $this->db->prepare('SELECT `ProductDiscount`.`Id` AS `ProdDiscId`,
        `ProductDiscount`.`ProductId` AS `ProdId`,
        `ProductDiscount`.`CustomerGroupId` AS `CustGroupId`,
        `ProductDiscount`.`Quantity` AS `ProdQty`,
        `ProductDiscount`.`Priority` AS `ProdPriority`,
        `ProductDiscount`.`Price` AS `ProdPrice`,
        `ProductDiscount`.`DateStart` AS `ProdStartDate`,
        `ProductDiscount`.`DateEnd` AS `ProdEndDate`,
        `CustomerGroup`.`Id` AS `CustGroupId`,
        `CustomerGroup`.`Name` AS `CustGroupName`,
        `Product`.`Id` AS `ProdId`,
        `Product`.`Name` AS `ProdName`
FROM `ProductDiscount`
INNER JOIN `CustomerGroup` AS `CustomerGroup`
   ON `CustomerGroup`.`Id` = `ProductDiscount`.`CustomerGroupId`
INNER JOIN `Product` AS `Product`
   ON `Product`.`Id` = `ProductDiscount`.`ProductId`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    * all records - get all marketplace records
    *
    * @param  
    * @return associative array.
    */
    public function getActiveUserAll($UserId = 0, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM ProductDiscount WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
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
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM ProductDiscount WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*
    * addProductDiscount - add a new cateogry for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProductDiscount($form = array())
    {
        $query  = 'INSERT INTO ProductDiscount (`ProductId`, CustomerGroupId, Quantity, Priority, Price,';
        $query  .= 'DateStart, DateEnd';
        $query .= ') VALUES (';
        $query .= ':ProductId, :CustomerGroupId, :Quantity, :Priority,';
        $query .= ':Price, :DateStart, :DateEnd';
        $query .= ')';

        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        return true;
    }


    /*
    * delete - delete a ProductDiscount records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM ProductDiscount WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*
    * find - Find ProductDiscount by ProductDiscount record Id
    *
    * @param  Id  - Table record Id of ProductDiscount to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM ProductDiscount WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    * editProductDiscount - Find ProductDiscount by ProductDiscount record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editProductDiscount($form)
    {
        $query  = 'UPDATE ProductDiscount SET ';
        $query .= 'ProductId = :ProductId, ';
        $query .= 'CustomerGroupId = :CustomerGroupId, ';
        $query .= 'Quantity = :Quantity, ';
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
