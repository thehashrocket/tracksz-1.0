<?php

namespace App\Models;

use App\Controllers\Inventory\InventoryTrait;

class TestJob1
{
    use InventoryTrait;
    public function setUp()
    {
    }

    public function perform()
    {
        echo '<pre> Test 1 :: Jpb calls';
        print_r($this->db);
        // print_r($this->args);
        echo '</pre>';
        die('LOOP ENDS HERE');

        // $map_data = $this->mapUIEEFieldsAttributes($this->args['marketplace'], $this->args['data'], $this->args['market_place_map']);
        // $is_result = $this->insertOrUpdateInventory($map_data, $this->args['UserId']);
    }

    private function mapUIEEFieldsAttributes($marketPlaceName = "", $fileData = array(), $market_place_map = array())
    {
        if (empty($marketPlaceName))
            return false;

        $map_uiee = [];
        $key_counter = 0;
        foreach ($fileData as $file_key => $file_val) {
            if (is_string($file_val) && empty(trim($file_val))) {
                $key_counter = $key_counter + 1;
                continue;
            }

            $uiee_key = strstr($file_val, "|", true);
            if (!empty($uiee_key)) {
                $arr_file = explode('|', $file_val);
                if (in_array($uiee_key, array_keys($market_place_map[$marketPlaceName]))) { // *found           
                    $map_uiee[$key_counter][$market_place_map[$marketPlaceName][$uiee_key]] = end($arr_file);
                } else { // ! not found
                    if (isset($market_place_map[$marketPlaceName]['AddtionalData'][$uiee_key]))
                        $map_uiee[$key_counter]['AddtionalData'][$market_place_map[$marketPlaceName]['AddtionalData'][$uiee_key]] = end($arr_file);
                }
            }
        }
        return $map_uiee;
    }

    /*
    * insertOrUpdate - find user id if exist
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function insertOrUpdateInventory($data, $UserId)
    {
        foreach ($data as $data_val) {
            if (isset($data_val['ProdId']) && !empty($data_val['ProdId'])) {
                $data_val['AddtionalData'] = json_encode($data_val['AddtionalData']);

                $is_exist = (new Product($this->db))->findByUserProd($UserId, $data_val['ProdId'], [0, 1]);
                $data = (isset($is_exist) && !empty($is_exist)) ? (new Product($this->db))->updateProdInventory($is_exist['Id'], $data_val) : (new Product($this->db))->addProdInventory($data_val);
            }
        }
        return true;
    }

    public function tearDown()
    {
        // ... Remove environment for this job
    }
}
