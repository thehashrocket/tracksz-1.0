<?php
$title_meta = 'Category Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Category Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/inventory/view" title="Inventory Listings"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <?= ('Order Setting') ?>
                            </li>
                            
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Order Setting') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Category for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <h6 class="card-header"> <?= _('Email Templates') ?></h6>
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->

                            <form name="order_setting" id="order_setting" action="/order/order_update_settings" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">

                                           <div class="form-group">
                                            <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ProductActive" id="ProductActive" data-parsley-required-message="<?= _('Do not send me a copy of any confirmation,cancellation or deferal emails') ?>" data-parsley-group="fieldset01" value="">
                                                    <label class="form-check-label" for="ProductActive">
                                                        <?= _('Do not send me a copy of any confirmation,cancellation or deferal emails') ?>
                                                    </label>
                                           
                                               </div>
                                           </div>
                                            <div class="form-group">
                                                <label for="ConfirmationMail">Confirmation Mail</label>
                                                <textarea class="form-control" id="ConfirmEmail" name="ConfirmEmail" rows="3" data-parsley-required-message="<?= _('Enter Confirm Email') ?>" placeholder="Enter Confirm Email" data-parsley-group="fieldset01" required><?php echo (isset($order_details['ConfirmEmail']) && !empty($order_details['ConfirmEmail'])) ? $order_details['ConfirmEmail'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="CancellationMail">Cancellation Mail</label>
                                                <textarea class="form-control" id="CancelEmail" name="CancelEmail" rows="3" data-parsley-required-message="<?= _('Enter Cancel Email') ?>" placeholder="Enter Cancel Email" data-parsley-group="fieldset01" required><?php echo (isset($order_details['CancelEmail']) && !empty($order_details['CancelEmail'])) ? $order_details['CancelEmail'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                        <label for="DeferMail">Defer(Backorder) Mail</label>
                                                <textarea class="form-control" id="DeferEmail" name="DeferEmail" rows="3" data-parsley-required-message="<?= _('Enter Defer Email') ?>" placeholder="Enter Defer Email" data-parsley-group="fieldset01" required><?php echo (isset($order_details['DeferEmail']) && !empty($order_details['DeferEmail'])) ? $order_details['DeferEmail'] : ''; ?></textarea>
                                            </div>
                                           
                                             <h6 class="card-header"> <?= _('Order Folders') ?></h6>
                                            <br>
                                             <div class="form-group">
                                                <label for="NumberOfAdditionalOrderFolders">Number Of Additional Order Folders</label>
                                                <select class="form-control" id="" name="">
                                                    <option>Please select</option>
                                                    <option>0</option>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                </select>
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
