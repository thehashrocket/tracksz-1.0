<?php declare(strict_types = 1);

namespace App\Models\Account;

use App\Library\Config;
use App\Library\Image;
use App\Library\Utils;
use PDO;

class Member
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
    * find - Find a member based on MemberId
    *
    * @param  $id  - Provided by AUTH after registration
    * @return Sends back to login or profile page
    */
    public function find($Id)
    {
        $query = 'SELECT * FROM Member WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        };
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
     *
     * createMember - create a New Member entry after someone registers
     *                leaves mostly blank fields, until member updates profile
     *
     * @param  $form  - Array of form fields, name match Database Fields
     * @return boolean
    */
    public function createMember($userId)
    {
        // generate key for two encryption for all things encrypted for this member
        $add_member = [
            'Id' => (int)$userId,
            'IpAddress' => $_SERVER['REMOTE_ADDR'],
            'MemberKey' => sodium_crypto_secretbox_keygen(),
        ];
        // type `Type` appears to be a mysql keyword, put `` around.
        $query  = 'INSERT INTO Member (Id, IpAddress, MemberKey) VALUES (';
        $query .= ':Id, :IpAddress, :MemberKey)';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($add_member)) {
            return false;
        }
        $stmt = null;
        return true;
    }
    
    /*
    * columns - get one or more columns from Member Table
    *
    * @param  $id  - user id from Auth->getuserid
    * @param  $columns  - an array of one or or more columns from member
    * @return Sends back to login or profile page
    */
    public function columns($Id, $columns)
    {
        $select = '';
        foreach ($columns as $column) {
            $select .= $column.', ';
        }
        $select = substr($select, 0, -2);
        $query = 'SELECT '. $select . ' FROM Member WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Id' => $Id])) {
            return false;
        };
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
    * saveColumns - update one or more columns to Member Table
    *
    * @param  $id  - user id from Auth->getuserid
    * @param  $columns  - an array of one or or more columns from member
    *                     Form Field Names MUST MATCH Database Column Names
    * @return Sends back to login or profile page
    */
    public function updateColumns($Id, $columns)
    {
        $update = '';
        $values = [];
        $values['Id'] = $Id;
        foreach ($columns as $column => $value) {
            $update .= $column. ' = :'.$column .', ';
            $values[$column] = $value;
        }
        $update = substr($update, 0, -2);
        
        $query  = 'UPDATE Member SET ';
        $query .= $update . ' ';
        $query .= 'WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($values)) {
            return false;
        };
        
        $stmt = null;
        return true;
    }
    
    /**
     * memberAvatar - Change Member Avatar
     *
     * @return array
     */
    public function updateAvatar($id)
    {
        // create server sanitized file name
        $utils = new Utils();
        $filename = $id.'_'.$utils->sanitizeFileName($_FILES['avatar']['name']);
        
        // resize new file
        $image = new Image($_FILES['avatar']['tmp_name']);
        $image->autoOrient();
        $image->resize(400);
        
        if (!$image->toFile(Config::get('web_dir').'assets/images/member/'.$filename,
            null, 85)) {
            return ['uploaded' => false];
        }
        
        // If upload true, get old file if any and delete it
        $update  = $this->columns($id, ['Avatar']);
        if (!$update) {
            return ['uploaded' => false];
        }
        if ($update['Avatar'] && $update['Avatar'] != 'defaultavatar.png' && $update['Avatar'] != $filename
            && file_exists(Config::get('web_dir').'assets/images/member/'.$update['Avatar'])) {
            unlink(Config::get('web_dir').'assets/images/member/'.$update['Avatar']);
        }
        
        // update logo in Store Table
        $this->updateColumns($id, ['Avatar' => $filename]);
        
        // update Avatar in session using Auth Session
        \Delight\Cookie\Session::set('member_avatar', $filename);
        $files = [$filename];
        return ['files' => $files];
    }
    
    
    /**
     * gateway - Select GatewayUserId and MemberKey from Member for a member
     *
     * @param  $id  - Member Id from Member table
     * @return Sends back to login or profile page
    */
    public function gateway($id)
    {
        $query = 'SELECT GatewayUserId, MemberKey FROM Member WHERE Id = :Id';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute(['Id' => $id])) {
            return false;
        };
        return $stmt->fetchObject('stdClass');
    }
    
    /**
     * addGatewayId - Add member after registration before validate email
     *               Just add record for update later.
     *
     * @param  $id  - Provided by AUTH after registration
     * @return Sends back to login or profile page
    */
    public function addGatewayId($Id, $GatewayUserId)
    {
        $query  = 'UPDATE Member SET ';
        $query .= 'GatewayUserId = :GatewayUserId, ';
        $query .= 'Updated = :today ';
        $query .= 'WHERE Id = :Id';
        $today  = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare($query);
        
        if (!$stmt->execute(['Id' => $Id, 'GatewayUserId' => $GatewayUserId, 'today' => $today])) {
            return false;
        }
        $stmt = null;
        return true;
    }

    /**
     *  getActiveStoreId - Get active store ID with member ID
     * 
     *  @param  int - Member ID
     *  @return int - Active store ID
     */
    public function getActiveStoreId($memberId)
    {
        $query = 'SELECT ActiveStore FROM Member WHERE Id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['ActiveStore'];
    }
}