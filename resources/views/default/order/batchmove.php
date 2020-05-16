<?php
$title_meta = 'Order Batch Move for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Order Batch Move for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/order/browse" title="Orders"><?= ('Orders') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Batch Move') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Order Batch Move') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Order for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <h6 class="card-header"> <?= _('Orders') ?></h6>
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->

                            <form name="order_batch_move" id="order_batch_move" action="/order/update-batchmove" method="POST" enctype="multipart/form-data" data-parsley-validate="" novalidate="">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">

                                            <div class="form-group">
                                                <label class="form-check-label" for="ProductActive">
                                                    <?= _('List orders to move to a different status by entering one order number per line. You can add tracking numbers after an order number separated by a space or comma.') ?> </label>
                                            </div>
                                            <div class="form-group">
                                                <label for="OrderId"><?= _('Order Id') ?></label>
                                                <textarea class="form-control" id="OrderId" name="OrderId" rows="6" data-parsley-required-message="Enter Order ID" placeholder="Enter Order ID" data-parsley-group="fieldset01" required=""></textarea>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <div class="form-group">
                                                <label for="UpdateStatusOrder"><?= _('Update status to:') ?></label>
                                                <select class="form-control" id="UpdateStatusOrder" name="UpdateStatusOrder">
                                                    <option value="" selected disabled><?= _('Select Status') ?></option>
                                                    <option value="new"><?= _('New') ?></option>
                                                    <option value="in-process"><?= _('In Process') ?></option>
                                                    <option value="shipped"><?= _('Shipped') ?></option>
                                                    <option value="deferred"><?= _('Deferred') ?></option>
                                                    <option value="cancelled"><?= _('Cancelled') ?></option>
                                                    <option value="shipped-noemail"><?= _('Shipped - No Email') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="ShippingCarrierOrder"><?= _('Shipping Carrier:') ?></label>
                                                <select class="form-control" id="ShippingCarrierOrder" name="ShippingCarrierOrder">
                                                    <option value="" selected disabled><?= _('Select Carrier') ?></option>
                                                    <option value="FEDEX"><?= _('FEDEX') ?></option>
                                                    <option value="DHL"><?= _('DHL') ?></option>
                                                    <option value="DHLGM"><?= _('DHLGM') ?></option>
                                                    <option value="USPS"><?= _('USPS') ?></option>
                                                    <option value="UPS"><?= _('UPS') ?></option>
                                                    <option value="UPSMI"><?= _('UPSMI') ?></option>
                                                    <option value="MISC"><?= _('MISC') ?></option>
                                                    <option value="AUTO"><?= _('AUTO') ?></option>
                                                    <option value="Other"><?= _('Other') ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->
                                <button type="submit" class="btn btn-primary"><?= _('Update Status') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div> <!-- .card card-fluid ends -->
                </div> <!-- .card-deck-xl ends -->
            </div>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<!-- <script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>