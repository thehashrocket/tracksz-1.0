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
