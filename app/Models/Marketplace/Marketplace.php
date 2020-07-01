<?php

declare(strict_types=1);

namespace App\Models\Marketplace;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Marketplace
{
    // Contains Resources
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    /*
    * all records - get all marketplace records
    *
    * @param  
    * @return associative array.
    */
    public function getAll()
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace ORDER BY `Id` DESC');
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
        $stmt = $this->db->prepare("SELECT * FROM marketplace WHERE UserId = :UserId AND Status IN ($Status) ORDER BY `Id` DESC");
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*
    * find - Find Marketplace by marketplace record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find Marketplace by marketplace record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findPriceById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM `marketprice_master` WHERE Id = :Id');
        $stmt->execute(['Id' => $Id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find Marketplace by marketplace record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findPriceProductId($ProductId)
    {
        $stmt = $this->db->prepare('SELECT * FROM `marketprice_master` WHERE ProductId = :ProductId');
        $stmt->execute(['ProductId' => $ProductId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find find Template ProductId record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findTemplateProductId($ProductId)
    {
        $stmt = $this->db->prepare('SELECT * FROM `shipping_templates` WHERE ProductId = :ProductId');
        $stmt->execute(['ProductId' => $ProductId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find find Ship rate ProductId record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findShipRateProductId($ProductId)
    {
        $stmt = $this->db->prepare('SELECT * FROM `ebay_shipping_rates` WHERE ProductId = :ProductId');
        $stmt->execute(['ProductId' => $ProductId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find find Ship rate ProductId record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findHandlingTimeProductId($ProductId)
    {
        $stmt = $this->db->prepare('SELECT * FROM `marketplace_handletime` WHERE ProductId = :ProductId');
        $stmt->execute(['ProductId' => $ProductId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find find Ship rate ProductId record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findAdditionalProductId($ProductId)
    {
        $stmt = $this->db->prepare('SELECT * FROM `marketspecific_addtional` WHERE ProductId = :ProductId');
        $stmt->execute(['ProductId' => $ProductId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function findByUserId($UserId, $Status = 0)
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace WHERE UserId = :UserId AND Status = :Status');
        $stmt->execute(['UserId' => $UserId, 'Status' => $Status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function findFtpDetails($MarketName, $UserId = 0,  $Status = 0)
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace WHERE MarketName = :MarketName AND UserId = :UserId AND Status = :Status');
        $stmt->execute(['MarketName' => $MarketName, 'UserId' => $UserId, 'Status' => $Status]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find MarketName by UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return market place name associative array.
    */

     public function get_market_place_name($UserId = 0,  $Status = 0)
    {
    
        $stmt = $this->db->prepare('SELECT MarketName FROM marketplace WHERE UserId = :UserId AND Status = :Status');
        $stmt->execute(['UserId' => $UserId, 'Status' => $Status]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*
    * addMarketplace - add a new marketplace for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addMarketplace($form = array())
    {
        $query  = 'INSERT INTO marketplace (MarketName, EmailAddress, SellerID, Password,FtpAddress,FtpUserId,';
        $query .= 'FtpPassword, PrependVenue,AppendVenue,IncreaseMinMarket,FileFormat,';
        $query .= 'FtpAppendVenue, SuspendExport,SendDeletes,MarketAcceptPrice,MarketAcceptPriceVal,';
        $query .= 'MarketAcceptPriceValMulti, MarketSpecificPrice, MarketAcceptPriceVal2,MarketAcceptPriceValMulti2, Status, UserId';
        $query .= ') VALUES (';
        $query .= ':MarketName, :EmailAddress, :SellerID, :Password,:FtpAddress, :FtpUserId,';
        $query .= ':FtpPassword, :PrependVenue, :AppendVenue, :IncreaseMinMarket, :FileFormat,';
        $query .= ':FtpAppendVenue, :SuspendExport, :SendDeletes, :MarketAcceptPrice, :MarketAcceptPriceVal,';
        $query .= ':MarketAcceptPriceValMulti, :MarketSpecificPrice, :MarketAcceptPriceVal2, :MarketAcceptPriceValMulti2, :Status, :UserId';
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
    public function editMarket($form)
    {
        $query  = 'UPDATE marketplace SET ';
        $query .= 'MarketName = :MarketName, ';
        $query .= 'EmailAddress = :EmailAddress, ';
        $query .= 'SellerID = :SellerID, ';
        $query .= 'Password = :Password, ';
        $query .= 'FtpAddress = :FtpAddress, ';
        $query .= 'FtpUserId = :FtpUserId, ';
        $query .= 'FtpPassword = :FtpPassword, ';
        $query .= 'PrependVenue = :PrependVenue, ';
        $query .= 'AppendVenue = :AppendVenue, ';
        $query .= 'IncreaseMinMarket = :IncreaseMinMarket,';
        $query .= 'FileFormat = :FileFormat, ';
        $query .= 'FtpAppendVenue = :FtpAppendVenue, ';
        $query .= 'SuspendExport = :SuspendExport, ';
        $query .= 'SendDeletes = :SendDeletes, ';
        $query .= 'MarketAcceptPrice = :MarketAcceptPrice, ';
        $query .= 'MarketAcceptPriceVal = :MarketAcceptPriceVal, ';
        $query .= 'MarketAcceptPriceValMulti = :MarketAcceptPriceValMulti, ';
        $query .= 'MarketSpecificPrice = :MarketSpecificPrice, ';
        $query .= 'MarketAcceptPriceVal2 = :MarketAcceptPriceVal2, ';
        $query .= 'MarketAcceptPriceValMulti2 = :MarketAcceptPriceValMulti2, ';
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



    /*
    * delete - delete a Marketplace records
    *
    * @param  $id = table record ID   
    * @return boolean
    */
    public function delete($Id = null)
    {
        $query = 'DELETE FROM marketplace WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*
     * addStore - add a new store for member
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addMarketPlacePrice($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO `marketprice_master` (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }

    public function updateMarketPrice($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `marketprice_master` SET ';
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

    public function updateMarketPriceProduct($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['ProductId'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `marketprice_master` SET ';
        $query .= $update . ' ';
        $query .= 'WHERE ProductId = :ProductId';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            var_dump($stmt->debugDumpParams());
            exit();
            return false;
        };

        $stmt = null;
        return true;
    }


    public function updateMarketTemplateProduct($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['ProductId'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `shipping_templates` SET ';
        $query .= $update . ' ';
        $query .= 'WHERE ProductId = :ProductId';

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
     * addMarketTemplateProduct - add a new template
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addMarketTemplateProduct($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO `shipping_templates` (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }

    public function updateMarketShipRateProduct($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['ProductId'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `ebay_shipping_rates` SET ';
        $query .= $update . ' ';
        $query .= 'WHERE ProductId = :ProductId';

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
     * addMarketShipRateProduct - add a new ship rate
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addMarketShipRateProduct($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO `ebay_shipping_rates` (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }


    public function updateMarketHindlingProduct($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['ProductId'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `marketplace_handletime` SET ';
        $query .= $update . ' ';
        $query .= 'WHERE ProductId = :ProductId';

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
     * addMarketHindlingProduct - add a new ship rate
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addMarketHindlingProduct($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO `marketplace_handletime` (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }

    public function updateAdditionalProduct($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['ProductId'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column . ' = :' . $column . ', ';
            $values[$column] = $value;
        }

        $update = substr($update, 0, -2);
        $query  = 'UPDATE `marketspecific_addtional` SET ';
        $query .= $update . ' ';
        $query .= 'WHERE ProductId = :ProductId';

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
     * addAdditionalProduct - add a new ship rate
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function addAdditionalProduct($form)
    {
        $insert = '';
        $values = '';
        foreach ($form as $key => $value) {
            $insert .= $key . ', ';
            $values .= ':' . $key . ', ';
        }
        $insert = substr($insert, 0, -2);
        $values = substr($values, 0, -2);

        $query  = 'INSERT INTO `marketspecific_addtional` (' . $insert . ') ';
        $query .= 'VALUES(' . $values . ')';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }
        $stmt = null;
        return $this->db->lastInsertId();
    }
    /********************************* */
    // [0] => __construct
    // [1] => prepare
    // [2] => beginTransaction
    // [3] => commit
    // [4] => rollBack
    // [5] => inTransaction
    // [6] => setAttribute
    // [7] => exec
    // [8] => query
    // [9] => lastInsertId
    // [10] => errorCode
    // [11] => errorInfo
    // [12] => getAttribute
    // [13] => quote
    // [14] => __wakeup
    // [15] => __sleep
    // [16] => getAvailableDrivers  
}
