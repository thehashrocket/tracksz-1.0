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
    * DATE RANGE - Find orderinventory by orderinventory record Id
    *
    * @param  Id  - Table record Id of orderinventory to find
    * @return associative array.
    */
    public function dateRangeSearchByOrderData($formD, $ToD)
    {
        $stmt = $this->db->prepare('SELECT * FROM orderinventory WHERE Created between "2020-05-12" And "2020-05-20"');
        $stmt->execute(['Created' => $formD, 'Created' => $ToD]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function orderstatusSearchByOrderData($export_val)
    {
        $stmt = $this->db->prepare('SELECT * FROM orderinventory WHERE Status = :Status');
        $stmt->execute(['Status' => $export_val]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allorderSearchByOrderData()
    {
        $stmt = $this->db->prepare('SELECT * FROM orderinventory ORDER BY `Id` DESC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     @author    :: Tejas
     @task_id   :: 
     @task_desc :: 
     @params    :: 
    */
    public function getPackingOrders($filter_data)
    {
        $stmt = $this->db->prepare('SELECT orderinventory.Id AS `OrderTableId`,
        orderinventory.OrderId AS `OrderId`,
        marketplace.MarketName AS `MarketplaceName`,
        orderinventory.Created AS `OrderDate`,
        orderinventory.ShippingMethod AS `ShippingMethod`,
        -- shipping
        orderinventory.ShippingName AS `ShippingName`,
        orderinventory.ShippingAddress1 AS `ShippingAddress1`,
        orderinventory.ShippingAddress2 AS `ShippingAddress2`,
        orderinventory.ShippingAddress3 AS `ShippingAddress3`,
        orderinventory.ShippingCity AS `ShippingCity`,
        orderinventory.ShippingState AS `ShippingState`,
        orderinventory.ShippingZipCode AS `ShippingZipCode`,
        orderinventory.ShippingCountry AS `ShippingCountry`,
        orderinventory.ShippingPhone AS `ShippingPhone`,
        orderinventory.ShippingMethod AS `ShippingMethod`,
        -- billing
        orderinventory.BillingName AS `BillingName`,
        orderinventory.BillingAddress1 AS `BillingAddress1`,
        orderinventory.BillingAddress2 AS `BillingAddress2`,
        orderinventory.BillingAddress3 AS `BillingAddress3`,
        orderinventory.BillingCity AS `BillingCity`,
        orderinventory.BillingState AS `BillingState`,
        orderinventory.BillingZipCode AS `BillingZipCode`,
        orderinventory.BillingCountry AS `BillingCountry`,
        orderinventory.BillingPhone AS `BillingPhone`,
        -- billing
        product.Id AS `ProductTableId`,
        product.Qty AS `ProductQty`,
        product.ProdId AS `ProductISBN`,
        product.Name AS `ProductName`,
        product.Notes AS `ProductDescription`,
        product.ProdCondition AS `ProductCondition`,
        product.SKU AS `ProductSKU`,
        orderinventory.BuyerNote AS `ProductBuyerNote`,
        orderinventory.Id AS `MarketplaceId`
        
FROM orderinventory
LEFT JOIN marketplace
   ON marketplace.Id = orderinventory.MarketPlaceId
   LEFT JOIN product
   ON product.Id = orderinventory.StoreProductId');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    /*
    * find - Find getStatusOrders by orderinventory record status
    *
    * @param  Id  - Table record Id of orderinventory to find
    * @return associative array.
    */
    public function getStatusOrders($Status)
    {
        if ($Status == 'all') {
            $stmt = $this->db->prepare('SELECT orderinventory.Id AS `OrderId`,
            orderinventory.Status AS `OrderStatus`,
            orderinventory.Currency AS `OrderCurrency`,
            orderinventory.PaymentStatus AS `OrderPaymentStatus`,
            marketplace.Id AS `MarketplaceId`,
            marketplace.MarketName AS `MarketplaceName`
    FROM orderinventory
    LEFT JOIN marketplace
       ON marketplace.Id = orderinventory.MarketPlaceId');
        } else {
            $stmt = $this->db->prepare('SELECT orderinventory.Id AS `OrderId`,
            orderinventory.Status AS `OrderStatus`,
            orderinventory.Currency AS `OrderCurrency`,
            orderinventory.PaymentStatus AS `OrderPaymentStatus`,
            marketplace.Id AS `MarketplaceId`,
            marketplace.MarketName AS `MarketplaceName`
    FROM orderinventory
    LEFT JOIN marketplace
       ON marketplace.Id = orderinventory.MarketPlaceId Where orderinventory.Status = :Status');
            $stmt->bindParam('Status', $Status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    * find - Find getStatusOrders by orderinventory record status
    *
    * @param  Id  - Table record Id of orderinventory to find
    * @return associative array.
    */
    public function getPickOrderStatus($Status)
    {
        if ($Status['status'] == 'all') {
            $query = 'SELECT orderinventory.Id AS `OrderTableId`,
            orderinventory.OrderId AS `OrderId`,
            marketplace.MarketName AS `MarketplaceName`,
            orderinventory.Created AS `OrderDate`,
            orderinventory.ShippingMethod AS `ShippingMethod`,
            -- shipping
            orderinventory.ShippingName AS `ShippingName`,
            orderinventory.ShippingAddress1 AS `ShippingAddress1`,
            orderinventory.ShippingAddress2 AS `ShippingAddress2`,
            orderinventory.ShippingAddress3 AS `ShippingAddress3`,
            orderinventory.ShippingCity AS `ShippingCity`,
            orderinventory.ShippingState AS `ShippingState`,
            orderinventory.ShippingZipCode AS `ShippingZipCode`,
            orderinventory.ShippingCountry AS `ShippingCountry`,
            orderinventory.ShippingPhone AS `ShippingPhone`,
            orderinventory.ShippingMethod AS `ShippingMethod`,
            -- billing
            orderinventory.BillingName AS `BillingName`,
            orderinventory.BillingAddress1 AS `BillingAddress1`,
            orderinventory.BillingAddress2 AS `BillingAddress2`,
            orderinventory.BillingAddress3 AS `BillingAddress3`,
            orderinventory.BillingCity AS `BillingCity`,
            orderinventory.BillingState AS `BillingState`,
            orderinventory.BillingZipCode AS `BillingZipCode`,
            orderinventory.BillingCountry AS `BillingCountry`,
            orderinventory.BillingPhone AS `BillingPhone`,
            -- billing
            product.Id AS `ProductTableId`,
            product.Qty AS `ProductQty`,
            product.ProdId AS `ProductISBN`,
            product.Name AS `ProductName`,
            product.Notes AS `ProductDescription`,
            product.ProdCondition AS `ProductCondition`,
            product.SKU AS `ProductSKU`,
            product.BasePrice AS `ProductPrice`,
            orderinventory.BuyerNote AS `ProductBuyerNote`,
            orderinventory.Id AS `MarketplaceId`
            
    FROM orderinventory
    LEFT JOIN marketplace
       ON marketplace.Id = orderinventory.MarketPlaceId
       LEFT JOIN product
       ON product.Id = orderinventory.StoreProductId';
        } else {
            $query = 'SELECT orderinventory.Id AS `OrderTableId`,
            orderinventory.OrderId AS `OrderId`,
            marketplace.MarketName AS `MarketplaceName`,
            orderinventory.Created AS `OrderDate`,
            orderinventory.ShippingMethod AS `ShippingMethod`,
            -- shipping
            orderinventory.ShippingName AS `ShippingName`,
            orderinventory.ShippingAddress1 AS `ShippingAddress1`,
            orderinventory.ShippingAddress2 AS `ShippingAddress2`,
            orderinventory.ShippingAddress3 AS `ShippingAddress3`,
            orderinventory.ShippingCity AS `ShippingCity`,
            orderinventory.ShippingState AS `ShippingState`,
            orderinventory.ShippingZipCode AS `ShippingZipCode`,
            orderinventory.ShippingCountry AS `ShippingCountry`,
            orderinventory.ShippingPhone AS `ShippingPhone`,
            orderinventory.ShippingMethod AS `ShippingMethod`,
            -- billing
            orderinventory.BillingName AS `BillingName`,
            orderinventory.BillingAddress1 AS `BillingAddress1`,
            orderinventory.BillingAddress2 AS `BillingAddress2`,
            orderinventory.BillingAddress3 AS `BillingAddress3`,
            orderinventory.BillingCity AS `BillingCity`,
            orderinventory.BillingState AS `BillingState`,
            orderinventory.BillingZipCode AS `BillingZipCode`,
            orderinventory.BillingCountry AS `BillingCountry`,
            orderinventory.BillingPhone AS `BillingPhone`,
            -- billing
            product.Id AS `ProductTableId`,
            product.Qty AS `ProductQty`,
            product.ProdId AS `ProductISBN`,
            product.Name AS `ProductName`,
            product.Notes AS `ProductDescription`,
            product.ProdCondition AS `ProductCondition`,
            product.SKU AS `ProductSKU`,
            product.BasePrice AS `ProductPrice`,
            orderinventory.BuyerNote AS `ProductBuyerNote`,
            orderinventory.Id AS `MarketplaceId`
            
    FROM orderinventory
    LEFT JOIN marketplace
       ON marketplace.Id = orderinventory.MarketPlaceId
       LEFT JOIN product
       ON product.Id = orderinventory.StoreProductId';

            $is_or = false;
            $or = '';
            $query .= ' where';
            // sku filter
            if (isset($Status['status']) && !empty($Status['status'])) {
                $or = (isset($is_or) && $is_or == true) ? 'OR' : '';
                $query .=  $or . ' orderinventory.`Status` LIKE "%' . $Status['status'] . '%" ';
                $is_or = true;
            }
        }

        $stmt = $this->db->query($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function select_multiple_ids($ids = null)
    {
        $in  = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "SELECT * FROM orderinventory WHERE Id IN ($in)";
        $stm = $this->db->prepare($sql);
        $stm->execute($ids);
        return $stm->fetchAll(PDO::FETCH_ASSOC);
     
    }
}
