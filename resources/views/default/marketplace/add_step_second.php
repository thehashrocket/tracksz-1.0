<?php
$title_meta = 'Marketplace Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Marketplace Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/marketplace/list" title="Browse Marketplace"><?= ('Marketplace') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Browse') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Marketplace for the current Active Store: ') ?><strong>
                        <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- Horizontal Steppers -->
            <!--  <div class="row">  row Starts -->
            <!-- <div class="col-md-12">  col-md-12 Starts -->
            <!-- <div class="progress">  progress Starts -->
            <!--  <div class="progress-bar" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
                           <?= _('Step 1 Of 3') ?>  progress-bar Starts -->
            <!--    </div>  progress-bar Ends -->
            <!--     </div> progress Ends -->
            <!--   </div> col-md-12 Ends -->
            <!--  </div>  row Ends -->
            <!-- /.Horizontal Steppers -->

            <!-- Steppers Starts -->
            <div class="card">
                <!-- .card-body -->
                <div class="card-body">
                    <!-- .progress-list -->
                    <ol class="progress-list mb-0 mb-sm-4">
                        <li class="success">
                            <button type="button">
                                <!-- progress indicator -->
                                <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 1</span>
                        </li>
                        <li class="success">
                            <button type="button">
                                <!-- progress indicator -->
                                <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 2</span>
                        </li>
                        <li class="active">
                            <button type="button">
                                <!-- progress indicator -->
                                <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 3</span>
                        </li>
                    </ol><!-- /.progress-list -->
                </div><!-- /.card-body -->
                <!-- .card-body -->
            </div>
            <!-- Steppers Ends -->










            <form name="category_market_request" id="category_market_request" action="<?= \App\Library\Config::get('company_url') ?>/marketplace/dashboard/step3" method="POST" data-parsley-validate>

                <div class="page-section">
                    <!-- .page-section starts -->
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->

                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="row">
                                    <!-- .row starts -->
                                    <div class="col-sm">
                                        <!-- col-sm Group Left Starts -->
                                        <h5 class="card-title"><?= _('Marketplace Credentials') ?></h5> <!-- Card title -->
                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="EmailAddress"><?= _('Email') ?></label>
                                            <input type="email" class="form-control" id="EmailAddress" name="EmailAddress" placeholder="Enter Email Address" data-parsley-required-message="<?= _('Enter Email Address') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['EmailAddress']) && !empty($form['EmailAddress'])) ? $form['EmailAddress'] : ''; ?>">
                                            <input type="hidden" class="form-control" id="MarketName" name="MarketName" value="<?php echo (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : ''; ?>">
                                        </div> <!-- form-group ends -->

                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="SellerID"><?= _('Seller ID') ?></label>
                                            <input type="text" class="form-control" id="SellerID" name="SellerID" placeholder="Enter Seller Id" data-parsley-required-message="<?= _('Enter Seller ID') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['SellerID']) && !empty($form['SellerID'])) ? $form['SellerID'] : ''; ?>">
                                        </div> <!-- form-group ends -->

                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="Password"><?= _('Password') ?></label>
                                            <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password" data-parsley-required-message="<?= _('Enter Password') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['Password']) && !empty($form['Password'])) ? $form['Password'] : ''; ?>">
                                            <small id="PasswordID" class="form-text text-muted" aria-describedby="PasswordID"><?= _('Note: Value will always appear as asterisks for security purposes') ?></small>
                                        </div> <!-- form-group ends -->
                                    </div> <!-- col-sm Group Left Ends -->

                                    <div class="col-sm mt-3 pt-3">
                                        <!-- col-sm Group Right Starts -->

                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="FtpAddress"><?= _('FTP Address') ?></label>
                                            <input type="text" class="form-control" id="FtpAddress" name="FtpAddress" placeholder="Enter FTP Address" data-parsley-required-message="<?= _('Enter Ftp Address') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['FtpAddress']) && !empty($form['FtpAddress'])) ? $form['FtpAddress'] : ''; ?>">
                                        </div> <!-- form-group ends -->

                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="FtpId"><?= _('FTP ID') ?></label>
                                            <input type="text" class="form-control" id="FtpId" name="FtpId" placeholder="Enter FTP Id" data-parsley-required-message="<?= _('Enter Ftp Id') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['FtpId']) && !empty($form['FtpId'])) ? $form['FtpId'] : ''; ?>">
                                        </div> <!-- form-group ends -->

                                        <div class="form-group">
                                            <!-- form-group starts -->
                                            <label for="FtpPwd"><?= _('FTP Pwd') ?></label>
                                            <input type="password" class="form-control" id="FtpPwd" name="FtpPwd" placeholder="Enter FTP Password" data-parsley-required-message="<?= _('Enter Ftp Password') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['FtpPwd']) && !empty($form['FtpPwd'])) ? $form['FtpPwd'] : ''; ?>">
                                            <small id="FtpHelp" class="form-text text-muted" aria-describedby="FtpHelp"><?= _('Note: Value will always appear as asterisks for security purposes') ?></small>
                                        </div> <!-- form-group ends -->
                                    </div> <!-- col-sm Group Right Ends -->
                                </div> <!-- Row Ends -->
                            </div> <!-- Card Body -->

                        </div> <!-- .card card-fluid ends -->
                    </div> <!-- .card-deck-xl ends -->
                </div> <!-- .page-section ends -->

                <div class="page-section">
                    <!-- .page-section starts -->
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->

                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="row">
                                    <div class="col-12">
                                        <h5><?= _('Marketplace Settings') ?></h5>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PrependVenue"><?= _('Prepend venue-specific text to item note:') ?></label>
                                            <input type="text" class="form-control" id="PrependVenue" name="PrependVenue" placeholder="Enter Prepend Venue" data-parsley-required-message="<?= _('Enter Prepend Venue') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['FtpPwd']) && !empty($form['FtpPwd'])) ? $form['FtpPwd'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="AppendVenue"><?= _('Append venue-specific text to item note:') ?></label>
                                            <input type="text" class="form-control" id="AppendVenue" name="AppendVenue" placeholder="Enter FTP Append Venue" data-parsley-required-message="<?= _('Enter Append Venue') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['AppendVenue']) && !empty($form['AppendVenue'])) ? $form['AppendVenue'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="IncreaseMinMarket"><?= _('Increase to marketplace minimum if item is at least:') ?></label> <!-- .input-group -->
                                            <div class="input-group">
                                                <label class="input-group-prepend" for="IncreaseMinMarket"><span class="input-group-text"><span class="fas fa-dollar-sign"></span></span></label>
                                                <input type="text" class="form-control" name="IncreaseMinMarket" id="IncreaseMinMarket" placeholder="Enter IncreaseMinMarket" data-parsley-required-message="<?= _('Enter IncreaseMinMarket') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['IncreaseMinMarket']) && !empty($form['IncreaseMinMarket'])) ? $form['IncreaseMinMarket'] : '0.01'; ?>">
                                            </div><!-- /.input-group -->
                                        </div>
                                    </div> <!-- col-6 -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PrependVenue"><?= _('File Format') ?></label>
                                            <select name="FileFormat" id="FileFormat" class="browser-default custom-select">
                                                <option value="<?php echo (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : ''; ?>" selected><?php echo (isset($form['MarketName']) && !empty($form['MarketName'])) ? $form['MarketName'] : ''; ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="FtpAppendVenue"><?= _('Order retrieval interval:') ?></label>
                                            <input type="number" class="form-control" id="FtpAppendVenue" name="FtpAppendVenue" placeholder="Enter FTP Append Venue" data-parsley-required-message="<?= _('Enter Ftp Append Venue') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['FtpAppendVenue']) && !empty($form['FtpAppendVenue'])) ? $form['FtpAppendVenue'] : ''; ?>">
                                            <small id="FtpHelp" class="form-text text-muted" aria-describedby="FtpHelp">Enter off to turn off automatic retrieval</small>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="SuspendExport" id="SuspendExport" data-parsley-required-message="<?= _('Enter SuspendExport') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['SuspendExport']) && !empty($form['SuspendExport'])) ? 'checked' : ''; ?>">
                                                <label class="form-check-label" for="SuspendExport">
                                                    <?= _('Suspend exports') ?>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="SendDeletes" id="SendDeletes" data-parsley-required-message="<?= _('Enter SendDeletes') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['SendDeletes']) && !empty($form['SendDeletes'])) ? 'checked' : ''; ?>">
                                                <label class="form-check-label" for="SendDeletes">
                                                    <?= _('Send deletes only') ?>
                                                </label>
                                            </div>
                                        </div>

                                    </div> <!-- col-6 -->
                                </div> <!-- row -->
                            </div> <!-- Card Body -->

                        </div> <!-- .card card-fluid ends -->
                    </div> <!-- .card-deck-xl ends -->
                </div> <!-- .page-section ends -->

                <div class="page-section">
                    <!-- .page-section starts -->
                    <div class="card-deck-xl">
                        <!-- .card-deck-xl starts -->
                        <div class="card card-fluid">
                            <!-- .card card-fluid starts -->

                            <div class="card-body">
                                <!-- .card-body starts -->
                                <div class="row">
                                    <div class="col-12">
                                        <h5><?= _('Currency Settings') ?></h5>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="PrependVenue"><?= _('Marketplace accepts prices in:') ?></label>
                                            <select id="MarketAcceptPrice" name="MarketAcceptPrice" class="browser-default custom-select market_place_dollar">
                                                <option value="USD" selected><?= _('US Dollars') ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="d-block"><?= _('MarketPlace Status:') ?></label>
                                            <div class="custom-control custom-control-inline custom-radio">
                                                <input type="radio" class="custom-control-input" name="MarketStatus" id="MarketStatusActive" value="1"> <label class="custom-control-label" for="MarketStatusActive"><?= _('Active') ?></label>
                                            </div>
                                            <div class="custom-control custom-control-inline custom-radio">
                                                <input type="radio" class="custom-control-input" name="MarketStatus" id="MarketStatusInActive" value="0" checked> <label class="custom-control-label" for="MarketStatusInActive"><?= _('In-Active') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="PrependVenue"><?= _('Marketplace accepts prices in:') ?></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?= _('( Price +') ?></span>
                                            </div>
                                            <input type="text" class="form-control" name="MarketAcceptPriceVal" id="MarketAcceptPriceVal" aria-label="Amount (to the nearest dollar)" data-parsley-required-message="<?= _('Enter Marketplace Accept Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MarketAcceptPriceVal']) && !empty($form['MarketAcceptPriceVal'])) ? $form['MarketAcceptPriceVal'] : '0.00'; ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><?= _(') X') ?></span>
                                            </div>
                                            <input type="text" class="form-control" name="MarketAcceptPriceValMulti" id="MarketAcceptPriceValMulti" aria-label="Amount (to the nearest dollar)" data-parsley-required-message="<?= _('Enter Marketplace Accept Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MarketAcceptPriceValMulti']) && !empty($form['MarketAcceptPriceValMulti'])) ? $form['MarketAcceptPriceValMulti'] : '1.00000'; ?>">
                                        </div>

                                        <div class="form-group">
                                            <p class="market_price"><a href="http://www.fillz.com/help/glossary.html#m_market_specific_prices" target="_blank"><?= _('Market-specific price') ?></a>
                                                <select name="MarketSpecificPrice" id="MarketSpecificPrice" class="browser-default market_price custom-select">
                                                    <?php
                                                    if (isset($market_price) && !empty($market_price)) {
                                                        foreach ($market_price as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val; ?>"><?php echo $mar_val; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Market Price found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span><?= _('will be sent as:') ?></span>
                                            </p>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?= _('( Price +') ?></span>
                                            </div>
                                            <input type="text" class="form-control" name="MarketAcceptPriceVal2" id="MarketAcceptPriceVal2" aria-label="Amount (to the nearest dollar)" data-parsley-required-message="<?= _('Enter Marketplace Accept Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MarketAcceptPriceVal2']) && !empty($form['MarketAcceptPriceVal2'])) ? $form['MarketAcceptPriceVal2'] : '0.00'; ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text market_price_wise"><?= _(') X') ?></span>
                                            </div>
                                            <input type="text" class="form-control" name="MarketAcceptPriceValMulti2" id="MarketAcceptPriceValMulti2" aria-label="Amount (to the nearest dollar)" data-parsley-required-message="<?= _('Enter Marketplace Accept Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['MarketAcceptPriceValMulti2']) && !empty($form['MarketAcceptPriceValMulti2'])) ? $form['MarketAcceptPriceValMulti2'] : '1.00000'; ?>">
                                        </div>
                                    </div>
                                </div> <!-- row -->
                                <a href="<?= \App\Library\Config::get('company_url') ?>/marketplace/dashboard" class="btn btn-warning"><i class="fa fa-arrow-left"> <?= _('Cancel') ?> </i></a> &nbsp;
                                <button type="submit" class="btn btn-primary"><?= _('Next') ?> <i class="fa fa-arrow-right"></i></button>
                            </div> <!-- Card Body Ends  -->

                        </div> <!-- .card card-fluid ends -->
                    </div> <!-- .card-deck-xl ends -->
                </div> <!-- .page-section ends -->
            </form> <!-- forms ends -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/javascript/pages/market_place.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>
