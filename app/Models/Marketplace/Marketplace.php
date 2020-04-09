<?php declare(strict_types = 1);

namespace App\Models\Marketplace;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Marketplace
{
    // Contains Resources
    private $db;
    
    private $adapter;
    
    public function __construct(PDO $db,Adapter $adapter = null)
    {
        $this->db = $db;  
        $this->adapter = new Adapter([
            'driver'   => 'Mysqli',
            'database' => getenv('DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD')
        ]);   
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
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function findByUserId($UserId , $Status = 0)
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace WHERE UserId = :UserId AND Status = :Status');
        $stmt->execute(['UserId' => $UserId,'Status' => $Status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function findFtpDetails($MarketName , $UserId = 0 ,  $Status = 0)
    {
        $stmt = $this->db->prepare('SELECT * FROM marketplace WHERE MarketName = :MarketName AND UserId = :UserId AND Status = :Status');
        $stmt->execute(['MarketName' => $MarketName, 'UserId' => $UserId,'Status' => $Status]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
