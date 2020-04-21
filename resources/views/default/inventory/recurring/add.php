<?php
$title_meta = 'Recurring Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Recurring Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/recurring/page" title="Inventory Recurring"><?= ('Recurring') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Recurring Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Recurring for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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

                            <form name="recurring_market_request" id="recurring_market_request" action="/recurring/insert_recurring" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="RecurringName">Recurring Name</label>
                                                <input type="text" class="form-control" id="RecurringName" name="RecurringName" placeholder="Enter Recurring Name" data-parsley-required-message="<?= _('Enter Recurring Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringName']) && !empty($form['RecurringName'])) ? $form['RecurringName'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="RecurringPrice">Recurring Price</label>
                                                <input type="number" class="form-control" id="RecurringPrice" name="RecurringPrice" placeholder="Enter Recurring Name" data-parsley-required-message="<?= _('Enter Recurring Price') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringPrice']) && !empty($form['RecurringPrice'])) ? $form['RecurringPrice'] : ''; ?>">
                                            </div>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="RecurringFrequency"><?= _('Frequency') ?></label>
                                                </div>
                                                <select class="custom-select" id="RecurringFrequency" name="RecurringFrequency">
                                                    <option value="" selected><?= _('Select Parent Recurring...') ?></option>
                                                    <?php
                                                    if (isset($all_recurring) && !empty($all_recurring)) {
                                                        foreach ($all_recurring as $rec_key => $rec_val) {
                                                    ?>
                                                            <div class="page-section">
                                                                <!-- .page-section starts -->
                                                                <div class="card-deck-xl">
                                                                    <!-- .card-deck-xl starts -->
                                                                    <div class="card card-fluid">
                                                                        <!-- .card card-fluid starts -->
                                                                        <div class="card-body">
                                                                            <!-- .card-body starts -->
                                                                            <option value=" <?php echo $rec_val['name']; ?>" <?php echo (isset($form['RecurringFrequency']) && $form['RecurringFrequency'] == $rec_val['name']) ? 'selected' : ''; ?>><?php echo strtoupper($rec_val['name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Parent Recurring found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringDuration">Duration</label>
                                                <input type="number" class="form-control" id="RecurringDuration" name="RecurringDuration" placeholder="Enter Recurring Duration" data-parsley-required-message="<?= _('Enter Recurring Duration') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringDuration']) && !empty($form['RecurringDuration'])) ? $form['RecurringDuration'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringCycle">Cycle</label>
                                                <input type="number" class="form-control" id="RecurringCycle" name="RecurringCycle" placeholder="Enter Recurring Cycle" data-parsley-required-message="<?= _('Enter Recurring Duration') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringCycle']) && !empty($form['RecurringCycle'])) ? $form['RecurringCycle'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="TrailStatus"><?= _('Trail Status') ?></label>
                                                <select name="TrailStatus" id="TrailStatus" class="browser-default custom-select">
                                                    <option value="0"><?= _('Disable') ?></option>
                                                    <option value="1"><?= _('Enable') ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringTrialPrice">Trial Price</label>
                                                <input type="number" class="form-control" id="RecurringTrialPrice" name="RecurringTrialPrice" placeholder="Enter Trail Price" data-parsley-required-message="<?= _('Enter Recurring Duration') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringTrialPrice']) && !empty($form['RecurringTrialPrice'])) ? $form['RecurringTrialPrice'] : ''; ?>">
                                            </div>


                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="RecurringTrailFrequency"><?= _('Trail Frequency') ?></label>
                                                </div>
                                                <select class="custom-select" id="RecurringTrailFrequency" name="RecurringTrailFrequency">
                                                    <option value="" selected><?= _('Select Parent Recurring...') ?></option>
                                                    <?php
                                                    if (isset($all_recurring) && !empty($all_recurring)) {
                                                        foreach ($all_recurring as $rec_key => $rec_val) {
                                                    ?>
                                                            <div class="page-section">
                                                                <!-- .page-section starts -->
                                                                <div class="card-deck-xl">
                                                                    <!-- .card-deck-xl starts -->
                                                                    <div class="card card-fluid">
                                                                        <!-- .card card-fluid starts -->
                                                                        <div class="card-body">
                                                                            <!-- .card-body starts -->
                                                                            <option value=" <?php echo $rec_val['name']; ?>" <?php echo (isset($form['RecurringFrequency']) && $form['RecurringFrequency'] == $rec_val['name']) ? 'selected' : ''; ?>><?php echo strtoupper($rec_val['name']); ?></option>
                                                                        <?php }
                                                                } else { ?>
                                                                        <option selected><?= _('No Trail Frequency found...') ?></option>
                                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringTrailDuration">Trail Duration</label>
                                                <input type="number" class="form-control" id="RecurringTrailDuration" name="RecurringTrailDuration" placeholder="Enter Trail Duration" data-parsley-required-message="<?= _('Enter Recurring Duration') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringTrailDuration']) && !empty($form['RecurringTrailDuration'])) ? $form['RecurringTrailDuration'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringTrailCycle">Trail Cycle</label>
                                                <input type="number" class="form-control" id="RecurringTrailCycle" name="RecurringTrailCycle" placeholder="Enter Trail Cycle" data-parsley-required-message="<?= _('Enter Recurring Duration') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['RecurringTrailCycle']) && !empty($form['RecurringTrailCycle'])) ? $form['RecurringTrailCycle'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="RecurringStatus"><?= _('Status') ?></label>
                                                <select name="RecurringStatus" id="RecurringStatus" class="browser-default custom-select">
                                                    <option value="0"><?= _('Disable') ?></option>
                                                    <option value="1"><?= _('Enable') ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="SortOrder">Sort Order</label>
                                                <input type="number" class="form-control" id="SortOrder" data-parsley-minvalue="0" min="0" name="SortOrder" placeholder="Enter Sort Order" data-parsley-required-message="<?= _('Enter Attribute Value') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : ''; ?>">
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
