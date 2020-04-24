<?php
$title_meta = 'Product Discount Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Discount Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/inventory/browse" title="Inventory Listings"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/productdiscount/page" title="Inventory Product Discount"><?= ('Product Discount') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Product Discount Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Product Discount for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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
                            <!-- .card-body starts -->

                            <form name="product_discount_request" id="product_discount_request" action="/productdiscount/insert_discount" method="POST" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="DiscountProductId"><?= _('Product') ?></label>
                                                </div>
                                                <select class="custom-select" id="DiscountProductId" name="DiscountProductId">
                                                    <option value="" selected><?= _('Select Product...') ?></option>
                                                    <?php
                                                    if (isset($product_list) && !empty($product_list)) {
                                                        foreach ($product_list as $prod_key => $prod_val) {
                                                    ?>
                                                            <div class="page-section">
                                                                <!-- .page-section starts -->
                                                                <div class="card-deck-xl">
                                                                    <!-- .card-deck-xl starts -->
                                                                    <div class="card card-fluid">
                                                                        <!-- .card card-fluid starts -->
                                                                        <div class="card-body">
                                                                            <!-- .card-body starts -->
                                                                            <option value=" <?php echo $prod_val['Id']; ?>" <?php echo (isset($form['DiscountProductId']) && $form['DiscountProductId'] == $prod_val['Id']) ? 'selected' : ''; ?>><?php echo strtoupper($prod_val['Name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Product found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="DiscountCustomerGroup"><?= _('Customer Group') ?></label>
                                                </div>
                                                <select class="custom-select" id="DiscountCustomerGroup" name="DiscountCustomerGroup">
                                                    <option value="" selected><?= _('Select Customer Group...') ?></option>
                                                    <?php
                                                    if (isset($customergroup_list) && !empty($customergroup_list)) {
                                                        foreach ($customergroup_list as $cust_key => $cust_val) {
                                                    ?>
                                                            <div class="page-section">
                                                                <!-- .page-section starts -->
                                                                <div class="card-deck-xl">
                                                                    <!-- .card-deck-xl starts -->
                                                                    <div class="card card-fluid">
                                                                        <!-- .card card-fluid starts -->
                                                                        <div class="card-body">
                                                                            <!-- .card-body starts -->
                                                                            <option value=" <?php echo $cust_val['Id']; ?>" <?php echo (isset($form['DiscountCustomerGroup']) && $form['DiscountCustomerGroup'] == $cust_val['Id']) ? 'selected' : ''; ?>><?php echo strtoupper($cust_val['Name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Customer Group found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="DiscountQuantity"><?= _('Quantity') ?></label>
                                                <input type="number" class="form-control" id="DiscountQuantity" name="DiscountQuantity" placeholder="Enter Discount Quantity" data-parsley-required-message="<?= _('Enter Discount Quantity') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['DiscountQuantity']) && !empty($form['DiscountQuantity'])) ? $form['DiscountQuantity'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="DiscountPriority"><?= _('Priority') ?></label>
                                                <input type="number" class="form-control" id="DiscountPriority" name="DiscountPriority" placeholder="Enter Discount Priority" data-parsley-required-message="<?= _('Enter Discount Priority') ?>" data-parsley-group="fieldset01" required min="0" value="<?php echo (isset($form['DiscountPriority']) && !empty($form['DiscountPriority'])) ? $form['DiscountPriority'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="DiscountPrice"><?= _('Price') ?></label>
                                                <input type="number" class="form-control" id="DiscountPrice" name="DiscountPrice" placeholder="Enter Discount Price" data-parsley-required-message="<?= _('Enter Discount Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['DiscountPrice']) && !empty($form['DiscountPrice'])) ? $form['DiscountPrice'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="DiscountStartDate"><?= _('Start Date') ?></label> <input id="DiscountStartDate" name="DiscountStartDate" type="text" class="form-control flatpickr-input active" data-toggle="flatpickr" data-enable-time="true" data-date-format="d-m-Y H:i" readonly="readonly" value="<?php echo (isset($form['DiscountStartDate']) && !empty($form['DiscountStartDate'])) ? $form['DiscountStartDate'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="DiscountEndDate"><?= _('End Date') ?></label> <input id="DiscountEndDate" name="DiscountEndDate" type="text" class="form-control flatpickr-input active" data-toggle="flatpickr" data-enable-time="true" data-date-format="d-m-Y H:i" readonly="readonly" value="<?php echo (isset($form['DiscountEndDate']) && !empty($form['DiscountEndDate'])) ? $form['DiscountEndDate'] : ''; ?>">
                                            </div>



                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">

                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div> <!-- Card Body -->

                    </div> <!-- .card card-fluid ends -->
                </div> <!-- .card-deck-xl ends -->
            </div> <!-- .page-section ends -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>