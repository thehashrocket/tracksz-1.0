<?php

declare(strict_types=1);

namespace App\Models\Order;

use PDO;

class Order
{
    // Contains Resources
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    /*
    * all records - get all orderinventory records
    *
    * @param  
    * @return associative array.
    */
    public function getAll()
    {
        $stmt = $this->db->prepare('SELECT * FROM orderinventory ORDER BY `Id` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * all records - get all orderinventory records
    *
    * @param  
    * @return associative array.
    */
    public function getAllBelongsTo()
    {
        $stmt = $this->db->prepare('SELECT orderinventory.Id AS `OrderId`,
        orderinventory.Status AS `OrderStatus`,
        orderinventory.Currency AS `OrderCurrency`,
        orderinventory.PaymentStatus AS `OrderPaymentStatus`,
        marketplace.Id AS `MarketplaceId`,
        marketplace.MarketName AS `MarketplaceName`
FROM orderinventory
LEFT JOIN marketplace
   ON marketplace.Id = orderinventory.MarketPlaceId');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    * all records - get all orderinventory records
    *
    * @param  
    * @return associative array.
    */
    public function getActiveUserAll($UserId = 0, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM orderinventory WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    * find - Find orderinventory by orderinventory record Id
    *
    * @param  Id  - Table record Id of orderinventory to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM orderinventory WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find orderinventory by orderinventory record UserId and Status
    *
    * @param  UserId  - Table record Id of orderinventory to find
    * @param  Status  - Table record Status of orderinventory to find
    * @return associative array.
    */
    public function findByUserId($UserId, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM orderinventory WHERE UserId = :UserId AND Status IN ($Status)");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * findBySKUProd - Find orderinventory by orderinventory record UserId and Status
    *
    * @param  UserId  - Table record Id of orderinventory to find
    * @param  Status  - Table record Status of orderinventory to find
    * @return associative array.
    */
    public function findByOrderID($OrderId, $UserId)
    {
        $stmt = $this->db->prepare("SELECT * FROM orderinventory WHERE OrderId = :OrderId AND UserId = :UserId");
        $stmt->execute(['OrderId' => $OrderId, 'UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    * findBySKUProd - Find orderinventory by orderinventory record UserId and Status
    *
    * @param  UserId  - Table record Id of orderinventory to find
    * @param  Status  - Table record Status of orderinventory to find
    * @return associative array.
    */
    public function findBySKUProd($ProdSku, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM orderinventory WHERE SKU = :SKU AND Status IN ($Status)");
        $stmt->execute(['SKU' => $ProdSku]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find orderinventory by orderinventory record UserId and Status
    *
    * @param  UserId  - Table record Id of orderinventory to find
    * @param  Status  - Table record Status of orderinventory to find
    * @return associative array.
    */
    public function findByUserProd($UserId, $ProdId, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM orderinventory WHERE UserId = :UserId AND Status IN ($Status) AND ProdId = :ProdId");
        $stmt->execute(['UserId' => $UserId, 'ProdId' => $ProdId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * addProduct - add a new orderinventory for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProduct($form = array())
    {
        $query  = 'INSERT INTO orderinventory (Name, Notes, SKU, ProdId,BasePrice,ProdCondition,';
        $query .= 'ProdActive, InternationalShip,ExpectedShip,EbayTitle,Qty,';
        $query .= 'CategoryId,Status,UserId,Image';
        $query .= ') VALUES (';
        $query .= ':Name, :Notes, :SKU, :ProdId,:BasePrice, :ProdCondition,';
        $query .= ':ProdActive, :InternationalShip, :ExpectedShip, :EbayTitle, :Qty,';
        $query .= ':CategoryId,:Status,:UserId,:Image';
        $query .= ')';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        return true;
    }
    /*
    * editAddress - Find address by address record Id
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editProduct($form)
    {
        $query  = 'UPDATE orderinventory SET ';
        $query .= 'Name = :Name, ';
        $query .= 'Notes = :Notes, ';
        $query .= 'SKU = :SKU, ';
        $query .= 'ProdId = :ProdId, ';
        $query .= 'BasePrice = :BasePrice, ';
        $query .= 'ProdCondition = :ProdCondition, ';
        $query .= 'ProdActive = :ProdActive, ';
        $query .= 'InternationalShip = :InternationalShip, ';
        $query .= 'ExpectedShip = :ExpectedShip, ';
        $query .= 'EbayTitle = :EbayTitle, ';
        $query .= 'Qty = :Qty, ';
        $query .= 'Image = :Image, ';
        $query .= 'CategoryId = :CategoryId, ';
        $query .= 'Status = :Status, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id ';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    }


    public function updateProdInventory($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE orderinventory SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            var_dump($stmt->debugDumpParams());
            exit();
            return false;
        };

        $stmt = null;
        return true;
    }

    /*
     * addOrder - add a new store for member
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addOrder($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO orderinventory (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }


    /*
     * addStore - add a new store for member
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addProdInventory($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO orderinventory (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }


    /*
    * delete - delete a orderinventory records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM orderinventory WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function editOrder($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE orderinventory SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            var_dump($stmt->debugDumpParams());
            exit();
            return false;
        };

        $stmt = null;
        return true;
    }
}