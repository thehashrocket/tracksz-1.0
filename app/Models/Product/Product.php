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
    * Product Condition all records - get all product Condition records
    *
    * @param  
    * @return associative array.
    */

     public function getProdconditionData()
    {
       
        $stmt = $this->db->prepare("SELECT ProdCondition FROM product GROUP BY ProdCondition ORDER BY `Id` DESC");

        $stmt->execute();
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
            $stmt = $this->db->prepare($query);
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

    public function updateProdInventorywithdelete($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $date = date('Y-m-d H:i:s');
        $update = substr($update, 0, -2);
        $query  = 'UPDATE product SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            var_dump($stmt->debugDumpParams());
            //exit();
            return false;
        };

        $stmt = null;

        
        $ids=null;
        $sth = $this->db->prepare("DELETE FROM product WHERE Updated < '$date';");
        return $sth->execute($ids);

        return true;
    }

    public function addProdInventorywithdelete($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }

        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $date = date('Y-m-d H:i:s');

        $query  = 'INSERT INTO product (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);

        if (!$stmt->execute($form)) {
            return false;
        }

        $ids=null;
        $sth = $this->db->prepare("DELETE FROM product WHERE Updated < '$date';");
        return $sth->execute($ids);

        $stmt = null;
        return $this->db->lastInsertId();
    }

    public function updateProdInventoryPrice($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;

        foreach ($columns as $column => $value) {
            if($column=='BasePrice')
            {
                $update .= $column . ' = :' . $column . ', ';
                $values[$column] = $value;
            }
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
    public function getmarketplace($MarketName)
    {

        $stmt = $this->db->prepare('SELECT * FROM product WHERE MarketPlaceId = :MarketPlaceId');
        $stmt->execute(['MarketPlaceId' => $MarketName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }









    /*
     @author    :: Tejas
     @task_id   :: 
     @task_desc :: 
     @params    :: 
    */
    public function getProductsBelongsTo($ProdId)
    {
        $stmt = $this->db->prepare('SELECT product.*, 
        marketprice_master.Id as `MarketPriceId`,
        marketprice_master.ProductId as `MarketPriceProductId`,
        marketprice_master.AbeBooks as `AbeBooks`,
        marketprice_master.Alibris as `Alibris`,
        marketprice_master.Amazon as `Amazon`,
        marketprice_master.AmazonEurope as `AmazonEurope`,
        marketprice_master.BarnesAndNoble as `BarnesAndNoble`,
        marketprice_master.Biblio as `Biblio`,
        marketprice_master.Chrislands as `Chrislands`,
        marketprice_master.eBay as `eBay`,
        marketprice_master.eCampus as `eCampus`,
        marketprice_master.TextbookRush as `TextbookRush`,
        marketprice_master.TextbookX as `TextbookX`,
        marketprice_master.Valore as `Valore`,

        shipping_templates.Id as `ShipTemplateId`,
        shipping_templates.ProductId as `ShipTemplateProductId`,
        shipping_templates.AbeBooksTemplate as `AbeBooksTemplate`,
        shipping_templates.AlibrisTemplate as `AlibrisTemplate`,
        shipping_templates.AmazonTemplate as `AmazonTemplate`,
        shipping_templates.AmazonEuropeTemplate as `AmazonEuropeTemplate`,
        shipping_templates.BarnesAndNobleTemplate as `BarnesAndNobleTemplate`,
        shipping_templates.BiblioTemplate as `BiblioTemplate`,
        shipping_templates.ChrislandsTemplate as `ChrislandsTemplate`,
        shipping_templates.eBayTemplate as `eBayTemplate`,
        shipping_templates.eCampusTemplate as `eCampusTemplate`,
        shipping_templates.TextbookRushTemplate as `TextbookRushTemplate`,
        shipping_templates.TextbookXTemplate as `TextbookXTemplate`,
        shipping_templates.ValoreTemplate as `ValoreTemplate`,
        shipping_templates.DefaultTemplate as `DefaultTemplate`,

        ebay_shipping_rates.Id as `ShipRateId`,
        ebay_shipping_rates.ProductId as `ShipRateProductId`,
        ebay_shipping_rates.Domestic as `Domestic`,
        ebay_shipping_rates.International as `International`,

        marketplace_handletime.Id as `HandlingTimeId`,
        marketplace_handletime.ProductId as `HandlingTimeProductId`,
        marketplace_handletime.AbeBooksHandlingTime as `AbeBooksHandlingTime`,
        marketplace_handletime.AlibrisHandlingTime as `AlibrisHandlingTime`,
        marketplace_handletime.AmazonHandlingTime as `AmazonHandlingTime`,
        marketplace_handletime.AmazonEuropeHandlingTime as `AmazonEuropeHandlingTime`,
        marketplace_handletime.BarnesAndNobleHandlingTime as `BarnesAndNobleHandlingTime`,
        marketplace_handletime.BiblioHandlingTime as `BiblioHandlingTime`,
        marketplace_handletime.ChrislandsHandlingTime as `ChrislandsHandlingTime`,
        marketplace_handletime.eBayHandlingTime as `eBayHandlingTime`,
        marketplace_handletime.eCampusHandlingTime as `eCampusHandlingTime`,
        marketplace_handletime.TextbookRushHandlingTime as `TextbookRushHandlingTime`,
        marketplace_handletime.TextbookXHandlingTime as `TextbookXHandlingTime`,
        marketplace_handletime.ValoreHandlingTime as `ValoreHandlingTime`,
        marketplace_handletime.DefaultHandlingTime as `DefaultHandlingTime`,

        marketspecific_addtional.Id as `HandlingTimeId`,
        marketspecific_addtional.ProductId as `HandlingTimeProductId`,
        marketspecific_addtional.Cost as `Cost`,
        marketspecific_addtional.Location as `Location`,
        marketspecific_addtional.Brand as `Brand`,
        marketspecific_addtional.Language as `Language`,
        marketspecific_addtional.AdditionalUIEE as `AdditionalUIEE`,
        marketspecific_addtional.Source as `Source`,
        marketspecific_addtional.Category as `Category`,
        marketspecific_addtional.ManufacturerPartNumber as `ManufacturerPartNumber`
FROM product
LEFT JOIN marketprice_master
   ON marketprice_master.ProductId = product.Id
LEFT JOIN shipping_templates
   ON shipping_templates.ProductId = product.Id
LEFT JOIN ebay_shipping_rates
   ON ebay_shipping_rates.ProductId = product.Id
LEFT JOIN marketplace_handletime
   ON marketplace_handletime.ProductId = product.Id
LEFT JOIN marketspecific_addtional
   ON marketspecific_addtional.ProductId = product.Id
   WHERE product.Id = ' . $ProdId . '');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
