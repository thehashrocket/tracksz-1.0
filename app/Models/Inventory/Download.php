<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Download
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
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM Download');
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
        $stmt = $this->db->prepare('SELECT Id, `Name`,`Filename`,`Mask` FROM Download ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function DownloadJoinAll()
    {
        $stmt = $this->db->prepare('SELECT `cat`.`Id` as `CatId`,`Download`.`Name` as `ParentName`,`Download`.`Id`, `Download`.`Name` as `Name`,`Download`.`Description`, `cat`.`ParentId` as `ParentDownload`,`Download`.`Image` FROM `Download` LEFT JOIN `Download` as `cat` ON 
        `Download`.`Id` =  `cat`.`ParentId`');
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
        $stmt = $this->db->prepare("SELECT * FROM Download WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
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
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Download WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*
    * addDownload - add a new download for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addDownload($form = array())
    {
        $query  = 'INSERT INTO Download (Name, Filename, Mask, StoreId';
        $query .= ') VALUES (';
        $query .= ':Name, :Filename, :Mask, :StoreId';
        $query .= ')';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        return true;
    }


    /*
    * delete - delete a Download records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM Download WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*
    * find - Find Download by Download record Id
    *
    * @param  Id  - Table record Id of Download to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM Download WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    * editDownload - Find Download by Download record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editDownload($form)
    {
        $query  = 'UPDATE Download SET ';
        $query .= 'Name = :Name, ';
        $query .= 'Filename = :Filename, ';
        $query .= 'Mask = :Mask, ';
        $query .= 'StoreId = :StoreId, ';
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
