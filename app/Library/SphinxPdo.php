<?php declare(strict_types = 1);

namespace App\Library;

use PDO;

class SphinxPdo extends PDO
{
    
    public function __construct($username = NULL, $password = NULL, $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        
        $options = array_replace($default_options, $options);
        parent::__construct('mysql:host=127.0.0.1;port=9306;', $username, $password, $options);
    }

}