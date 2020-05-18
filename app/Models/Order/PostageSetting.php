<?php

declare(strict_types=1);

namespace App\Models\Order;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class PostageSetting
{
    // Contains Resources
    private $db;
    private $adapter;

    public function __construct(PDO $db, Adapter $adapter = null)
    {
        $this->db = $db;
    }


    /*
    * editOrderSettings - Find OrderSettings by OrderSettings record Id and update
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function editPostageSettings($form)
    {
        $query  = 'UPDATE postagesettings SET ';
        $query .= 'OperatingSystem = :OperatingSystem, ';
        $query .= 'MaxWeight = :MaxWeight, ';
        $query .= 'DeliveryConfirmation = :DeliveryConfirmation, ';
        $query .= 'MinOrderTotalDelivery = :MinOrderTotalDelivery, ';
        $query .= 'SignatureConfirmation = :SignatureConfirmation, ';
        $query .= 'ConsolidatorLabel = :ConsolidatorLabel, ';
        $query .= 'IncludeInsurance = :IncludeInsurance, ';
        $query .= 'MinOrderTotalInsurance = :MinOrderTotalInsurance, ';
        $query .= 'RoundDownPartial  = :RoundDownPartial, ';
        $query .= 'EstimatePostage = :EstimatePostage, ';
        $query .= 'MaxPostageBatch  = :MaxPostageBatch, ';
        $query .= 'CustomsSigner = :CustomsSigner, ';
        $query .= 'DefaultWeight = :DefaultWeight, ';
        $query .= 'FlatRatePriority = :FlatRatePriority, ';
        $query .= 'GlobalWeight = :GlobalWeight, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE UserId = :UserId ';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return 0;
        }
        $stmt = null;
        return $form['UserId'];
    }

    /*
    * addInventorySettings - add a new inventory settings for a user
    *
    * @param  $form  - Array of form fields, name match Database Fields
    *                  Form Field Names MUST MATCH Database Column Names
    * @return boolean
    */
    public function addPostageSettings($form = array())
    {
        $query  = 'INSERT INTO postagesettings (UserId,OperatingSystem,MaxWeight,DeliveryConfirmation,MinOrderTotalDelivery,SignatureConfirmation,ConsolidatorLabel,IncludeInsurance,MinOrderTotalInsurance,RoundDownPartial,EstimatePostage,MaxPostageBatch,CustomsSigner,DefaultWeight,FlatRatePriority,GlobalWeight,Created)';
        $query .= ' VALUES (';
        $query .= ':UserId,:OperatingSystem,:MaxWeight,:DeliveryConfirmation,:MinOrderTotalDelivery,:SignatureConfirmation,:ConsolidatorLabel,:IncludeInsurance,:MinOrderTotalInsurance,:RoundDownPartial,:EstimatePostage,:MaxPostageBatch,:CustomsSigner,:DefaultWeight,:FlatRatePriority,:GlobalWeight,:Created';
        $query .= ')';

        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            return false;
        }

        return true;
    }

    /*
    * find - Find Marketplace by marketplace record UserId and Status
    *
    * @param  UserId  - Table record Id of marketplace to find
    * @param  Status  - Table record Status of marketplace to find
    * @return associative array.
    */
    public function PostageSettingfindByUserId($UserId)
    {
        $stmt = $this->db->prepare('SELECT * FROM postagesettings WHERE UserId = :UserId');
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
