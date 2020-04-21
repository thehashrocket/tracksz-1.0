<?php
$title_meta = 'Download Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Download Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/download/page" title="Inventory Download"><?= ('Downloads') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Download Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Download for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
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

                            <form name="download_market_request" id="download_market_request" action="/download/insert_download" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="DownloadName">Name</label>
                                                <input type="text" class="form-control" id="DownloadName" name="DownloadName" placeholder="Enter Download Name" data-parsley-required-message="<?= _('Enter Download Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['DownloadName']) && !empty($form['DownloadName'])) ? $form['DownloadName'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="DownloadFilename"><?= _('Filename') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="DownloadFilename" name="DownloadFilename" multiple="" data-parsley-required-message="<?= _('Select File') ?>" data-parsley-group="fieldset01" data-parsley-trigger="change" required> <label class="custom-file-label" for="DownloadFilename"><?= _('Choose file') ?></label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="DownloadMask">Mask</label>
                                                <input type="text" class="form-control" id="DownloadMask" name="DownloadMask" placeholder="Enter Mask" data-parsley-required-message="<?= _('Enter Download Value') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['DownloadMask']) && !empty($form['DownloadMask'])) ? $form['DownloadMask'] : ''; ?>">
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
