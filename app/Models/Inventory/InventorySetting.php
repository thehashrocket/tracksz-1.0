<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class InventorySetting
{
    // Contains Resources
    private $db;
    private $adapter;

    public function __construct(PDO $db, Adapter $adapter = null)
    {
        $this->db = $db;
    }


    /*
    * editInventorySettings - Find InventorySettings by InventorySettings record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editInventorySettings($form)
    {
        $query  = 'UPDATE inventorysettings SET ';
        $query .= 'FileType = :FileType, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE UserId = :UserId ';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['UserId'];
    }

    /*
    * addInventorySettings - add a new inventory settings for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addInventorySettings($form = array())
    {
        $query  = 'INSERT INTO inventorysettings (UserId,FileType)';
        $query .= ' VALUES (';
        $query .= ':UserId,:FileType';
        $query .= ')';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }

        return true;
    }

    /*
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function findByUserId($UserId)
    {
        $stmt = $this->db->prepare('SELECT * FROM inventorysettings WHERE UserId = :UserId');
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
