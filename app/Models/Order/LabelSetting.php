<?php

declare(strict_types=1);

namespace App\Models\Order;

use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use PDO;

class LabelSetting
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
    public function editLabelSettings($form)
    {

        $query  = 'UPDATE labelsettings SET ';
        $query .= 'SkipPDFView = :SkipPDFView, ';
        $query .= 'DefaultAction = :DefaultAction, ';
        $query .= 'SortOrders = :SortOrders, ';
        $query .= 'SplitOrders = :SplitOrders, ';
        $query .= 'AddBarcode = :AddBarcode, ';
        $query .= 'BarcodeType = :BarcodeType, ';
        $query .= 'SortPickList = :SortPickList, ';
        $query .= 'DefaultTemplate = :DefaultTemplate, ';
        $query .= 'HeaderImageURL  = :HeaderImageURL, ';
        $query .= 'FooterImageURL = :FooterImageURL, ';
        $query .= 'PackingSlipHeader  = :PackingSlipHeader, ';
        $query .= 'PackingSlipFooter = :PackingSlipFooter, ';
        $query .= 'IncludeOrderBarcodes = :IncludeOrderBarcodes, ';
        $query .= 'IncludeItemBarcodes = :IncludeItemBarcodes, ';
        $query .= 'CentreHeaderText = :CentreHeaderText, ';
        $query .= 'HideEmail = :HideEmail, ';
        $query .= 'HidePhone = :HidePhone, ';
        $query .= 'IncludeGSTExAus1  = :IncludeGSTExAus1, ';
        $query .= 'CentreFooter = :CentreFooter, ';
        $query .= 'ShowItemPrice = :ShowItemPrice, ';
        $query .= 'IncludeMarketplaceOrder = :IncludeMarketplaceOrder, ';
        $query .= 'IncludePageNumbers = :IncludePageNumbers, ';
        $query .= 'PackingSlipFrom = :PackingSlipFrom, ';
        $query .= 'ColumnsPerPage  = :ColumnsPerPage, ';
        $query .= 'RowsPerPage = :RowsPerPage, ';
        $query .= 'FontSize = :FontSize, ';
        $query .= 'HideLabelBoundaries = :HideLabelBoundaries, ';
        $query .= 'IncludeGSTExAus2 = :IncludeGSTExAus2, ';
        $query .= 'LabelWidth = :LabelWidth, ';
        $query .= 'LabelWidthIn = :LabelWidthIn, ';
        $query .= 'LabelHeight = :LabelHeight, ';
        $query .= 'LabelHeightIn = :LabelHeightIn, ';
        $query .= 'PageMargins = :PageMargins, ';
        $query .= 'PageMarginsIn = :PageMarginsIn, ';
        $query .= 'LabelMargins = :LabelMargins, ';
        $query .= 'LabelMarginsIn = :LabelMarginsIn, ';
        $query .= 'Updated = :Updated ';
        $query .= 'WHERE UserId = :UserId ';
        $stmt = $this->db->prepare($query);
        if (!$stmt->execute($form)) {
            echo 'asdasdasd'; exit;
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
    public function addLabelSettings($form = array())
    {
        $query  = 'INSERT INTO labelsettings (UserId,SkipPDFView,DefaultAction,SortOrders,SplitOrders,AddBarcode,BarcodeType,SortPickList,DefaultTemplate,HeaderImageURL,FooterImageURL,PackingSlipHeader,PackingSlipFooter,IncludeOrderBarcodes,IncludeItemBarcodes,CentreHeaderText,HideEmail,HidePhone,IncludeGSTExAus1,CentreFooter,ShowItemPrice,IncludeMarketplaceOrder,IncludePageNumbers,PackingSlipFrom,ColumnsPerPage,RowsPerPage,FontSize,HideLabelBoundaries,IncludeGSTExAus2,LabelWidth,LabelWidthIn,LabelHeight,LabelHeightIn,PageMargins,PageMarginsIn,LabelMargins,LabelMarginsIn,Created)';
        $query .= ' VALUES (';
        $query .= ':UserId,:SkipPDFView,:DefaultAction,:SortOrders,:SplitOrders,:AddBarcode,:BarcodeType,:SortPickList,:DefaultTemplate,:HeaderImageURL,:FooterImageURL,:PackingSlipHeader,:PackingSlipFooter,:IncludeOrderBarcodes,:IncludeItemBarcodes,:CentreHeaderText,:HideEmail,:HidePhone,:IncludeGSTExAus1,:CentreFooter,:ShowItemPrice,:IncludeMarketplaceOrder,:IncludePageNumbers,:PackingSlipFrom,:ColumnsPerPage,:RowsPerPage,:FontSize,:HideLabelBoundaries,:IncludeGSTExAus2,:LabelWidth,:LabelWidthIn,:LabelHeight,:LabelHeightIn,:PageMargins,:PageMarginsIn,:LabelMargins,:LabelMarginsIn,:Created';
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
    public function LabelSettingfindByUserId($UserId)
    {
        $stmt = $this->db->prepare('SELECT * FROM labelsettings WHERE UserId = :UserId');
        $stmt->execute(['UserId' => $UserId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
