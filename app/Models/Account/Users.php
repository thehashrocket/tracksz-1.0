<?php declare(strict_types = 1);

namespace App\Models\Account;

use PDO;

// class to interact with the "users" table created to
// delight-im/PHP-Auth authentication system found at
// https://github.com/delight-im/PHP-Auth
class Users
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
     * username -find username based on email address
     *           We added an email index to users table not
     *           there by default in delight-im/PHP-Auth
     * @return array of arrays
    */
    public function username($email)
    {
        $stmt = $this->db->prepare('SELECT username FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
     * email -find email based on username
     *           We added an email index to users table not
     *           there by default in delight-im/PHP-Auth
     * @return array of arrays
    */
    public function email($username)
    {
        $stmt = $this->db->prepare('SELECT email FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}