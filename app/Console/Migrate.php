<?php declare(strict_types = 1);

namespace App\Console;

use App\Library\Config;
use PDO;
use PDOException;
use DirectoryIterator;

class Migrate
{
    private $db;
    private $migration_folder;
    
    public function __construct()
    {
        $this->db = new PDO('mysql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
    
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->query('CREATE TABLE IF NOT EXISTS `Migration` (`Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`Name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');        
        $this->migration_folder = __DIR__.'/../..'.Config::get('migration_folder');
    }
    
    public function run ()
    {
        // find already processed migrations
        $migrated = [];
        $stmt = $this->db->prepare('select * from Migration');
        $stmt->execute();
        $processed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($processed as $process) {
            $migrated[$process['Name']] = 1;
        }
        
        $migrations = [];
        foreach (new DirectoryIterator($this->migration_folder) as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..'
                || array_key_exists($file->getFileName(), $migrated))
                continue;
            $migrations[] = $file->getFilename();
        }
        
        if (count($migrations) > 0) {
            sort($migrations);
            array_map([$this, 'migrate'], $migrations);
        }
    }
    
    private function migrate ($migration) {
        print "\n\nMigrating $migration";
    
        try {
            $this->db->query(file_get_contents($this->migration_folder . $migration));
        } catch (PDOException $exception) {
            print "\n\n".' - Error executing query, check syntax.'."\n";
            print '   '. $exception->getMessage() . "\n\n";
            return false;
        };
        $this->db->prepare('INSERT INTO Migration (Name) VALUES (:name)')
            ->execute(['name' => $migration]);
        print ' - SUCCESS'."\n\n";
        return true;
    }
}