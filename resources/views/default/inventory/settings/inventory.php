<?php
$title_meta = 'Inventory Settings for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Inventory Settings for your Tracksz Store, a Multiple Market Product Management Service';
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
                            <li class="breadcrumb-item active"><?= ('Settings') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Inventory Settings') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can update Inventory Settings for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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

                            <form name="inventory_settings_request" id="inventory_settings_request" action="/inventory/update_settings" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="FileName"><?= _('Inventory Upload') ?></label>
                                                </div>
                                                <select class="custom-select" id="FileName" name="FileName">
                                                    <option value=""><?= _('Select File Type...') ?></option>
                                                    <option value="uiee" <?php echo (isset($all_settings['FileType']) && $all_settings['FileType'] == 'uiee') ? 'selected' : '' ?>><?= _('UIEE') ?></option>
                                                    <option value="csv" <?php echo (isset($all_settings['FileType']) && $all_settings['FileType'] == 'csv') ? 'selected' : '' ?>><?= _('CSV') ?></option>
                                                    <option value="xlsx" <?php echo (isset($all_settings['FileType']) && $all_settings['FileType'] == 'xlsx') ? 'selected' : '' ?>><?= _('Excel') ?></option>
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
