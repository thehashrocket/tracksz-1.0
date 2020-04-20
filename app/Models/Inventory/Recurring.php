<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Recurring
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
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM Recurring');
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
        $stmt = $this->db->prepare('SELECT Id, `Name`,`Price`,`Frequency`,`Duration`,`Cycle`,`TrialStatus`,`TrialPrice`,`TrialFrequency`,`TrialDuration`,`TrialCycle`,`Status`,`SortOrder` FROM Recurring ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function RecurringJoinAll()
    {
        $stmt = $this->db->prepare('SELECT `cat`.`Id` as `CatId`,`Recurring`.`Name` as `ParentName`,`Recurring`.`Id`, `Recurring`.`Name` as `Name`,`Recurring`.`Description`, `cat`.`ParentId` as `ParentRecurring`,`Recurring`.`Image` FROM `Recurring` LEFT JOIN `Recurring` as `cat` ON 
        `Recurring`.`Id` =  `cat`.`ParentId`');
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
        $stmt = $this->db->prepare("SELECT * FROM Recurring WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
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
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Recurring WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*
    * addRecurring - add a new cateogry for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addRecurring($form = array())
    {
        $query  = 'INSERT INTO Recurring (`Name`, Price, Frequency, Duration, Cycle, TrialStatus,';
        $query  .= 'TrialPrice, TrialFrequency, TrialDuration, TrialCycle, Status,StoreId,SortOrder';
        $query .= ') VALUES (';
        $query .= ':Name, :Price, :Frequency, :Duration, :Cycle, :TrialStatus,';
        $query .= ':TrialPrice, :TrialFrequency, :TrialDuration, :TrialCycle, :Status, :StoreId, :SortOrder';
        $query .= ')';

        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        return true;
    }


    /*
    * delete - delete a Recurring records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM Recurring WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*
    * find - Find Recurring by Recurring record Id
    *
    * @param  Id  - Table record Id of Recurring to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM Recurring WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    * editRecurring - Find Recurring by Recurring record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editRecurring($form)
    {
        $query  = 'UPDATE Recurring SET ';
        $query .= 'Name = :Name, ';
        $query .= 'Price = :Price, ';
        $query .= 'Frequency = :Frequency, ';
        $query .= 'Duration = :Duration, ';
        $query .= 'Cycle = :Cycle, ';
        $query .= 'TrialStatus = :TrialStatus, ';
        $query .= 'TrialPrice = :TrialPrice, ';
        $query .= 'TrialFrequency = :TrialFrequency, ';
        $query .= 'TrialDuration = :TrialDuration, ';
        $query .= 'TrialCycle = :TrialCycle, ';
        $query .= 'Status = :Status, ';
        $query .= 'SortOrder = :SortOrder, ';
        $query .= 'SortOrder = :SortOrder, ';
        $query .= 'StoreId = :StoreId ';
        $query .= 'WHERE Id = :Id ';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    }
}
