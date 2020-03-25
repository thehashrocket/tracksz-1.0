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
    * find - Find address by marketplace record Id
    *
    * @param  Id  - Table record Id of marketplace to find
    * @return associative array.
    */
    public function findById($Id)
    {
        $stmt = $this->db->prepare('SELECT * FROM Marketplace WHERE Id = :Id AND IsDeleted = :Value');
        $stmt->execute(['Id' => $Id, 'Value' => 0]);
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
        $query  = 'INSERT INTO marketplace (MarketName, EmailAddress, SellerID, Password,FtpUserId,';
        $query .= 'FtpPassword, PrependVenue,AppendVenue,IncreaseMinMarket,FileFormat,';
        $query .= 'FtpAppendVenue, SuspendExport,SendDeletes,MarketAcceptPrice,MarketAcceptPriceVal,';
        $query .= 'MarketAcceptPriceValMulti, MarketSpecificPrice, MarketAcceptPriceVal2,MarketAcceptPriceValMulti2';                
        $query .= ') VALUES (';
        $query .= ':MarketName, :EmailAddress, :SellerID, :Password, :FtpUserId,';
        $query .= ':FtpPassword, :PrependVenue, :AppendVenue, :IncreaseMinMarket, :FileFormat,';
        $query .= ':FtpAppendVenue, :SuspendExport, :SendDeletes, :MarketAcceptPrice, :MarketAcceptPriceVal,';
        $query .= ':MarketAcceptPriceValMulti, :MarketSpecificPrice, :MarketAcceptPriceVal2, :MarketAcceptPriceValMulti2';        
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
    public function editAddress($form)
    {
        $query  = 'UPDATE Address SET ';
        $query .= 'FullName = :FullName, ';
        $query .= 'Address1 = :Address1, ';
        $query .= 'Address2 = :Address2, ';
        $query .= 'Company = :Company, ';
        $query .= 'City = :City, ';
        $query .= 'PostCode = :PostCode, ';
        $query .= 'CountryId = :CountryId, ';
        $query .= 'ZoneId = :ZoneId, ';
        $query .= 'IsDeleted = 0, ';
        $query .= 'MemberId = :MemberId, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE Id = :Id';
        $form['Updated'] = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['Id'];
    }
    
 
    
    /*
    * delete - delete a members address, by Address Table ID and User Id
    *
    * @param  $id = table record numbver of card
    * @param $memberId - member Id of Logged in User
    * @return boolean
    */
    public function delete($Id, $memberId)
    {
        $query  = 'UPDATE Address SET IsDeleted = :Value WHERE Id = :Id AND MemberId = :MemberId';
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute(['Value' => 1, 'Id' => $Id, 'MemberId' => $memberId])) {
            return false;
        }
        $stmt = null;
        return true;
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