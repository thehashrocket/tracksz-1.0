<?php declare(strict_types = 1);

namespace App\Models\Inventory;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class Category
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
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function count_all_records()
    {
        $stmt = $this->db->prepare('SELECT COUNT(`Id`) as total_records FROM Category');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function all()
    {
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


     /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function get_all_records($search_params)
    {
        $sql    = new Sql($this->adapter);
        $select = $sql->select();
        $select_filter = $sql->select();
        $select->from('category');
        
        // echo "<pre>";
        // print_r(get_class_methods(get_class($sql)));
        // exit;
        // if(array_key_exists('cst_policy_number', $search_params) 
        // && !empty($search_params['cst_policy_number']))
        //     $select->where("sys_h_policy.cst_policy_number", $search_params['cst_policy_number']);

        // $select->where(['Id' => 1]);

        // Server side processing
        if(array_key_exists('search', $search_params) 
        && !empty($search_params['search'])){ // search not empty

                $select->where('ParentId','LIKE',"%{$search_params['search']}%");
                $select->where('Name', 'LIKE',"%{$search_params['search']}%");
                $select->where('Description', 'LIKE',"%{$search_params['search']}%");
                $select->offset($search_params['start']);
                $select->limit($search_params['limit']);
             //   $select->orderBy($search_params['order'],$search_params['dir']);        

    

    }else{ // search is empty    
            if(array_key_exists('start', $search_params))
                    $select->offset($search_params['start']);

            if(array_key_exists('limit', $search_params) 
                && !empty($search_params['limit']))                                       
                    $select->limit($search_params['limit']);

            // if(array_key_exists('order', $search_params) 
            //     && !empty($search_params['order']))                                       
            //         $select->orderBy($search_params['order'],$search_params['dir']);
    }   
        $selectString = $sql->buildSqlString($select);        
        $results = $this->adapter->query($selectString,$this->adapter::QUERY_MODE_EXECUTE);
        return $results = $results->toArray();        
    }
    
    /*
    * all - Get all Zomnes
    *
    * @return array of arrays
    */
    public function categorySelect($form=[])
    {
        $start = 'a';
        if (isset($form['cat'])) {
            $start = $form['cat'];
        }
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category WHERE `Name` LIKE ":name'.'%"');
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
        $stmt = $this->db->prepare('SELECT Id, `Name` FROM Category WHERE ParentId = 0 ORDER BY `Name`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * findParents - get top level categories
     *
     * @return array of arrays
    */
    public function insert_category($ParentId = null, $Name= null, $Description= null, $Image= null,$Created= null, $Update= null)
    {   
        $res['status'] = false;
        $res['message'] = 'record not inserted';
        $res['data'] = array();
        $stmt = $this->db->prepare("INSERT INTO `category` (`Id`, `ParentId`,`Name`, `Description`, `Image`, `Created`, `Update`) VALUES (NULL, $ParentId, '$Name', '$Description', '$Image', '$Created', '$Update')");                  
        $stmt->execute();
        if($stmt){
            $res['status'] = true;
            $res['message'] = 'insert success';
            $res['data'] = array();
        }
        return $res;
    }

    
}
