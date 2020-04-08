<?php declare(strict_types = 1);

namespace App\Models\Product;

use PDO;

class Inventory
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * all - Get all Zomnes
    *
    * @param
    * @return associative array.
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Product ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function productSelect($form=[])
    {
        $start = 'a';
        if (isset($form['cat'])) {
            $start = $form['cat'];
        }
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM product WHERE `Name` LIKE ":name'.'%"');
        $stmt->bindParam(':name', $start, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /*
     * findParents - get top level categories
     *
     * @return array of arrays
    */
    public function findParents()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM product WHERE ParentId = 0 ORDER BY `Name`');
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
    public function findByUserId($UserId , $Status = 0)
    {
        $stmt = $this->db->prepare('SELECT * FROM product WHERE UserId = :UserId AND Status = :Status');
        $stmt->execute(['UserId' => $UserId,'Status' => $Status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $query .= 'CategoryId, Status,UserId,Image';
        $query .= ') VALUES (';
        $query .= ':Name, :Notes, :SKU, :ProdId,:BasePrice, :ProdCondition,';
        $query .= ':ProdActive, :InternationalShip, :ExpectedShip, :EbayTitle, :Qty,';
        $query .= ':CategoryId, :Status, :UserId, :Image';
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
        $query  = 'UPDATE product SET ';
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
}