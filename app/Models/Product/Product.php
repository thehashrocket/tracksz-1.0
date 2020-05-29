<?php

declare(strict_types=1);

namespace App\Models\Product;

use PDO;

class product
{
    // Contains Resources
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    /*
    * all records - get all product records
    *
    * @param  
    * @return associative array.
    */
    public function getAll()
    {
        $stmt = $this->db->prepare('SELECT * FROM product ORDER BY `Id` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    * all records - get all product records
    *
    * @param  
    * @return associative array.
    */
    public function getActiveUserAll($UserId = 0, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM product WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }






    /*
    * searchProductFilter - get all product records
    *
    * @param  
    * @return associative array.
    */

    public function searchProductFilter($filterData = [])
    {
        if (empty($filterData))
            return false;

        $query = 'SELECT orderinventory.Id AS `OrderId`,
                 orderinventory.Status AS `OrderStatus`,
                 orderinventory.Currency AS `OrderCurrency`,
                 orderinventory.PaymentStatus AS `OrderPaymentStatus`,
                 marketplace.Id AS `MarketplaceId`,
                 marketplace.MarketName AS `MarketplaceName`,
                 product.Id AS `ProdId`
         FROM orderinventory
         LEFT JOIN marketplace
            ON marketplace.Id = orderinventory.MarketPlaceId
         LEFT JOIN product
         ON product.Id = orderinventory.StoreProductId';

        // Clear filter get all result
        if (isset($filterData['clear_filter']) && !empty($filterData['clear_filter'])) {
            $stmt = $this->db->query($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $is_or = false;
        $or = '';
        $query .= ' where';
        // sku filter
        if (isset($filterData['SKU']) && !empty($filterData['SKU'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .=  $or . ' product.`SKU` LIKE "%' . $filterData['SKU'] . '%" ';
            $is_or = true;
        }

        // title filter
        if (isset($filterData['Title']) && !empty($filterData['Title'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`Name` LIKE "%' . $filterData['Title'] . '%" ';
            $is_or = true;
        }

        // ISBN filter
        if (isset($filterData['ISBN']) && !empty($filterData['ISBN'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`ProdId` LIKE "%' . $filterData['ISBN'] . '%" ';
            $is_or = true;
        }

        // Author filter
        if (isset($filterData['Author']) && !empty($filterData['Author'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`AddtionalData` LIKE "%' . $filterData['Author'] . '%" ';
            $is_or = true;
        }

        // Order filter
        if (isset($filterData['Order']) && !empty($filterData['Order'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' orderinventory.`OrderId` LIKE "%' . $filterData['Order'] . '%" ';
            $is_or = true;
        }

        // Customer filter
        if (isset($filterData['Customer']) && !empty($filterData['Customer'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`AddtionalData` LIKE "%' . $filterData['Customer'] . '%" ';
            $is_or = true;
        }

        // Location filter
        if (isset($filterData['Location']) && !empty($filterData['Location'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`AddtionalData` LIKE "%' . $filterData['Location'] . '%" ';
            $is_or = true;
        }

        // Author filter
        if (isset($filterData['Note']) && !empty($filterData['Note'])) {
            $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
            $query .= $or . ' product.`Notes` LIKE "%' . $filterData['Note'] . '%" ';
            $is_or = true;
        }

        $stmt = $this->db->query($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find product by product record Id
    *
    * @param  Id  - Table record Id of product to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM product WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find product by product record UserId and Status
    *
    * @param  UserId  - Table record Id of product to find
    * @param  Status  - Table record Status of product to find
    * @return associative array.
    */
    public function findByUserId($UserId, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM product WHERE UserId = :UserId AND Status IN ($Status)");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /*
    * findBySKUProd - Find product by product record UserId and Status
    *
    * @param  UserId  - Table record Id of product to find
    * @param  Status  - Table record Status of product to find
    * @return associative array.
    */
    public function findBySKUProd($ProdSku, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM product WHERE SKU = :SKU AND Status IN ($Status)");
        $stmt->execute(['SKU' => $ProdSku]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find product by product record UserId and Status
    *
    * @param  UserId  - Table record Id of product to find
    * @param  Status  - Table record Status of product to find
    * @return associative array.
    */
    public function findByUserProd($UserId, $ProdId, $Status = array())
    {
        $Status = implode(',', $Status); // WITHOUT WHITESPACES BEFORE AND AFTER THE COMMA
        $stmt = $this->db->prepare("SELECT * FROM product WHERE UserId = :UserId AND Status IN ($Status) AND ProdId = :ProdId");
        $stmt->execute(['UserId' => $UserId, 'ProdId' => $ProdId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * addProduct - add a new product for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addProduct($form = array())
    {
        $query  = 'INSERT INTO product (Name, Notes, SKU, ProdId,BasePrice,ProdCondition,';
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
        $query  = 'UPDATE product SET ';
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
        $query  = 'UPDATE product SET ';
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

        $query  = 'INSERT INTO product (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }


    /*
    * delete - delete a product records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM product WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletemultiple($ids = null)
    {
        $mParams = str_repeat('?,', count($ids) - 1) . '?';
        $sth = $this->db->prepare("DELETE FROM product WHERE Id IN ($mParams)");
        return $sth->execute($ids);
     
    }

    public function select_multiple_ids($ids = null)
    {
        $in  = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM product WHERE Id IN ($in)";
        $stm = $this->db->prepare($sql);
        $stm->execute($ids);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
     
    }
}
