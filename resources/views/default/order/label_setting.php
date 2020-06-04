<?php
$title_meta = 'Order Defaults for Your Store Product Listings, a Multiple Market Order Management Service';
$description_meta = 'Order Defaults for your store\'s product listings at Tracksz, a Multiple Market Order Management Service';
?>
<?= $this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta]) ?>

<?= $this->start('page_content') ?>
<!-- .wrapper -->
<div class="wrapper">
    <!-- .page -->
    <div class="page">
        <!-- .page-inner -->
        <div class="page-inner">
            <!-- .page-title-bar -->
            <header class="page-title-bar">
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?= ('Dashboard') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/order/browse" title="Browse Store's Order"><?= ('Order') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Label Settings') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Label Settings') ?> </h1>
                <p class="text-muted"> <?= _('Configure default settings for your Active Store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <form name="order_market_request" id="label_setting" action="/order/add_update_label_setting" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->
                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title"><?= _('PDF Options') ?></h5>

                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="SkipPDFView" id="SkipPDFView" data-parsley-required-message="<?= _('Enter SkipPDFView') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($all_order['SkipPDFView']) && $all_order['SkipPDFView'] == 1) ? 1 : 0; ?>" <?php echo (isset($all_order['SkipPDFView']) && $all_order['SkipPDFView'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="SkipPDFView">
                                                        <?= _('Skip PDF View/Download page and perform the default action instead') ?>
                                                    </label>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <label for="Default Action"><?= _('Default Action') ?></label>
                                                <select name="DefaultAction" id="DefaultAction" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Default Action') ?></option>
                                                    <option value="View" <?php if (isset($all_order['DefaultAction']) && $all_order['DefaultAction'] == 'View') {
                                                                                echo 'selected';
                                                                            } ?>>View</option>
                                                    <option value="Download" <?php if (isset($all_order['DefaultAction']) && $all_order['DefaultAction'] == 'Download') {
                                                                                    echo 'selected';
                                                                                } ?>>Download</option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <label for="Sort Orders By (Mailing Labels and Packing Slips)"><?= _(' Sort Orders By (Mailing Labels and Packing Slips)') ?></label>
                                                <select name="SortOrders" id="SortOrders" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Sort Orders') ?></option>
                                                    <option value="Order#" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Order#') {
                                                                                echo 'selected';
                                                                            } ?>>Order#</option>
                                                    <option value="Location/SKU" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Location/SKU') {
                                                                                        echo 'selected';
                                                                                    } ?>>Location/SKU</option>
                                                    <option value="SKU" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'SKU') {
                                                                            echo 'selected';
                                                                        } ?>>SKU</option>
                                                    <option value="Zip Code" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Zip Code') {
                                                                                    echo 'selected';
                                                                                } ?>>Zip Code</option>
                                                    <option value="Country/Zip" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Country/Zip') {
                                                                                    echo 'selected';
                                                                                } ?>>Country/Zip</option>
                                                    <option value="Author" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Author') {
                                                                                echo 'selected';
                                                                            } ?>>Author</option>
                                                    <option value="Title" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Title') {
                                                                                echo 'selected';
                                                                            } ?>>Title</option>
                                                    <option value="Marketplace/Order#" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Marketplace/Order#') {
                                                                                            echo 'selected';
                                                                                        } ?>>Marketplace/Order#</option>
                                                    <option value="Marketplace/Location" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Marketplace/Location') {
                                                                                                echo 'selected';
                                                                                            } ?>>Marketplace/Location</option>
                                                    <option value="Shipping Method" <?php if (isset($all_order['SortOrders']) && $all_order['SortOrders'] == 'Shipping Method') {
                                                                                        echo 'selected';
                                                                                    } ?>>Shipping Method</option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->

                    </div> <!-- .card-deck-xl ends -->


                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->
                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title"><?= _('Pick List & Barcode') ?></h5>
                                            <hr>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm">
                                            <!-- form starts -->

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="SplitOrders" id="SplitOrders" data-parsley-required-message="<?= _('Enter SplitOrders') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($all_order['SplitOrders']) && $all_order['SplitOrders'] == 1) ? 1 : 0; ?>" <?php echo (isset($all_order['SplitOrders']) && $all_order['SplitOrders'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="SplitOrders">
                                                        <?= _('Split orders') ?>
                                                    </label>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="AddBarcode" id="AddBarcode" data-parsley-required-message="<?= _('Enter AddBarcode') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['AddBarcode']) && $all_order['AddBarcode'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['AddBarcode']) && $all_order['AddBarcode'] == 1) ? 1 : 0; ?>">
                                                    <label class="form-check-label" for="AddBarcode">
                                                        <?= _('Add barcode to pick list') ?>
                                                    </label>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Barcode Type"><?= _('Barcode Type') ?></label>
                                                <select name="BarcodeType" id="BarcodeType" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Barcode Type') ?></option>
                                                    <option value="Code128" <?php if (isset($all_order['BarcodeType']) && $all_order['BarcodeType'] == 'Code128') {
                                                                                echo 'selected';
                                                                            } ?>>Code 128</option>
                                                    <option value="Code39" <?php if (isset($all_order['BarcodeType']) && $all_order['BarcodeType'] == 'Code39') {
                                                                                echo 'selected';
                                                                            } ?>>Code 39</option>
                                                    <option value="ITF" <?php if (isset($all_order['BarcodeType']) && $all_order['BarcodeType'] == 'ITF') {
                                                                            echo 'selected';
                                                                        } ?>>ITF</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Sort Pick List By"><?= _('Sort Pick List By') ?></label>
                                                <select name="SortPickList" id="SortPickList" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Pick List') ?></option>
                                                    <option value="Location/SKU" <?php if (isset($all_order['SortPickList']) && $all_order['SortPickList'] == 'Location/SKU') {
                                                                                        echo 'selected';
                                                                                    } ?>>Location/SKU</option>
                                                    <option value="Author" <?php if (isset($all_order['SortPickList']) && $all_order['SortPickList'] == 'Author') {
                                                                                echo 'selected';
                                                                            } ?>>Author</option>
                                                    <option value="Title" <?php if (isset($all_order['SortPickList']) && $all_order['SortPickList'] == 'Title') {
                                                                                echo 'selected';
                                                                            } ?>>Title</option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <style>
                                                /* .myDiv {
                                                    display: none;
                                                    text-align: center;
                                                }

                                                .myDiv img {
                                                    margin: 0 auto;
                                                }

                                                .myDiv span {
                                                    text-align: center;
                                                    background: #ffdede;
                                                    padding: 6px 10px;
                                                    display: block;
                                                    width: 100px;
                                                    border: 1px solid #d47c7c;
                                                    margin: 8px auto;
                                                } */
                                            </style>
                                            <div class="form-group">
                                                <?php if (isset($all_order['BarcodeType']) == 'Code128') { ?>
                                                    <div id="Code128" class="myDiv" style="display: block;">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/Code128.png'; ?>" alt="Manager" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="Code128" class="myDiv">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/Code128.png'; ?>" alt="Manager" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php }
                                                ?>

                                                <?php if (isset($all_order['BarcodeType']) == 'Code39') { ?>
                                                    <div id="Code39" class="myDiv" style="display: block;">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/code39.png'; ?>" alt="HR" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="Code39" class="myDiv">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/code39.png'; ?>" alt="HR" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php }
                                                ?>
                                                <?php if (isset($all_order['BarcodeType']) == 'ITF') { ?>
                                                    <div id="ITF" class="myDiv" style="display: block;">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/itf.png'; ?>" alt="Developer" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="ITF" class="myDiv">
                                                        <img src="<?php echo \App\Library\Config::get('company_url') . '/assets/images/itf.png'; ?>" alt="Developer" class="img-responsive img-thumbnail" />
                                                    </div>
                                                <?php }
                                                ?>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->

                    </div> <!-- .card-deck-xl ends -->


                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->
                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title"><?= _('Paking Slips') ?></h5>
                                            <hr>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="Default Template"><?= _('Default Template') ?></label>
                                                <select name="DefaultTemplate" id="DefaultTemplate" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Default Template') ?></option>
                                                    <option value="Full" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == 'Full') {
                                                                                echo 'selected';
                                                                            } ?>>Full</option>
                                                    <option value="Small" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == 'Small') {
                                                                                echo 'selected';
                                                                            } ?>>Small</option>
                                                    <option value="Self-Stick Labels" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == 'Self-Stick Labels') {
                                                                                            echo 'selected';
                                                                                        } ?>>Self-Stick Labels</option>
                                                    <option value="92mm Fold" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == '92mm Fold') {
                                                                                    echo 'selected';
                                                                                } ?>>92mm Fold</option>
                                                    <option value="Mailing Slip" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == 'Mailing Slip') {
                                                                                        echo 'selected';
                                                                                    } ?>>Mailing Slip</option>
                                                    <option value="Integrated Label" <?php if (isset($all_order['DefaultTemplate']) && $all_order['DefaultTemplate'] == 'Integrated Label') {
                                                                                            echo 'selected';
                                                                                        } ?>>Integrated Label</option>
                                                </select>
                                            </div>
                                            <b><label style="color: #f8ac59;">Note: The recommended image resolution for the header and footer is 1024px X 120px</label></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <label for="Header Image URL"><?= _('Header Image URL') ?></label>
                                                <input type="text" class="form-control" id="HeaderImageURL" name="HeaderImageURL" placeholder="Header Image URL" data-parsley-required-message="<?= _('Header Image URL') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['HeaderImageURL']) && !empty($all_order['HeaderImageURL'])) ? $all_order['HeaderImageURL'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Packing slip header text"><?= _('Packing slip header text') ?></label>
                                                <textarea class="form-control" id="PackingSlipHeader" name="PackingSlipHeader" rows="3" data-parsley-required-message="<?= _('Enter Packing slip header text') ?>" placeholder="Enter Packing slip header text" data-parsley-group="fieldset01" required><?php echo (isset($all_order['PackingSlipHeader']) && !empty($all_order['PackingSlipHeader'])) ? $all_order['PackingSlipHeader'] : ''; ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="IncludeOrderBarcodes" id="IncludeOrderBarcodes" data-parsley-required-message="<?= _('Enter IncludeOrderBarcodes') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludeOrderBarcodes']) && $all_order['IncludeOrderBarcodes'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludeOrderBarcodes']) && $all_order['IncludeOrderBarcodes'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="IncludeOrderBarcodes">
                                                                <?= _('Include order barcodes') ?>
                                                            </label>

                                                        </div>

                                                    </div>
                                                    <div class="form-group">

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="IncludeItemBarcodes" id="IncludeItemBarcodes" data-parsley-required-message="<?= _('Enter IncludeItemBarcodes') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludeItemBarcodes']) && $all_order['IncludeItemBarcodes'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludeItemBarcodes']) && $all_order['IncludeItemBarcodes'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="IncludeItemBarcodes">
                                                                <?= _('Include item barcodes') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="CentreHeaderText" id="CentreHeaderText" data-parsley-required-message="<?= _('Enter CentreHeaderText') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['CentreHeaderText']) && $all_order['CentreHeaderText'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['CentreHeaderText']) && $all_order['CentreHeaderText'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="CentreHeaderText">
                                                                <?= _('Centre Header Text') ?>
                                                            </label>

                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="HideEmail" id="HideEmail" data-parsley-required-message="<?= _('Enter HideEmail') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['HideEmail']) && $all_order['HideEmail'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['HideEmail']) && $all_order['HideEmail'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="HideEmail">
                                                                <?= _('Hide e-mail address') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="HidePhone" id="HidePhone" data-parsley-required-message="<?= _('Enter HidePhone') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['HidePhone']) && $all_order['HidePhone'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['HidePhone']) && $all_order['HidePhone'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="HidePhone">
                                                                <?= _('Hide phone number') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="IncludeGSTExAus1" id="IncludeGSTExAus1" data-parsley-required-message="<?= _('Enter IncludeGSTExAus1') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludeGSTExAus1']) && $all_order['IncludeGSTExAus1'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludeGSTExAus1']) && $all_order['IncludeGSTExAus1'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="IncludeGSTExAus1">
                                                                <?= _('Include GST Exemptions - Australia') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="CentreFooter" id="CentreFooter" data-parsley-required-message="<?= _('Enter CentreFooter') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['CentreFooter']) && $all_order['CentreFooter'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['CentreFooter']) && $all_order['CentreFooter'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="CentreFooter">
                                                                <?= _('Centre footer text') ?>
                                                            </label>

                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="ShowItemPrice" id="ShowItemPrice" data-parsley-required-message="<?= _('Enter ShowItemPrice') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['ShowItemPrice']) && $all_order['ShowItemPrice'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['ShowItemPrice']) && $all_order['ShowItemPrice'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="ShowItemPrice">
                                                                <?= _('Show item price') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="IncludeMarketplaceOrder" id="IncludeMarketplaceOrder" data-parsley-required-message="<?= _('Enter IncludeMarketplaceOrder') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludeMarketplaceOrder']) && $all_order['IncludeMarketplaceOrder'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludeMarketplaceOrder']) && $all_order['IncludeMarketplaceOrder'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="IncludeMarketplaceOrder">
                                                                <?= _('Include marketplace order barcode (Full template only)') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="IncludePageNumbers" id="IncludePageNumbers" data-parsley-required-message="<?= _('Enter IncludePageNumbers') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludePageNumbers']) && $all_order['IncludePageNumbers'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludePageNumbers']) && $all_order['IncludePageNumbers'] == 1) ? 1 : 0; ?>">
                                                            <label class="form-check-label" for="IncludePageNumbers">
                                                                <?= _('Include page numbers') ?>
                                                            </label>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <label for="Footer Image URL"><?= _('Footer Image URL') ?></label>
                                                <input type="text" class="form-control" id="FooterImageURL" name="FooterImageURL" placeholder="Footer Image URL" data-parsley-required-message="<?= _('Footer Image URL') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['FooterImageURL']) && !empty($all_order['FooterImageURL'])) ? $all_order['FooterImageURL'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Packing slip footer text"><?= _('Packing slip footer text') ?></label>
                                                <textarea class="form-control" id="PackingSlipFooter" name="PackingSlipFooter" rows="3" data-parsley-required-message="<?= _('Enter Packing slip footer text') ?>" placeholder="Enter Packing slip footer text" data-parsley-group="fieldset01" required><?php echo (isset($all_order['PackingSlipFooter']) && !empty($all_order['PackingSlipFooter'])) ? $all_order['PackingSlipFooter'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="Packing slip From address"><?= _('Packing slip "From" address') ?></label>
                                                <textarea class="form-control" id="PackingSlipFrom" name="PackingSlipFrom" rows="3" data-parsley-required-message="<?= _('Enter Packing slip from address') ?>" placeholder="Enter Packing slip from address" data-parsley-group="fieldset01" required><?php echo (isset($all_order['PackingSlipFrom']) && !empty($all_order['PackingSlipFrom'])) ? $all_order['PackingSlipFrom'] : ''; ?></textarea>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->

                    </div> <!-- .card-deck-xl ends -->



                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->
                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="card-title"><?= _('Mailing Labels') ?></h5>
                                            <hr>
                                            <label style="color:#f8ac59;">Note: Mailing labels are intended to be printed on US Letter sized paper (8.5 in x 11 in; 21.59 cm x 27.94 cm)</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <label for="Columns per page"><?= _('Columns per page') ?></label>
                                                <input type="text" class="form-control" id="ColumnsPerPage" name="ColumnsPerPage" placeholder="Columns per page" data-parsley-required-message="<?= _('Columns per page') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['ColumnsPerPage']) && !empty($all_order['ColumnsPerPage'])) ? $all_order['ColumnsPerPage'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Rows per page"><?= _('Rows per page') ?></label>
                                                <input type="text" class="form-control" id="RowsPerPage" name="RowsPerPage" placeholder="Rows per page" data-parsley-required-message="<?= _('Rows per page') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['RowsPerPage']) && !empty($all_order['RowsPerPage'])) ? $all_order['RowsPerPage'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Font size"><?= _('Font size') ?></label>
                                                <select name="FontSize" id="FontSize" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Font Size') ?></option>
                                                    <option value="xx-small" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'xx-small') {
                                                                                    echo 'selected';
                                                                                } ?>>xx-small</option>
                                                    <option value="x-small" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'x-small') {
                                                                                echo 'selected';
                                                                            } ?>>x-small</option>
                                                    <option value="small" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'small') {
                                                                                echo 'selected';
                                                                            } ?>>small</option>
                                                    <option value="medium" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'medium') {
                                                                                echo 'selected';
                                                                            } ?>>medium</option>
                                                    <option value="large" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'large') {
                                                                                echo 'selected';
                                                                            } ?>>large</option>
                                                    <option value="x-large" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'x-large') {
                                                                                echo 'selected';
                                                                            } ?>>x-large</option>
                                                    <option value="xx-large" <?php if (isset($all_order['FontSize']) && $all_order['FontSize'] == 'xx-large') {
                                                                                    echo 'selected';
                                                                                } ?>>xx-large</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="HideLabelBoundaries" id="HideLabelBoundaries" data-parsley-required-message="<?= _('Enter HideLabelBoundaries') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['HideLabelBoundaries']) && $all_order['HideLabelBoundaries'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['HideLabelBoundaries']) && $all_order['HideLabelBoundaries'] == 1) ? 1 : 0; ?>">
                                                    <label class="form-check-label" for="HideLabelBoundaries">
                                                        <?= _('Hide label boundaries') ?>
                                                    </label>

                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="IncludeGSTExAus2" id="IncludeGSTExAus2" data-parsley-required-message="<?= _('Enter IncludeGSTExAus2') ?>" data-parsley-group="fieldset01" <?php echo (isset($all_order['IncludeGSTExAus2']) && $all_order['IncludeGSTExAus2'] == 1) ? 'checked' : ''; ?> value="<?php echo (isset($all_order['IncludeGSTExAus2']) && $all_order['IncludeGSTExAus2'] == 1) ? 1 : 0; ?>">
                                                    <label class="form-check-label" for="IncludeGSTExAus2">
                                                        <?= _('Include GST Exemptions - Australia') ?>
                                                    </label>

                                                </div>


                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for=" Label width"><?= _(' Label width') ?></label>
                                                <div class="input-group mb-3">

                                                    <input type="number" class="form-control" id="LabelWidth" name="LabelWidth" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['LabelWidth']) && !empty($all_order['LabelWidth'])) ? $all_order['LabelWidth'] : ''; ?>" style="width: 255px;">
                                                    <div class="input-group-prepend">
                                                        <select name="LabelWidthIn" id="LabelWidthIn" class="browser-default custom-select order_carrier_select">
                                                            <option value="" selected disabled><?= _('Select Label Width') ?></option>
                                                            <option value="cm" <?php if (isset($all_order['LabelWidthIn']) && $all_order['LabelWidthIn'] == 'cm') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('cm') ?></option>
                                                            <option value="in" <?php if (isset($all_order['LabelWidthIn']) && $all_order['LabelWidthIn'] == 'in') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('in') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=" Label height"><?= _(' Label height') ?></label>
                                                <div class="input-group mb-3">

                                                    <input type="number" class="form-control" id="LabelHeight" name="LabelHeight" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['LabelHeight']) && !empty($all_order['LabelHeight'])) ? $all_order['LabelHeight'] : ''; ?>" style="width: 255px;">
                                                    <div class="input-group-prepend">
                                                        <select name="LabelHeightIn" id="LabelHeightIn" class="browser-default custom-select order_carrier_select">
                                                            <option value="" selected disabled><?= _('Select Label Height') ?></option>
                                                            <option value="cm" <?php if (isset($all_order['LabelHeightIn']) && $all_order['LabelHeightIn'] == 'cm') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('cm') ?></option>
                                                            <option value="in" <?php if (isset($all_order['LabelHeightIn']) && $all_order['LabelHeightIn'] == 'in') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('in') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm-4">

                                            <div class="form-group">
                                                <label for=" Page margins"><?= _(' Page margins') ?></label>
                                                <div class="input-group mb-3">

                                                    <input type="number" class="form-control" id="PageMargins" name="PageMargins" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['PageMargins']) && !empty($all_order['PageMargins'])) ? $all_order['PageMargins'] : ''; ?>" style="width: 255px;">
                                                    <div class="input-group-prepend">
                                                        <select name="PageMarginsIn" id="PageMarginsIn" class="browser-default custom-select order_carrier_select">
                                                            <option value="" selected disabled><?= _('Select Page Margin') ?></option>
                                                            <option value="cm" <?php if (isset($all_order['PageMarginsIn']) && $all_order['PageMarginsIn'] == 'cm') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('cm') ?></option>
                                                            <option value="in" <?php if (isset($all_order['PageMarginsIn']) && $all_order['PageMarginsIn'] == 'in') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('in') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for=" Label margins"><?= _(' Label margins') ?></label>
                                                <div class="input-group mb-3">

                                                    <input type="number" class="form-control" id="LabelMargins" name="LabelMargins" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['LabelMargins']) && !empty($all_order['LabelMargins'])) ? $all_order['LabelMargins'] : ''; ?>">
                                                    <div class=" input-group-prepend">
                                                        <select name="LabelMarginsIn" id="LabelMarginsIn" class="browser-default custom-select order_carrier_select">
                                                            <option value="" selected disabled><?= _('Select Label Margin') ?></option>
                                                            <option value="cm" <?php if (isset($all_order['LabelMarginsIn']) && $all_order['LabelMarginsIn'] == 'cm') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('cm') ?></option>
                                                            <option value="in" <?php if (isset($all_order['LabelMarginsIn']) && $all_order['LabelMarginsIn'] == 'in') {
                                                                                    echo 'selected';
                                                                                } ?>><?= _('in') ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->

                    </div> <!-- .card-deck-xl ends -->


                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div> <!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->

<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/javascript/pages/label-setting.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?= $this->stop() ?>
