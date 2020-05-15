<?php
$title_meta = 'Order Edit for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Order Edit for your Tracksz Store, a Multiple Market Product Management Service';
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
                            <li class="breadcrumb-item active"><?= ('Edit') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Order Edit') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Order for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <form name="order_market_request" id="order_market_request" action="/order/update_order" method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <!-- .page-section starts -->
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->
                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Order Information') ?></h5>

                                            <?php

                                            echo '<pre> Test 1 :: Starts';
                                            print_r($form);
                                            echo '</pre>';
                                            die('LOOP ENDS HERE');
                                            ?>
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select">
                                                    <option value="" selected><?= _('Select Marketplace...') ?></option>
                                                    <?php
                                                    if (isset($market_places) && is_array($market_places) && !empty($market_places)) {
                                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val['Id']; ?>"><?php echo $mar_val['MarketName']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Marketplace found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" class="form-control" id="Id" name="Id" value="<?php echo (isset($all_order['Id']) && !empty($all_order['Id'])) ? $all_order['Id'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="MarketPlaceOrder"><?= _('MarketPlace Order') ?></label>
                                                <input type="text" class="form-control" id="MarketPlaceOrder" name="MarketPlaceOrder" placeholder="Enter MarketPlace Order" data-parsley-required-message="<?= _('Enter MarketPlace Order') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['MarketPlaceOrder']) && !empty($all_order['MarketPlaceOrder'])) ? $all_order['MarketPlaceOrder'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <select name="OrderStatus" id="OrderStatus" class="browser-default custom-select order_status_select">
                                                    <option value="" selected><?= _('Select Status...') ?></option>
                                                    <option value="new"><?= _('New') ?></option>
                                                    <option value="in-process"><?= _('In Process') ?></option>
                                                    <option value="shipped"><?= _('Shipped') ?></option>
                                                    <option value="deferred"><?= _('Deferred') ?></option>
                                                    <option value="cancelled"><?= _('Cancelled') ?></option>
                                                    <option value="shipped-noemail"><?= _('Shipped - No Email') ?></option>
                                                    <option value="cancelled-noemail"><?= _('Cancelled - No Email') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="PaymentStatus" id="PaymentStatus" class="browser-default custom-select payment_status_select">
                                                    <option value="" selected><?= _('Select Payment Status...') ?></option>
                                                    <option value="pre-paid"><?= _('Pre-Paid') ?></option>
                                                    <option value="pending"><?= _('Pending') ?></option>
                                                    <option value="paid"><?= _('Paid') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="BuyerNote"><?= _('Buyer Note') ?></label>
                                                <textarea class="form-control" id="BuyerNote" name="BuyerNote" rows="3" data-parsley-required-message="<?= _('Enter Buyer Note') ?>" placeholder="Enter Buyer Note" data-parsley-group="fieldset01" required><?php echo (isset($all_order['BuyerNote']) && !empty($all_order['BuyerNote'])) ? $all_order['BuyerNote'] : ''; ?></textarea>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <div class="form-group">
                                            </div>
                                            <div class="form-group">
                                            </div>
                                            <div class="form-group">
                                            </div>
                                            <div class="form-group">
                                                <select name="Currency" id="Currency" class="browser-default custom-select order_currency_select">
                                                    <option value="" selected><?= _('Select Currency...') ?></option>
                                                    <option value="USD"><?= _('USD') ?></option>
                                                    <option value="CAD"><?= _('CAD') ?></option>
                                                    <option value="EUR"><?= _('EUR') ?></option>
                                                    <option value="GBP"><?= _('GBP') ?></option>
                                                    <option value="MXN"><?= _('MXN') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="PaymentMethod"><?= _('Payment Method') ?></label>
                                                <input type="text" class="form-control" id="PaymentMethod" name="PaymentMethod" placeholder="Enter Payment Method" data-parsley-required-message="<?= _('Enter Payment Method') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['PaymentMethod']) && !empty($all_order['PaymentMethod'])) ? $all_order['PaymentMethod'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="SellerNote"><?= _('Seller Note') ?></label>
                                                <textarea class="form-control" id="SellerNote" name="SellerNote" rows="3" data-parsley-required-message="<?= _('Enter Seller Note') ?>" placeholder="Enter Seller Note" data-parsley-group="fieldset01" required><?php echo (isset($all_order['SellerNote']) && !empty($all_order['SellerNote'])) ? $all_order['SellerNote'] : ''; ?></textarea>
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
                                        <div class="col-sm">
                                            <!-- form starts -->
                                            <h5 class="card-title"><?= _('Shipping Information') ?></h5>
                                            <div class="form-group">
                                                <label for="ShippingMethod"><?= _('Shipping Method') ?></label>
                                                <input type="text" class="form-control" id="ShippingMethod" name="ShippingMethod" placeholder="Enter Shipping Method" data-parsley-required-message="<?= _('Enter Shipping Method') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['ShippingMethod']) && !empty($all_order['ShippingMethod'])) ? $all_order['ShippingMethod'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <select name="CarrierOrder" id="CarrierOrder" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected><?= _('Select Status...') ?></option>
                                                    <option value="fedex"><?= _('FEDEX') ?></option>
                                                    <option value="dhl"><?= _('DHL') ?></option>
                                                    <option value="dhlgm"><?= _('DHLGM') ?></option>
                                                    <option value="usps"><?= _('USPS') ?></option>
                                                    <option value="ups"><?= _('UPS') ?></option>
                                                    <option value="upsmi"><?= _('UPSMI') ?></option>
                                                    <option value="misc"><?= _('MISC') ?></option>
                                                    <option value="auto"><?= _('AUTO') ?></option>
                                                    <option value="other"><?= _('Other') ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <div class="form-group">
                                                <label for="Tracking"><?= _('Tracking') ?></label>
                                                <input type="text" class="form-control" id="Tracking" name="Tracking" placeholder="Enter Tracking" data-parsley-required-message="<?= _('Enter Tracking') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['Tracking']) && !empty($all_order['Tracking'])) ? $all_order['Tracking'] : ''; ?>">
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->

                    </div> <!-- .card-deck-xl ends -->
                    <div class="card-deck-xl">
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body">
                                <h4 class="card-title"> <?= _('Shipping Editress') ?> </h4>

                                <div class="form-group">
                                    <label for="ShippingName"><?= _('Name') ?></label>
                                    <input type="text" class="form-control" id="ShippingName" name="ShippingName" placeholder="Enter Name" data-parsley-required-message="Enter Name" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingName']) && !empty($all_order['ShippingName'])) ? $all_order['ShippingName'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingPhone"><?= _('Phone') ?></label>
                                    <input type="text" class="form-control" id="ShippingPhone" name="ShippingPhone" placeholder="Enter Phone" data-parsley-required-message="Enter Phone" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingPhone']) && !empty($all_order['ShippingPhone'])) ? $all_order['ShippingPhone'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingEmail"><?= _('Email') ?></label>
                                    <input type="email" class="form-control" id="ShippingEmail" name="ShippingEmail" placeholder="Enter Email" data-parsley-required-message="Enter Email" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingEmail']) && !empty($all_order['ShippingEmail'])) ? $all_order['ShippingEmail'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingEditress1"><?= _('Editress 1') ?></label>
                                    <input type="text" class="form-control" id="ShippingEditress1" name="ShippingEditress1" placeholder="Enter Editress1" data-parsley-required-message="Enter Editress1" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingEditress1']) && !empty($all_order['ShippingEditress1'])) ? $all_order['ShippingEditress1'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingEditress2"><?= _('Editress 2') ?></label>
                                    <input type="text" class="form-control" id="ShippingEditress2" name="ShippingEditress2" placeholder="Enter Editress2" data-parsley-required-message="Enter Editress2" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingEditress2']) && !empty($all_order['ShippingEditress2'])) ? $all_order['ShippingEditress2'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingEditress3"><?= _('Editress 3') ?></label>
                                    <input type="text" class="form-control" id="ShippingEditress3" name="ShippingEditress3" placeholder="Enter Editress3" data-parsley-required-message="Enter Editress3" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingEditress3']) && !empty($all_order['ShippingEditress3'])) ? $all_order['ShippingEditress3'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingCity"><?= _('City') ?></label>
                                    <input type="text" class="form-control" id="ShippingCity" name="ShippingCity" placeholder="Enter City" data-parsley-required-message="Enter City" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingCity']) && !empty($all_order['ShippingCity'])) ? $all_order['ShippingCity'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingState"><?= _('State/Province') ?></label>
                                    <input type="text" class="form-control" id="ShippingState" name="ShippingState" placeholder="Enter State" data-parsley-required-message="Enter State" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingState']) && !empty($all_order['ShippingState'])) ? $all_order['ShippingState'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingZipCode"><?= _('Zip/Postal Code') ?></label>
                                    <input type="text" class="form-control" id="ShippingZipCode" name="ShippingZipCode" placeholder="Enter ZipCode" data-parsley-required-message="Enter ZipCode" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingZipCode']) && !empty($all_order['ShippingZipCode'])) ? $all_order['ShippingZipCode'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingCountry"><?= _('Country') ?></label>
                                    <input type="text" class="form-control" id="ShippingCountry" name="ShippingCountry" placeholder="Enter Country" data-parsley-required-message="Enter Country" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['ShippingCountry']) && !empty($all_order['ShippingCountry'])) ? $all_order['ShippingCountry'] : ''; ?>">
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body">
                                <h4 class="card-title"> <?= _('Billing Editress') ?> </h4>

                                <div class="form-group">
                                    <label for="BillingName"><?= _('Name') ?></label>
                                    <input type="text" class="form-control" id="BillingName" name="BillingName" placeholder="Enter Name" data-parsley-required-message="Enter Name" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingName']) && !empty($all_order['BillingName'])) ? $all_order['BillingName'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingPhone"><?= _('Phone') ?></label>
                                    <input type="text" class="form-control" id="BillingPhone" name="BillingPhone" placeholder="Enter Phone" data-parsley-required-message="Enter Phone" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingPhone']) && !empty($all_order['BillingPhone'])) ? $all_order['BillingPhone'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingEmail"><?= _('Email') ?></label>
                                    <input type="email" class="form-control" id="BillingEmail" name="BillingEmail" placeholder="Enter Email" data-parsley-required-message="Enter Email" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingEmail']) && !empty($all_order['BillingEmail'])) ? $all_order['BillingEmail'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingEditress1"><?= _('Editress 1') ?></label>
                                    <input type="text" class="form-control" id="BillingEditress1" name="BillingEditress1" placeholder="Enter Editress1" data-parsley-required-message="Enter Editress1" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingEditress1']) && !empty($all_order['BillingEditress1'])) ? $all_order['BillingEditress1'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingEditress2"><?= _('Editress 2') ?></label>
                                    <input type="text" class="form-control" id="BillingEditress2" name="BillingEditress2" placeholder="Enter Editress2" data-parsley-required-message="Enter Editress2" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingEditress2']) && !empty($all_order['BillingEditress2'])) ? $all_order['BillingEditress2'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingEditress3"><?= _('Editress 3') ?></label>
                                    <input type="text" class="form-control" id="BillingEditress3" name="BillingEditress3" placeholder="Enter Editress3" data-parsley-required-message="Enter Editress3" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingEditress3']) && !empty($all_order['BillingEditress3'])) ? $all_order['BillingEditress3'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingCity"><?= _('City') ?></label>
                                    <input type="text" class="form-control" id="BillingCity" name="BillingCity" placeholder="Enter City" data-parsley-required-message="Enter City" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingCity']) && !empty($all_order['BillingCity'])) ? $all_order['BillingCity'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingState"><?= _('State/Province') ?></label>
                                    <input type="text" class="form-control" id="BillingState" name="BillingState" placeholder="Enter State" data-parsley-required-message="Enter State" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingState']) && !empty($all_order['BillingState'])) ? $all_order['BillingState'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingZipCode"><?= _('Zip/Postal Code') ?></label>
                                    <input type="text" class="form-control" id="BillingZipCode" name="BillingZipCode" placeholder="Enter ZipCode" data-parsley-required-message="Enter ZipCode" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingZipCode']) && !empty($all_order['BillingZipCode'])) ? $all_order['BillingZipCode'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingCountry"><?= _('Country') ?></label>
                                    <input type="text" class="form-control" id="BillingCountry" name="BillingCountry" placeholder="Enter Country" data-parsley-required-message="Enter Country" data-parsley-group="fieldset01" required="" value="<?php echo (isset($all_order['BillingCountry']) && !empty($all_order['BillingCountry'])) ? $all_order['BillingCountry'] : ''; ?>">
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div> <!-- .page-section ends -->
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