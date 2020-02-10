<?php declare(strict_types = 1);

namespace App\Models\Account;

use App\Library\Config;
use PDO;

// Links to various websites and social media

class StoreLinks
{
    // Contains Resources
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    
    /*
     * find - Find a store's web links
     *
     * @param  $Id  - StoreId from Store table
     * @return send associative array back with required data
    */
    public function find($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM StoreLinks WHERE StoreId = :Id');
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $return_links = [];
        $links = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($links as $link) {
            $return_links[Config::get('link_types_reverse')[$link['LinkId']]] = $link['LinkUrl'];
        }
        return $return_links;
    }
    
    /*
     * replaceLinks - Replace links for a store
     *
     * @param  $form  - Array of form fields, name match Database Fields
     *                  Form Field Names MUST MATCH Database Column Names
     * @return boolean
    */
    public function replaceLinks($store_id, $form)
    {
        $links_used = '';
        foreach ($form as $key => $value) {
            $link_id = Config::get('link_types')[$key];
            $links_used .= intval($link_id).', ';
            
            $query  = 'REPLACE INTO StoreLinks (StoreId, LinkId, LinkUrl) ';
            $query .= 'VALUES(:store_id, :LinkId, :LinkUrl)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
            $stmt->bindParam(':LinkId', $link_id, PDO::PARAM_INT);
            $stmt->bindParam(':LinkUrl', $value, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                return false;
            }
        }
        $links_used = substr($links_used, 0, -2);
        
        $query  = 'DELETE FROM StoreLinks WHERE StoreId = :store_id ';
        $query .= 'AND LinkId NOT IN ('.$links_used.')';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            return false;
        }
        $stmt = null;
        return true;
    }
    
}