<?php declare(strict_types = 1);

// include full path to this class
namespace App\Console\zzExample;

use App\Models\zzExampleModel;
use PDO;


class zzExample
{
    // Need database access
    private $db;
    private $lookup = 'Virgil Collier';
    
    public function __construct($data=[])
    {
        if (isset($data[0]) && $data[0] !== '') {
            $this->lookup = str_replace('~', ' ', $data[0]);
        }
        
        // create PDO.  No access to PDO Service
        $this->db = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    
    public function run ()
    {
        $results = (new zzExampleModel($this->db))->find($this->lookup);
        foreach ($results as $result) {
            echo $result['ExampleName'] . "\n";
        }
        echo "\n".' - SUCCESS'."\n\n";
    }
    
}