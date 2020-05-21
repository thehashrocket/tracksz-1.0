<?php

declare(strict_types=1);

namespace App\Models\Inventory;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class OrderSetting
{
    // Contains Resources
    private $db;
    private $adapter;

    public function __construct(PDO $db, Adapter $adapter = null)
    {
        $this->db = $db;
    }


    /*
    * editOrderSettings - Find OrderSettings by OrderSettings record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editOrderSettings($form)
    {
        $query  = 'UPDATE ordersettings SET ';
        $query .= 'DontSendCopy = :DontSendCopy, ';
        $query .= 'ConfirmEmail = :ConfirmEmail, ';
        $query .= 'CancelEmail = :CancelEmail, ';
        $query .= 'DeferEmail = :DeferEmail, ';
        $query .= 'NoAdditionalOrder = :NoAdditionalOrder, ';
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
        $query  = 'INSERT INTO ordersettings (UserId,DontSendCopy,ConfirmEmail,CancelEmail,DeferEmail,NoAdditionalOrder,Created)';
        $query .= ' VALUES (';
        $query .= ':UserId,:DontSendCopy,:ConfirmEmail,:CancelEmail,:DeferEmail,:NoAdditionalOrder,:Created';
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
    public function OrderSettingfindByUserId($UserId)
    {
        $stmt = $this->db->prepare('SELECT * FROM ordersettings WHERE UserId = :UserId');
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
