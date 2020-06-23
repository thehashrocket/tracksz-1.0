<?php
$title_meta = 'Order Settings for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Order Settings for your Tracksz Store, a Multiple Market Product Management Service';
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
                                                    <input class="form-check-input" type="checkbox" name="DontSendCopy" id="DontSendCopy" data-parsley-required-message="<?= _('Enter DontSendCopy') ?>" data-parsley-group="fieldset01" <?php echo (isset($order_details['DontSendCopy']) && $order_details['DontSendCopy'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="DontSendCopy">
                                                        <?= _('Do not send me a copy of any confirmation, cancellation or deferal emails') ?>
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
                                            <?php
                                            $order_data = "";
                                            if (isset($order_details['NoAdditionalOrder']) && !empty($order_details['NoAdditionalOrder'])) {
                                                $order_data = json_decode($order_details['NoAdditionalOrder']);
                                            }
                                            ?>
                                            <div class="form-group">
                                                <label for="NumberOfAdditionalOrderFolders">Number Of Additional Order Folders</label>
                                                <select class="form-control" id="NoAdditionalOrder" name="NoAdditionalOrder">
                                                    <option value="" disabled>Please select</option>
                                                    <option value="0" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 0) ? 'selected' : ''; ?>>0</option>
                                                    <option value="1" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 1) ? 'selected' : ''; ?>>1</option>
                                                    <option value="2" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 2) ? 'selected' : ''; ?>>2</option>
                                                    <option value="3" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 3) ? 'selected' : ''; ?>>3</option>
                                                    <option value="4" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 4) ? 'selected' : ''; ?>>4</option>
                                                    <option value="5" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 5) ? 'selected' : ''; ?>>5</option>
                                                    <option value="6" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 6) ? 'selected' : ''; ?>>6</option>
                                                    <option value="7" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 7) ? 'selected' : ''; ?>>7</option>
                                                    <option value="8" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 8) ? 'selected' : ''; ?>>8</option>
                                                    <option value="9" <?php echo (isset($order_data->AdditionalOrder) && $order_data->AdditionalOrder == 9) ? 'selected' : ''; ?>>9</option>
                                                </select>
                                            </div>
                                            <div class="col-sm mt-3 pt-3">
                                                <div id="addfolderinput">

                                                </div>
                                                <div class="additional_values">
                                                    <input type='hidden' id='additional_order1_hidden' name='additional_order1_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work1)) ? $order_data->AdditionalOrderData->work1 : ""; ?>'>
                                                    <input type='hidden' id='additional_order2_hidden' name='additional_order2_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work2)) ? $order_data->AdditionalOrderData->work2 : ""; ?>'>
                                                    <input type='hidden' id='additional_order3_hidden' name='additional_order3_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work3)) ? $order_data->AdditionalOrderData->work3 : ""; ?>'>
                                                    <input type='hidden' id='additional_order4_hidden' name='additional_order4_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work4)) ? $order_data->AdditionalOrderData->work4 : ""; ?>'>
                                                    <input type='hidden' id='additional_order5_hidden' name='additional_order5_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work5)) ? $order_data->AdditionalOrderData->work5 : ""; ?>'>
                                                    <input type='hidden' id='additional_order6_hidden' name='additional_order6_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work6)) ? $order_data->AdditionalOrderData->work6 : ""; ?>'>
                                                    <input type='hidden' id='additional_order7_hidden' name='additional_order7_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work7)) ? $order_data->AdditionalOrderData->work7 : ""; ?>'>
                                                    <input type='hidden' id='additional_order8_hidden' name='additional_order8_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work8)) ? $order_data->AdditionalOrderData->work8 : ""; ?>'>
                                                    <input type='hidden' id='additional_order9_hidden' name='additional_order9_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work9)) ? $order_data->AdditionalOrderData->work9 : ""; ?>'>
                                                    <input type='hidden' id='additional_order10_hidden' name='additional_order10_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work10)) ? $order_data->AdditionalOrderData->work10 : ""; ?>'>
                                                    <input type='hidden' id='additional_order11_hidden' name='additional_order11_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work11)) ? $order_data->AdditionalOrderData->work11 : ""; ?>'>
                                                    <input type='hidden' id='additional_order12_hidden' name='additional_order12_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work12)) ? $order_data->AdditionalOrderData->work12 : ""; ?>'>
                                                    <input type='hidden' id='additional_order13_hidden' name='additional_order13_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work13)) ? $order_data->AdditionalOrderData->work13 : ""; ?>'>
                                                    <input type='hidden' id='additional_order14_hidden' name='additional_order14_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work14)) ? $order_data->AdditionalOrderData->work14 : ""; ?>'>
                                                    <input type='hidden' id='additional_order15_hidden' name='additional_order15_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work15)) ? $order_data->AdditionalOrderData->work15 : ""; ?>'>
                                                    <input type='hidden' id='additional_order16_hidden' name='additional_order16_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work16)) ? $order_data->AdditionalOrderData->work16 : ""; ?>'>
                                                    <input type='hidden' id='additional_order17_hidden' name='additional_order17_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work17)) ? $order_data->AdditionalOrderData->work17 : ""; ?>'>
                                                    <input type='hidden' id='additional_order18_hidden' name='additional_order18_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work18)) ? $order_data->AdditionalOrderData->work18 : ""; ?>'>
                                                    <input type='hidden' id='additional_order19_hidden' name='additional_order19_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work19)) ? $order_data->AdditionalOrderData->work19 : ""; ?>'>
                                                    <input type='hidden' id='additional_order20_hidden' name='additional_order20_hidden' value='<?php echo (isset($order_data->AdditionalOrderData->work1) && !empty($order_data->AdditionalOrderData->work20)) ? $order_data->AdditionalOrderData->work20 : ""; ?>'>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container --><br>
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
<!-- <script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
<script src="/assets/javascript/pages/order-setting.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>



<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>
