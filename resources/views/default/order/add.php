<?php
$title_meta = 'Order Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Order Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Order Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Order for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <form name="order_market_request" id="order_market_request" action="/order/insert_order" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select">
                                                    <option value="" selected><?= _('Select Marketplace...') ?></option>
                                                    <?php
                                                    if (isset($market_places) && is_array($market_places) && !empty($market_places)) {
                                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val['Id']; ?>" <?php echo (isset($form['MarketName']) && $form['MarketName'] == $mar_val['Id']) ? 'selected' : ''; ?>><?php echo $mar_val['MarketName']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Marketplace found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="MarketPlaceOrder"><?= _('MarketPlace Order') ?></label>
                                                <input type="text" class="form-control" id="MarketPlaceOrder" name="MarketPlaceOrder" placeholder="Enter MarketPlace Order" data-parsley-required-message="<?= _('Enter MarketPlace Order') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MarketPlaceOrder']) && !empty($form['MarketPlaceOrder'])) ? $form['MarketPlaceOrder'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <select name="StoreProductId" id="StoreProductId" class="browser-default custom-select store_product_select">
                                                    <option value="" selected disabled><?= _('Select Store Product...') ?></option>
                                                    <?php
                                                    if (isset($products) && is_array($products) && !empty($products)) {
                                                        foreach ($products as $prod_key => $prod_val) { ?>
                                                            <option value="<?php echo $prod_val['Id']; ?>" <?php echo (isset($form['StoreProductId']) && $form['StoreProductId'] == $prod_val['Id']) ? 'selected' : ''; ?>><?php echo $prod_val['Name'] . " | SKU | " . $prod_val['SKU'] . " | ISBN | " . $prod_val['ProdId']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Products found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="OrderStatus" id="OrderStatus" class="browser-default custom-select order_status_select">
                                                    <option value="" selected><?= _('Select Status...') ?></option>
                                                    <option value="new" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "new") ? 'selected' : ''; ?>><?= _('New') ?></option>
                                                    <option value="in-process" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "in-process") ? 'selected' : ''; ?>><?= _('In Process') ?></option>
                                                    <option value="shipped" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "shipped") ? 'selected' : ''; ?>><?= _('Shipped') ?></option>
                                                    <option value="deferred" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "deferred") ? 'selected' : ''; ?>><?= _('Deferred') ?></option>
                                                    <option value="cancelled" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "cancelled") ? 'selected' : ''; ?>><?= _('Cancelled') ?></option>
                                                    <option value="shipped-noemail" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "shipped-noemail") ? 'selected' : ''; ?>><?= _('Shipped - No Email') ?></option>
                                                    <option value="cancelled-noemail" <?php echo (isset($form['OrderStatus']) && $form['OrderStatus'] == "cancelled-noemail") ? 'selected' : ''; ?>><?= _('Cancelled - No Email') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="PaymentStatus" id="PaymentStatus" class="browser-default custom-select payment_status_select">
                                                    <option value="" selected><?= _('Select Payment Status...') ?></option>
                                                    <option value="pre-paid" <?php echo (isset($form['PaymentStatus']) && $form['PaymentStatus'] == "pre-paid") ? 'selected' : ''; ?>><?= _('Pre-Paid') ?></option>
                                                    <option value="pending" <?php echo (isset($form['PaymentStatus']) && $form['PaymentStatus'] == "pending") ? 'selected' : ''; ?>><?= _('Pending') ?></option>
                                                    <option value="paid" <?php echo (isset($form['PaymentStatus']) && $form['PaymentStatus'] == "paid") ? 'selected' : ''; ?>><?= _('Paid') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="BuyerNote"><?= _('Buyer Note') ?></label>
                                                <textarea class="form-control" id="BuyerNote" name="BuyerNote" rows="3" data-parsley-required-message="<?= _('Enter Buyer Note') ?>" placeholder="Enter Buyer Note" data-parsley-group="fieldset01" required><?php echo (isset($form['BuyerNote']) && !empty($form['BuyerNote'])) ? $form['BuyerNote'] : ''; ?></textarea>
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
                                                    <option value="USD" <?php echo (isset($form['Currency']) && $form['Currency'] == "USD") ? 'selected' : ''; ?>><?= _('USD') ?></option>
                                                    <option value="CAD" <?php echo (isset($form['Currency']) && $form['Currency'] == "CAD") ? 'selected' : ''; ?>><?= _('CAD') ?></option>
                                                    <option value="EUR" <?php echo (isset($form['Currency']) && $form['Currency'] == "EUR") ? 'selected' : ''; ?>><?= _('EUR') ?></option>
                                                    <option value="GBP" <?php echo (isset($form['Currency']) && $form['Currency'] == "GBP") ? 'selected' : ''; ?>><?= _('GBP') ?></option>
                                                    <option value="MXN" <?php echo (isset($form['Currency']) && $form['Currency'] == "MXN") ? 'selected' : ''; ?>><?= _('MXN') ?></option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="PaymentMethod"><?= _('Payment Method') ?></label>
                                                <input type="text" class="form-control" id="PaymentMethod" name="PaymentMethod" placeholder="Enter Payment Method" data-parsley-required-message="<?= _('Enter Payment Method') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['PaymentMethod']) && !empty($form['PaymentMethod'])) ? $form['PaymentMethod'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="SellerNote"><?= _('Seller Note') ?></label>
                                                <textarea class="form-control" id="SellerNote" name="SellerNote" rows="3" data-parsley-required-message="<?= _('Enter Seller Note') ?>" placeholder="Enter Seller Note" data-parsley-group="fieldset01" required><?php echo (isset($form['SellerNote']) && !empty($form['SellerNote'])) ? $form['SellerNote'] : ''; ?></textarea>
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
                                                <input type="text" class="form-control" id="ShippingMethod" name="ShippingMethod" placeholder="Enter Shipping Method" data-parsley-required-message="<?= _('Enter Shipping Method') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ShippingMethod']) && !empty($form['ShippingMethod'])) ? $form['ShippingMethod'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <select name="CarrierOrder" id="CarrierOrder" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected><?= _('Select Status...') ?></option>
                                                    <option value="fedex" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "fedex") ? 'selected' : ''; ?>><?= _('FEDEX') ?></option>
                                                    <option value="dhl" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "dhl") ? 'selected' : ''; ?>><?= _('DHL') ?></option>
                                                    <option value="dhlgm" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "dhlgm") ? 'selected' : ''; ?>><?= _('DHLGM') ?></option>
                                                    <option value="usps" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "usps") ? 'selected' : ''; ?>><?= _('USPS') ?></option>
                                                    <option value="ups" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "ups") ? 'selected' : ''; ?>><?= _('UPS') ?></option>
                                                    <option value="upsmi" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "upsmi") ? 'selected' : ''; ?>><?= _('UPSMI') ?></option>
                                                    <option value="misc" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "misc") ? 'selected' : ''; ?>><?= _('MISC') ?></option>
                                                    <option value="auto" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "auto") ? 'selected' : ''; ?>><?= _('AUTO') ?></option>
                                                    <option value="other" <?php echo (isset($form['CarrierOrder']) && $form['CarrierOrder'] == "other") ? 'selected' : ''; ?>><?= _('Other') ?></option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <div class="form-group">
                                                <label for="Tracking"><?= _('Tracking') ?></label>
                                                <input type="text" class="form-control" id="Tracking" name="Tracking" placeholder="Enter Tracking" data-parsley-required-message="<?= _('Enter Tracking') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['Tracking']) && !empty($form['Tracking'])) ? $form['Tracking'] : ''; ?>">
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
                                <h4 class="card-title"> <?= _('Shipping Address') ?> </h4>

                                <div class="form-group">
                                    <label for="ShippingName"><?= _('Name') ?></label>
                                    <input type="text" class="form-control" id="ShippingName" name="ShippingName" placeholder="Enter Name" data-parsley-required-message="Enter Name" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingName']) && !empty($form['ShippingName'])) ? $form['ShippingName'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingPhone"><?= _('Phone') ?></label>
                                    <input type="text" class="form-control" id="ShippingPhone" name="ShippingPhone" placeholder="Enter Phone" data-parsley-required-message="Enter Phone" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingPhone']) && !empty($form['ShippingPhone'])) ? $form['ShippingPhone'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingEmail"><?= _('Email') ?></label>
                                    <input type="email" class="form-control" id="ShippingEmail" name="ShippingEmail" placeholder="Enter Email" data-parsley-required-message="Enter Email" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingEmail']) && !empty($form['ShippingEmail'])) ? $form['ShippingEmail'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingAddress1"><?= _('Address 1') ?></label>
                                    <input type="text" class="form-control" id="ShippingAddress1" name="ShippingAddress1" placeholder="Enter Address1" data-parsley-required-message="Enter Address1" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingAddress1']) && !empty($form['ShippingAddress1'])) ? $form['ShippingAddress1'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingAddress2"><?= _('Address 2') ?></label>
                                    <input type="text" class="form-control" id="ShippingAddress2" name="ShippingAddress2" placeholder="Enter Address2" data-parsley-required-message="Enter Address2" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingAddress2']) && !empty($form['ShippingAddress2'])) ? $form['ShippingAddress2'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingAddress3"><?= _('Address 3') ?></label>
                                    <input type="text" class="form-control" id="ShippingAddress3" name="ShippingAddress3" placeholder="Enter Address3" data-parsley-required-message="Enter Address3" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingAddress3']) && !empty($form['ShippingAddress3'])) ? $form['ShippingAddress3'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingCity"><?= _('City') ?></label>
                                    <input type="text" class="form-control" id="ShippingCity" name="ShippingCity" placeholder="Enter City" data-parsley-required-message="Enter City" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingCity']) && !empty($form['ShippingCity'])) ? $form['ShippingCity'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingState"><?= _('State/Province') ?></label>
                                    <input type="text" class="form-control" id="ShippingState" name="ShippingState" placeholder="Enter State" data-parsley-required-message="Enter State" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingState']) && !empty($form['ShippingState'])) ? $form['ShippingState'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingZipCode"><?= _('Zip/Postal Code') ?></label>
                                    <input type="text" class="form-control" id="ShippingZipCode" name="ShippingZipCode" placeholder="Enter ZipCode" data-parsley-required-message="Enter ZipCode" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingZipCode']) && !empty($form['ShippingZipCode'])) ? $form['ShippingZipCode'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="ShippingCountry"><?= _('Country') ?></label>
                                    <input type="text" class="form-control" id="ShippingCountry" name="ShippingCountry" placeholder="Enter Country" data-parsley-required-message="Enter Country" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['ShippingCountry']) && !empty($form['ShippingCountry'])) ? $form['ShippingCountry'] : ''; ?>">
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                        <!-- .card -->
                        <div class="card card-fluid">
                            <!-- .card-body -->
                            <div class="card-body">
                                <h4 class="card-title"> <?= _('Billing Address') ?> </h4>

                                <div class="form-group">
                                    <label for="BillingName"><?= _('Name') ?></label>
                                    <input type="text" class="form-control" id="BillingName" name="BillingName" placeholder="Enter Name" data-parsley-required-message="Enter Name" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingName']) && !empty($form['BillingName'])) ? $form['BillingName'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingPhone"><?= _('Phone') ?></label>
                                    <input type="text" class="form-control" id="BillingPhone" name="BillingPhone" placeholder="Enter Phone" data-parsley-required-message="Enter Phone" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingPhone']) && !empty($form['BillingPhone'])) ? $form['BillingPhone'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingEmail"><?= _('Email') ?></label>
                                    <input type="email" class="form-control" id="BillingEmail" name="BillingEmail" placeholder="Enter Email" data-parsley-required-message="Enter Email" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingEmail']) && !empty($form['BillingEmail'])) ? $form['BillingEmail'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingAddress1"><?= _('Address 1') ?></label>
                                    <input type="text" class="form-control" id="BillingAddress1" name="BillingAddress1" placeholder="Enter Address1" data-parsley-required-message="Enter Address1" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingAddress1']) && !empty($form['BillingAddress1'])) ? $form['BillingAddress1'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingAddress2"><?= _('Address 2') ?></label>
                                    <input type="text" class="form-control" id="BillingAddress2" name="BillingAddress2" placeholder="Enter Address2" data-parsley-required-message="Enter Address2" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingAddress2']) && !empty($form['BillingAddress2'])) ? $form['BillingAddress2'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingAddress3"><?= _('Address 3') ?></label>
                                    <input type="text" class="form-control" id="BillingAddress3" name="BillingAddress3" placeholder="Enter Address3" data-parsley-required-message="Enter Address3" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingAddress3']) && !empty($form['BillingAddress3'])) ? $form['BillingAddress3'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingCity"><?= _('City') ?></label>
                                    <input type="text" class="form-control" id="BillingCity" name="BillingCity" placeholder="Enter City" data-parsley-required-message="Enter City" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingCity']) && !empty($form['BillingCity'])) ? $form['BillingCity'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingState"><?= _('State/Province') ?></label>
                                    <input type="text" class="form-control" id="BillingState" name="BillingState" placeholder="Enter State" data-parsley-required-message="Enter State" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingState']) && !empty($form['BillingState'])) ? $form['BillingState'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingZipCode"><?= _('Zip/Postal Code') ?></label>
                                    <input type="text" class="form-control" id="BillingZipCode" name="BillingZipCode" placeholder="Enter ZipCode" data-parsley-required-message="Enter ZipCode" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingZipCode']) && !empty($form['BillingZipCode'])) ? $form['BillingZipCode'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="BillingCountry"><?= _('Country') ?></label>
                                    <input type="text" class="form-control" id="BillingCountry" name="BillingCountry" placeholder="Enter Country" data-parsley-required-message="Enter Country" data-parsley-group="fieldset01" required="" value="<?php echo (isset($form['BillingCountry']) && !empty($form['BillingCountry'])) ? $form['BillingCountry'] : ''; ?>">
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
