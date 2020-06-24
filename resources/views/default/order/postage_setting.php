<?php
$title_meta = 'Postage Settings for Your Store Product Listings, a Multiple Market Order Management Service';
$description_meta = 'Postage Settings for your store\'s product listings at Tracksz, a Multiple Market Order Management Service';
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
                                <a href="/order/browse" title="Browse Store's Order"><?= ('Order') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Defaults') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Postage Settings') ?> </h1>
                <p class="text-muted"> <?= _('Configure default settings for your Active Store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <form name="order_market_request" id="postage_setting" action="/order/add_update_postage_setting" method="POST" enctype="multipart/form-data" data-parsley-validate>
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
                                            <h5 class="card-title"><?= _('Postage (Endicia/DAZzle)') ?></h5>
                                            <div class="form-group">
                                                <label for="OperatingSystem"><?= _('Operating System') ?> <i class="fa fa-question-circle" title="<?= _('Select Operating System use want to use.') ?>" data-toggle="tooltip" data-placement="right"></i></label>
                                                <select name="OperatingSystem" id="OperatingSystem" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected disabled><?= _('Select Operating System...') ?></option>
                                                    <option value="Windows" <?php if (isset($all_order['OperatingSystem']) && $all_order['OperatingSystem'] == 'Windows') {
                                                                                echo 'selected';
                                                                            } ?>>Windows</option>
                                                    <option value="Osx" <?php if (isset($all_order['OperatingSystem']) && $all_order['OperatingSystem'] == 'Osx') {
                                                                            echo 'selected';
                                                                        } ?>>Osx</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Max weight to auto-upgrade to First Class"><?= _('Max weight to auto-upgrade to First Class') ?></label>
                                                <input type="text" class="form-control" id="MaxWeight" name="MaxWeight" placeholder="Max weight to auto-upgrade to First Class" data-parsley-required-message="<?= _('Max weight to auto-upgrade to First Class') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['MaxWeight']) && !empty($all_order['MaxWeight'])) ? $all_order['MaxWeight'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="Delivery Confirmation"><?= _('Delivery Confirmation') ?></label>
                                                <select name="DeliveryConfirmation" id="DeliveryConfirmation" class="browser-default custom-select order_carrier_select">
                                                    <option value="" selected><?= _('Select Delivery Confirmation...') ?></option>
                                                    <option value="Only for Priority" <?php if (isset($all_order['DeliveryConfirmation']) && $all_order['DeliveryConfirmation'] == 'Only for Priority') {
                                                                                            echo 'selected';
                                                                                        } ?>>Only for Priority</option>
                                                    <option value="All Postage Classes" <?php if (isset($all_order['DeliveryConfirmation']) && $all_order['DeliveryConfirmation'] == 'All Postage Classes') {
                                                                                            echo 'selected';
                                                                                        } ?>>All Postage Classes</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="Min order total for non-Priority Delivery Confirm"><?= _('Min order total for non-Priority Delivery Confirm') ?></label>
                                                <input type="text" class="form-control" id="MinOrderTotalDelivery" name="MinOrderTotalDelivery" placeholder="Min order total for non-Priority Delivery Confirm" data-parsley-required-message="<?= _('Min order total for non-Priority Delivery Confirm') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['MinOrderTotalDelivery']) && !empty($all_order['MinOrderTotalDelivery'])) ? $all_order['MinOrderTotalDelivery'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="Signature Confirmation"><?= _('Signature Confirmation') ?></label>
                                                <select name="SignatureConfirmation" id="SignatureConfirmation" class="browser-default custom-select order_carrier_select">
                                                    <option value="Never" selected><?= _('Never') ?></option>
                                                    <option value="Only for Priority" <?php if (isset($all_order['SignatureConfirmation']) && $all_order['SignatureConfirmation'] == 'Only for Priority') {
                                                                                            echo 'selected';
                                                                                        } ?>>Only for Priority</option>
                                                    <option value="All Postage Classes" <?php if (isset($all_order['SignatureConfirmation']) && $all_order['SignatureConfirmation']  == 'All Postage Classes') {
                                                                                            echo 'selected';
                                                                                        } ?>>All Postage Classes</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="Consolidator Label"><?= _('Consolidator Label') ?></label>
                                                <select name="ConsolidatorLabel" id="ConsolidatorLabel" class="browser-default custom-select order_carrier_select">
                                                    <option value="Never" selected><?= _('Never') ?></option>
                                                    <option value="Only for Standerd" <?php if (isset($all_order['ConsolidatorLabel']) && $all_order['ConsolidatorLabel'] == 'Only for Standerd') {
                                                                                            echo 'selected';
                                                                                        } ?>>Only for Standerd</option>
                                                    <option value="Only for Priority" <?php if (isset($all_order['ConsolidatorLabel']) && $all_order['ConsolidatorLabel'] == 'Only for Priority') {
                                                                                            echo 'selected';
                                                                                        } ?>>Only for Priority</option>
                                                    <option value="Only When Manually Selected" <?php if (isset($all_order['ConsolidatorLabel']) && $all_order['ConsolidatorLabel'] == 'Only When Manually Selected') {
                                                                                                    echo 'selected';
                                                                                                } ?>>Only When Manually Selected</option>
                                                    <option value="All Postage Classes" <?php if (isset($all_order['ConsolidatorLabel']) && $all_order['ConsolidatorLabel'] == 'All Postage Classes') {
                                                                                            echo 'selected';
                                                                                        } ?>>All Postage Classes</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="Include Insurance"><?= _('Include Insurance') ?></label>
                                                <select name="IncludeInsurance" id="IncludeInsurance" class="browser-default custom-select order_carrier_select">
                                                    <option value="Never" selected><?= _('Never') ?></option>
                                                    <option value="Only for Priority" <?php if (isset($all_order['IncludeInsurance']) && $all_order['IncludeInsurance'] == 'Only for Priority') {
                                                                                            echo 'selected';
                                                                                        } ?>>Only for Priority</option>
                                                    <option value="All Postage Classes" <?php if (isset($all_order['IncludeInsurance']) && $all_order['IncludeInsurance'] == 'All Postage Classes') {
                                                                                            echo 'selected';
                                                                                        } ?>>All Postage Classes</option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                            <div class="form-group">
                                                <label for="Min order total to include insurance"><?= _('Min order total to include insurance') ?></label>
                                                <input type="text" class="form-control" id="MinOrderTotalInsurance" name="MinOrderTotalInsurance" placeholder="Enter Min order total to include insurance" data-parsley-required-message="<?= _('Min order total to include insurance') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['MinOrderTotalInsurance']) && !empty($all_order['MinOrderTotalInsurance'])) ? $all_order['MinOrderTotalInsurance'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for=" Round down partial ounces below"><?= _(' Round down partial ounces below') ?></label>
                                                <select name="RoundDownPartial" id="RoundDownPartial" class="browser-default custom-select order_carrier_select">
                                                    <option value="0" selected><?= _('0') ?></option>
                                                    <option value="0.1" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.1') {
                                                                            echo 'selected';
                                                                        } ?>>0.1</option>
                                                    <option value="0.2" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.2') {
                                                                            echo 'selected';
                                                                        } ?>>0.2</option>
                                                    <option value="0.3" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.3') {
                                                                            echo 'selected';
                                                                        } ?>>0.3</option>
                                                    <option value="0.4" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.4') {
                                                                            echo 'selected';
                                                                        } ?>>0.4</option>
                                                    <option value="0.5" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.5') {
                                                                            echo 'selected';
                                                                        } ?>>0.5</option>
                                                    <option value="0.6" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.6') {
                                                                            echo 'selected';
                                                                        } ?>>0.6</option>
                                                    <option value="0.7" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.7') {
                                                                            echo 'selected';
                                                                        } ?>>0.7</option>
                                                    <option value="0.8" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.8') {
                                                                            echo 'selected';
                                                                        } ?>>0.8</option>
                                                    <option value="0.9" <?php if (isset($all_order['RoundDownPartial']) && $all_order['RoundDownPartial'] == '0.9') {
                                                                            echo 'selected';
                                                                        } ?>>0.9</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for=" Estimate postage from Zip code"><?= _(' Estimate postage from Zip code') ?></label>
                                                <input type="text" class="form-control" id="EstimatePostage" name="EstimatePostage" placeholder="Estimate postage from Zip code" data-parsley-required-message="<?= _(' Estimate postage from Zip code') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['EstimatePostage']) && !empty($all_order['EstimatePostage'])) ? $all_order['EstimatePostage'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for=" Max postage batch size (# of orders)"><?= _(' Max postage batch size (# of orders)') ?></label>
                                                <input type="text" class="form-control" id="MaxPostageBatch" name="MaxPostageBatch" placeholder="Max postage batch size (# of orders)" data-parsley-required-message="<?= _(' Max postage batch size (# of orders)') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['MaxPostageBatch']) && !empty($all_order['MaxPostageBatch'])) ? $all_order['MaxPostageBatch'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for=" Customs Signer"><?= _(' Customs Signer') ?></label>
                                                <input type="text" class="form-control" id="CustomsSigner" name="CustomsSigner" placeholder="Enter Customs Signer" data-parsley-required-message="<?= _(' Customs Signer') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['CustomsSigner']) && !empty($all_order['CustomsSigner'])) ? $all_order['CustomsSigner'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="  Default Weight"><?= _('  Default Weight') ?></label>
                                                <input type="text" class="form-control" id="DefaultWeight" name="DefaultWeight" placeholder="Enter  Default Weight" data-parsley-required-message="<?= _('  Default Weight') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($all_order['DefaultWeight']) && !empty($all_order['DefaultWeight'])) ? $all_order['DefaultWeight'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" id="FlatRatePriority" name="FlatRatePriority" data-parsley-group="fieldset01" value="<?php echo (isset($all_order['FlatRatePriority']) && $all_order['FlatRatePriority'] == 1) ? 1 : 0; ?>" <?php echo (isset($all_order['FlatRatePriority']) && $all_order['FlatRatePriority'] == 1) ? 'checked' : ''; ?>> Default Priority to Flat Rate</label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" id="GlobalWeight" name="GlobalWeight" data-parsley-group="fieldset01" value="<?php echo (isset($all_order['GlobalWeight']) && $all_order['GlobalWeight'] == 1) ? 1 : 0; ?>" <?php echo (isset($all_order['GlobalWeight']) && $all_order['GlobalWeight'] == 1) ? 'checked' : ''; ?>> Do not use global weight estimates
                                                </label>
                                            </div>
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div> <!-- Card Body -->
                        </div> <!-- .card card-fluid ends -->
                    </div> <!-- .card-deck-xl ends -->
                </form>
            </div> <!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/javascript/pages/postage-setting.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?= $this->stop() ?>