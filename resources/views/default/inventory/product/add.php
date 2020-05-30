<?php
$title_meta = 'Product Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/inventory/browse" title="View Store's Orders"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?= _('Product Add') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can add, modify, and delete Product for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_request" id="product_market_request" action="/product/place_market" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Basic Information') ?></h5>
                                            <div class="form-group">
                                                <label for="ProductNameInput"><?= _('Product Name') ?></label>
                                                <input type="text" class="form-control" id="ProductNameInput" name="ProductNameInput" placeholder="Enter Product Name" data-parsley-required-message="<?= _('Enter Product Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductNameInput']) && !empty($form['ProductNameInput'])) ? $form['ProductNameInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductSKUInput"><?= _('Product SKU') ?></label>
                                                <input type="text" class="form-control" id="ProductSKUInput" name="ProductSKUInput" placeholder="Enter Product SKU" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductSKUInput']) && !empty($form['ProductSKUInput'])) ? $form['ProductSKUInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductIdInput"><?= _('Product ID') ?></label>
                                                <input type="text" class="form-control" id="ProductIdInput" name="ProductIdInput" placeholder="Enter Product ID" data-parsley-required-message="<?= _('Enter Product Id') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductIdInput']) && !empty($form['ProductIdInput'])) ? $form['ProductIdInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductBasePriceInput"><?= _('Base Price') ?></label>
                                                <input type="number" class="form-control" id="ProductBasePriceInput" name="ProductBasePriceInput" placeholder="Enter Product Base Price" value="<?php echo (isset($form['ProductBasePriceInput']) && !empty($form['ProductBasePriceInput'])) ? $form['ProductBasePriceInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="inputGroupSelectCondition"><?= _('Conditions') ?></label>
                                                    </div>
                                                    <select class="custom-select" id="ProductCondition" name="ProductCondition">
                                                        <option value="" selected><?= _('Choose...') ?></option>
                                                        <option value="Used- Very Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Very Good') ? 'selected' : ''; ?>>Used; Very Good</option>
                                                        <option value="Used- Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Good') ? 'selected' : ''; ?>>Used; Good</option>
                                                        <option value="Used- Not Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Not Good') ? 'selected' : ''; ?>>Used; Not Good</option>
                                                        <option value="New" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'New') ? 'selected' : ''; ?>>New</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProductActive" id="ProductActive" data-parsley-required-message="<?= _('Make Product Active') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProductActive']) && $form['ProductActive'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProductActive']) && $form['ProductActive'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProductActive">
                                                        <?= _('Make Product Active') ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProdInterShip" id="ProdInterShip" data-parsley-required-message="<?= _('International Shipping') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProdInterShip']) && $form['ProdInterShip'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProdInterShip']) && $form['ProdInterShip'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProdInterShip">
                                                        <?= _('International Shipping') ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProdExpectedShip" id="ProdExpectedShip" data-parsley-required-message="<?= _('Expected Shipping') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProdExpectedShip']) && $form['ProdExpectedShip'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProdExpectedShip']) && $form['ProdExpectedShip'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProdExpectedShip">
                                                        <?= _('Expected Shipping') ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="ProdTitleBayInput"><?= _('Override Title For eBay') ?></label>
                                                <input type="text" class="form-control" id="ProdTitleBayInput" name="ProdTitleBayInput" placeholder="Enter eBay Title" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProdTitleBayInput']) && !empty($form['ProdTitleBayInput'])) ? $form['ProdTitleBayInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProdQtyInput"><?= _('Product Qty') ?></label>
                                                <input type="number" class="form-control" id="ProdQtyInput" name="ProdQtyInput" placeholder="Enter Product Qty" data-parsley-required-message="<?= _('Enter Product QTY') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProdQtyInput']) && !empty($form['ProdQtyInput'])) ? $form['ProdQtyInput'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProdImage"><?= _('Product Image') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="ProdImage" name="ProdImage" multiple=""> <label class="custom-file-label" for="CategoryImage" data-parsley-required-message="<?= _('Select Inventory File Upload') ?>" data-parsley-group="fieldset01" required><?= _('Choose file') ?></label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <select name="CategoryName" id="CategoryName" class="browser-default custom-select category_select">
                                                    <option value="" selected><?= _('Select Category...') ?></option>
                                                    <?php
                                                    if (isset($all_category) && is_array($all_category) && !empty($all_category)) {
                                                        foreach ($all_category as $cat_key => $cat_val) { ?>
                                                            <option value="<?php echo $cat_val['Id']; ?>" <?php echo (isset($form['CategoryName']) && $form['CategoryName'] == $cat_val['Id']) ? 'selected' : ''; ?>><?php echo $cat_val['Name']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Category found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select">
                                                    <option selected><?= _('Select Marketplace...') ?></option>
                                                    <?php
                                                    if (isset($market_places) && !empty($market_places)) {
                                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val['Id']; ?>" <?php echo (isset($form['MarketName']) && $form['MarketName'] == $mar_val['Id']) ? 'selected' : ''; ?>><?php echo $mar_val['MarketName']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Marketplace found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->
                                    <div class="form-group">
                                        <label for="ProductNote"><?= _('Notes') ?></label>
                                        <textarea class="form-control" id="ProductNote" name="ProductNote" rows="3"><?php echo (isset($form['ProductNote']) && !empty($form['ProductNote'])) ? $form['ProductNote'] : ''; ?></textarea>
                                    </div>
                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary"><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- /.page -->


                <!-- .page-section Market prices starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_price" id="product_market_price" action="/product/market_price" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Market Specific Prices') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="AbeBooks"><?= _('AbeBooks') ?></label>
                                                <input type="number" class="form-control" id="AbeBooks" name="ProductNameInput" placeholder="Enter AbeBooks" data-parsley-group="fieldset01" value="<?php echo (isset($form['AbeBooks']) && !empty($form['AbeBooks'])) ? $form['AbeBooks'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Alibris"><?= _('Alibris') ?></label>
                                                <input type="number" class="form-control" id="Alibris" name="Alibris" placeholder="Enter Alibris" data-parsley-group="fieldset01" value="<?php echo (isset($form['Alibris']) && !empty($form['Alibris'])) ? $form['Alibris'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Amazon"><?= _('Amazon') ?></label>
                                                <input type="number" class="form-control" id="Amazon" name="Amazon" placeholder="Enter Amazon" data-parsley-group="fieldset01" value="<?php echo (isset($form['Amazon']) && !empty($form['Amazon'])) ? $form['Amazon'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonEurope"><?= _('AmazonEurope') ?></label>
                                                <input type="number" class="form-control" id="AmazonEurope" name="AmazonEurope" placeholder="Enter Amazon Europe" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="BarnesAndNoble"><?= _('Barnes And Noble') ?></label>
                                                <input type="number" class="form-control" id="BarnesAndNoble" name="BarnesAndNoble" placeholder="Enter Barnes And Noble" data-parsley-group="fieldset01" value="<?php echo (isset($form['BarnesAndNoble']) && !empty($form['BarnesAndNoble'])) ? $form['BarnesAndNoble'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Biblio"><?= _('Biblio') ?></label>
                                                <input type="number" class="form-control" id="Biblio" name="Biblio" placeholder="Enter Biblio" data-parsley-group="fieldset01" value="<?php echo (isset($form['Biblio']) && !empty($form['Biblio'])) ? $form['Biblio'] : ''; ?>">
                                            </div>
                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="Chrislands"><?= _('Chrislands') ?></label>
                                                <input type="number" class="form-control" id="Chrislands" name="Chrislands" placeholder="Enter Chrislands" data-parsley-group="fieldset01" value="<?php echo (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBay"><?= _('eBay') ?></label>
                                                <input type="number" class="form-control" id="eBay" name="eBay" placeholder="Enter eBay" data-parsley-group="fieldset01" value="<?php echo (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="eCampus"><?= _('eCampus') ?></label>
                                                <input type="number" class="form-control" id="eCampus" name="eCampus" placeholder="Enter eCampus" data-parsley-group="fieldset01" value="<?php echo (isset($form['eCampus']) && !empty($form['eCampus'])) ? $form['eCampus'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="TextbookRush"><?= _('TextbookRush') ?></label>
                                                <input type="number" class="form-control" id="TextbookRush" name="TextbookRush" placeholder="Enter TextbookRush" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookRush']) && !empty($form['TextbookRush'])) ? $form['TextbookRush'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="TextbookX"><?= _('TextbookX') ?></label>
                                                <input type="number" class="form-control" id="TextbookX" name="TextbookX" placeholder="Enter TextbookX" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookX']) && !empty($form['TextbookX'])) ? $form['TextbookX'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="Valore"><?= _('Valore') ?></label>
                                                <input type="number" class="form-control" id="Valore" name="Valore" placeholder="Enter Valore" data-parsley-group="fieldset01" value="<?php echo (isset($form['Valore']) && !empty($form['Valore'])) ? $form['Valore'] : ''; ?>">
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_marketprice" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- marketprices /.page -->


                <!-- .page-section shipping_templates starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_template" id="product_market_template" action="/product/market_template" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Shipping Templates') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="DefaultTemplate"><?= _('Default Template') ?></label>
                                                <input type="text" class="form-control" id="DefaultTemplate" name="DefaultTemplate" placeholder="Enter Default Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['DefaultTemplate']) && !empty($form['DefaultTemplate'])) ? $form['DefaultTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AbeBooksTemplate"><?= _('AbeBooks Template') ?></label>
                                                <input type="text" class="form-control" id="AbeBooksTemplate" name="AbeBooksTemplate" placeholder="Enter AbeBooks Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AbeBooksTemplate']) && !empty($form['AbeBooksTemplate'])) ? $form['AbeBooksTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AlibrisTemplate"><?= _('Alibris Template') ?></label>
                                                <input type="text" class="form-control" id="AlibrisTemplate" name="AlibrisTemplate" placeholder="Enter Alibris Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AlibrisTemplate']) && !empty($form['AlibrisTemplate'])) ? $form['AlibrisTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonTemplate"><?= _('Amazon Template') ?></label>
                                                <input type="text" class="form-control" id="AmazonTemplate" name="AmazonTemplate" placeholder="Enter Amazon Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonTemplate']) && !empty($form['AmazonTemplate'])) ? $form['AmazonTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonEuropeTemplate"><?= _('AmazonEurope Template') ?></label>
                                                <input type="text" class="form-control" id="AmazonEuropeTemplate" name="AmazonEuropeTemplate" placeholder="Enter Amazon Europe Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonEuropeTemplate']) && !empty($form['AmazonEuropeTemplate'])) ? $form['AmazonEuropeTemplate'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="BarnesAndNobleTemplate"><?= _('Barnes And Noble Template') ?></label>
                                                <input type="text" class="form-control" id="BarnesAndNobleTemplate" name="BarnesAndNobleTemplate" placeholder="Enter Barnes And Noble Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['BarnesAndNobleTemplate']) && !empty($form['BarnesAndNobleTemplate'])) ? $form['BarnesAndNobleTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="BiblioTemplate"><?= _('Biblio Template') ?></label>
                                                <input type="text" class="form-control" id="BiblioTemplate" name="BiblioTemplate" placeholder="Enter Biblio Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['BiblioTemplate']) && !empty($form['BiblioTemplate'])) ? $form['BiblioTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsTemplate"><?= _('Chrislands Template') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsTemplate" name="ChrislandsTemplate" placeholder="Enter Chrislands Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayTemplate"><?= _('eBay Template') ?></label>
                                                <input type="text" class="form-control" id="eBayTemplate" name="eBayTemplate" placeholder="Enter eBay Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="eCampusTemplate"><?= _('eCampus Template') ?></label>
                                                <input type="text" class="form-control" id="eCampusTemplate" name="eCampusTemplate" placeholder="Enter eCampus Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eCampusTemplate']) && !empty($form['eCampusTemplate'])) ? $form['eCampusTemplate'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="TextbookRushTemplate"><?= _('TextbookRush Template') ?></label>
                                                <input type="text" class="form-control" id="TextbookRushTemplate" name="TextbookRushTemplate" placeholder="Enter TextbookRush Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookRushTemplate']) && !empty($form['TextbookRushTemplate'])) ? $form['TextbookRushTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="TextbookXTemplate"><?= _('TextbookX Template') ?></label>
                                                <input type="text" class="form-control" id="TextbookXTemplate" name="TextbookXTemplate" placeholder="Enter TextbookX Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookXTemplate']) && !empty($form['TextbookXTemplate'])) ? $form['TextbookXTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ValoreTemplate"><?= _('Valore Template') ?></label>
                                                <input type="text" class="form-control" id="ValoreTemplate" name="ValoreTemplate" placeholder="Enter Valore Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ValoreTemplate']) && !empty($form['ValoreTemplate'])) ? $form['ValoreTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsTemplate"><?= _('Chrislands Template') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsTemplate" name="ChrislandsTemplate" placeholder="Enter Chrislands Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayTemplate"><?= _('eBay Template') ?></label>
                                                <input type="text" class="form-control" id="eBayTemplate" name="eBayTemplate" placeholder="Enter eBay Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_market_template" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- shipping_templates /.page -->
            </div><!-- /.wrapper -->
            <?= $this->stop() ?>

            <?php $this->start('plugin_js') ?>
            <script src="/assets/vendor/pace/pace.min.js"></script>
            <script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
            <script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
            <script src="/assets/javascript/pages/product.js"></script>
            <?= $this->stop() ?>

            <?php $this->start('page_js') ?>
            <?= $this->stop() ?>

            <?php $this->start('footer_extras') ?>
            <script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
            <?php $this->stop() ?>
