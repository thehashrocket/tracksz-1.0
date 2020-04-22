<?php
$title_meta = 'Customer Group Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Customer Group Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/customergroup/page" title="Inventory Categories"><?= ('Customer Groups') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Customer Group Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Customer Group for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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

                            <form name="customergroup_market_request" id="customergroup_market_request" action="/customergroup/insert_customergroup" method="POST" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="CustomerGroupName">Name</label>
                                                <input type="text" class="form-control" id="CustomerGroupName" name="CustomerGroupName" placeholder="Enter Customer Group Name" data-parsley-required-message="<?= _('Enter Customer Group Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['CustomerGroupName']) && !empty($form['CustomerGroupName'])) ? $form['CustomerGroupName'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="CustomerGroupDescription"><?= _('Description') ?></label>
                                                <textarea class="form-control" id="CustomerGroupDescription" name="CustomerGroupDescription" rows="3"><?php echo (isset($form['CustomerGroupDescription']) && !empty($form['CustomerGroupDescription'])) ? $form['CustomerGroupDescription'] : ''; ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="CustomerGroupApproval" id="CustomerGroupApproval" data-parsley-required-message="<?= _('Customer Group Approval') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['CustomerGroupApproval']) && $form['CustomerGroupApproval'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['CustomerGroupApproval']) && $form['CustomerGroupApproval'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="CustomerGroupApproval">
                                                        <?= _('Approval') ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="SortOrder">Sort Order</label>
                                                <input type="number" class="form-control" id="SortOrder" data-parsley-minvalue="0" min="0" name="SortOrder" placeholder="Enter Sort Order" data-parsley-required-message="<?= _('Enter Customer Group Value') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['SortOrder']) && !empty($form['SortOrder'])) ? $form['SortOrder'] : ''; ?>">
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
<script src="/assets/javascript/pages/customergroup.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>