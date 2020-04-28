<?php
$title_meta = 'Product Add_2 for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Add_2 for your Tracksz Store, a Multiple Market Product Management Service';
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
                    <h1 class="page-title"> <?= _('Product Add_2') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can add, modify, and delete Product for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <form name="product_market_request" id="product_market_request" action="/product/place_market" method="POST" enctype="multipart/form-data" data-parsley-validate>
                <div class="page-section">
                    <!-- .page-section starts -->
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->

                            <div class="card-body">

                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('General') ?></h5>
                                            <div class="form-group">
                                                <label for="ProductNameInput"><?= _('Name') ?></label>
                                                <input type="text" class="form-control" id="ProductNameInput" name="ProductNameInput" placeholder="Enter Product Name" data-parsley-required-message="<?= _('Enter Product Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductNameInput']) && !empty($form['ProductNameInput'])) ? $form['ProductNameInput'] : ''; ?>">
                                                <input type="hidden" class="form-control" id="store_id" store_id="Id" value="<?php echo (isset($all_store['Id']) && !empty($all_store['Id'])) ? $all_store['Id'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductDescription"><?= _('Description') ?></label>
                                                <textarea class="form-control" id="ProductDescription" name="ProductDescription" rows="3"><?php echo (isset($form['ProductDescription']) && !empty($form['ProductDescription'])) ? $form['ProductDescription'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductTag"><?= _('Tag') ?></label>
                                                <input type="text" class="form-control" id="ProductTag" name="ProductTag" placeholder="Enter Tag" data-parsley-required-message="<?= _('Enter Tag') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductTag']) && !empty($form['ProductTag'])) ? $form['ProductTag'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="MetaTitle"><?= _('Meta Title') ?></label>
                                                <input type="text" class="form-control" id="MetaTitle" name="MetaTitle" placeholder="Enter Meta Title" data-parsley-required-message="<?= _('Enter Tag') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MetaTitle']) && !empty($form['MetaTitle'])) ? $form['MetaTitle'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductMetaDescription"><?= _('Meta Description') ?></label>
                                                <textarea class="form-control" id="ProductMetaDescription" name="ProductMetaDescription" rows="3"><?php echo (isset($form['ProductMetaDescription']) && !empty($form['ProductMetaDescription'])) ? $form['ProductMetaDescription'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductMetaKey"><?= _('Meta Key') ?></label>
                                                <textarea class="form-control" id="ProductMetaKey" name="ProductMetaKey" rows="3"><?php echo (isset($form['ProductMetaKey']) && !empty($form['ProductMetaKey'])) ? $form['ProductMetaKey'] : ''; ?></textarea>
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->


                            </div> <!-- Card Body -->



                        </div><!-- /.page-inner -->


                    </div><!-- /.page -->

                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->

                            <div class="card-body">

                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Data') ?></h5>
                                            <div class="form-group">
                                                <label for="ProductModel"><?= _('Model') ?></label>
                                                <input type="text" class="form-control" id="ProductModel" name="ProductModel" placeholder="Enter Product Model" data-parsley-required-message="<?= _('Enter Product Model') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductModel']) && !empty($form['ProductModel'])) ? $form['ProductModel'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductSKU"><?= _('SKU') ?></label>
                                                <input type="text" class="form-control" id="ProductSKU" name="ProductSKU" placeholder="Enter Product SKU" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductSKU']) && !empty($form['ProductSKU'])) ? $form['ProductSKU'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductUPC"><?= _('UPC') ?></label>
                                                <input type="text" class="form-control" id="ProductUPC" name="ProductUPC" placeholder="Enter Product UPC" data-parsley-required-message="<?= _('Enter Product UPC') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductUPC']) && !empty($form['ProductUPC'])) ? $form['ProductUPC'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductEAN"><?= _('EAN') ?></label>
                                                <input type="text" class="form-control" id="ProductEAN" name="ProductEAN" placeholder="Enter Product EAN" data-parsley-required-message="<?= _('Enter Product EAN') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductEAN']) && !empty($form['ProductEAN'])) ? $form['ProductEAN'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductJAN"><?= _('JAN') ?></label>
                                                <input type="text" class="form-control" id="ProductJAN" name="ProductJAN" placeholder="Enter Product JAN" data-parsley-required-message="<?= _('Enter Product JAN') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductJAN']) && !empty($form['ProductJAN'])) ? $form['ProductJAN'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductISBN"><?= _('ISBN') ?></label>
                                                <input type="text" class="form-control" id="ProductISBN" name="ProductISBN" placeholder="Enter Product ISBN" data-parsley-required-message="<?= _('Enter Product ISBN') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductISBN']) && !empty($form['ProductISBN'])) ? $form['ProductISBN'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="ProductLocation"><?= _('Location') ?></label>
                                                <input type="text" class="form-control" id="ProductLocation" name="ProductLocation" placeholder="Enter Location" data-parsley-required-message="<?= _('Enter Location') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductLocation']) && !empty($form['ProductLocation'])) ? $form['ProductLocation'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductQty"><?= _('Quantity') ?></label>
                                                <input type="number" class="form-control" id="ProductQty" name="ProductQty" placeholder="Enter Quantity" data-parsley-required-message="<?= _('Enter Quantity') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductQty']) && !empty($form['ProductQty'])) ? $form['ProductQty'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="d-block"><?= _('Stock Status') ?></label>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" class="custom-control-input" name="StockStatus" id="StockStatusActive" value="1"> <label class="custom-control-label" for="StockStatusActive"><?= _('In-Stock') ?></label>
                                                </div>
                                                <div class="custom-control custom-control-inline custom-radio">
                                                    <input type="radio" class="custom-control-input" name="StockStatus" id="StockStatusInActive" value="0" checked> <label class="custom-control-label" for="StockStatusInActive"><?= _('OutofStock') ?></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductMetaDescription"><?= _('Meta Description') ?></label>
                                                <textarea class="form-control" id="ProductMetaDescription" name="ProductMetaDescription" rows="3"><?php echo (isset($form['ProductMetaDescription']) && !empty($form['ProductMetaDescription'])) ? $form['ProductMetaDescription'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductMetaKey"><?= _('Meta Key') ?></label>
                                                <textarea class="form-control" id="ProductMetaKey" name="ProductMetaKey" rows="3"><?php echo (isset($form['ProductMetaKey']) && !empty($form['ProductMetaKey'])) ? $form['ProductMetaKey'] : ''; ?></textarea>
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->


                            </div> <!-- Card Body -->



                        </div><!-- /.page-inner -->


                    </div><!-- /.page -->
                </div><!-- /.wrapper -->
                <button type="submit" class="btn btn-primary"><?= _('Submit') ?></button>
            </form>
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