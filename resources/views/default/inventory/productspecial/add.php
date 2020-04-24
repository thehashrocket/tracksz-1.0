<?php
$title_meta = 'Product Special Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Special Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/productspecial/page" title="Inventory Product Special"><?= ('Product Special') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Product Special Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Product Special for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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

                            <form name="product_special_request" id="product_special_request" action="/productspecial/insert_discount" method="POST" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="SpecialProductId"><?= _('Product') ?></label>
                                                </div>
                                                <select class="custom-select" id="SpecialProductId" name="SpecialProductId">
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
                                                                            <option value=" <?php echo $prod_val['Id']; ?>" <?php echo (isset($form['SpecialProductId']) && $form['SpecialProductId'] == $prod_val['Id']) ? 'selected' : ''; ?>><?php echo strtoupper($prod_val['Name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Product found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="SpecialCustomerGroup"><?= _('Customer Group') ?></label>
                                                </div>
                                                <select class="custom-select" id="SpecialCustomerGroup" name="SpecialCustomerGroup">
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
                                                                            <option value=" <?php echo $cust_val['Id']; ?>" <?php echo (isset($form['SpecialCustomerGroup']) && $form['SpecialCustomerGroup'] == $cust_val['Id']) ? 'selected' : ''; ?>><?php echo strtoupper($cust_val['Name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Customer Group found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="SpecialPriority"><?= _('Priority') ?></label>
                                                <input type="number" class="form-control" id="SpecialPriority" name="SpecialPriority" placeholder="Enter Special Priority" data-parsley-required-message="<?= _('Enter Special Priority') ?>" data-parsley-group="fieldset01" required min="0" value="<?php echo (isset($form['SpecialPriority']) && !empty($form['SpecialPriority'])) ? $form['SpecialPriority'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="SpecialPrice"><?= _('Price') ?></label>
                                                <input type="number" class="form-control" id="SpecialPrice" name="SpecialPrice" placeholder="Enter Special Price" data-parsley-required-message="<?= _('Enter Special Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['SpecialPrice']) && !empty($form['SpecialPrice'])) ? $form['SpecialPrice'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="SpecialStartDate"><?= _('Start Date') ?></label> <input id="SpecialStartDate" name="SpecialStartDate" type="text" class="form-control flatpickr-input active" data-toggle="flatpickr" data-enable-time="true" data-date-format="d-m-Y H:i" readonly="readonly" value="<?php echo (isset($form['SpecialStartDate']) && !empty($form['SpecialStartDate'])) ? $form['SpecialStartDate'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label" for="SpecialEndDate"><?= _('End Date') ?></label> <input id="SpecialEndDate" name="SpecialEndDate" type="text" class="form-control flatpickr-input active" data-toggle="flatpickr" data-enable-time="true" data-date-format="d-m-Y H:i" readonly="readonly" value="<?php echo (isset($form['SpecialEndDate']) && !empty($form['SpecialEndDate'])) ? $form['SpecialEndDate'] : ''; ?>">
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