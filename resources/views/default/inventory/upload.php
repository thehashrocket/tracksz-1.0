<?php
$title_meta = 'Inventory File Upload for Your Tracksz Store, a Multiple Market Inventory Management Service';
$description_meta = 'Inventory Listing for your Tracksz Store, a Multiple Market Inventory Management Service';
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
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?= _('Inventory File Upload') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where upload inventory for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <div id="ajaxMsg"></div>
              <!--  <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?> -->
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <div class="col-lg-12">
                    <!-- .card -->
                    <div class="card">
                        <!-- .card-header -->
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#card-ftpupload"><?= _('FTP Upload') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link show" data-toggle="tab" href="#card-filebrowse"><?= _('File Browse') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link show" data-toggle="tab" href="#card-filedelete"><?= _('Delete Inventory') ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#">Disabled</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <!-- .card-body -->
                        <div class="card-body">
                            <!-- .tab-content -->

                            <div id="myTabCard" class="tab-content">
                                <div class="tab-pane fade active show" id="card-ftpupload">
                                    <h5 class="card-title"> FTP File Upload </h5>
                                    <div class="col-lg-6">

                                        <form name="dropzoneFrom" id="dropzoneFrom" action="/inventory/ftpupload" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                            <!-- form starts -->
                                            <div class="form-group">
                                                <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select">
                                                    <option value="" selected><?= _('Select Marketplace...') ?></option>
                                                    <?php
                                                    if (isset($market_places) && is_array($market_places) && !empty($market_places)) {
                                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val['MarketName']; ?>"><?php echo $mar_val['MarketName']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Marketplace found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="InventoryUpload"><?= _('Inventory File Upload') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="InventoryUpload" name="InventoryUpload" multiple=""> <label class="custom-file-label" for="InventoryUpload" data-parsley-required-message="<?= _('Select Inventory File Upload') ?>" data-parsley-group="fieldset01" required><?= _('Choose file') ?></label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><?= _('Submit') ?> </button>
                                            <a href="<?php echo \App\Library\Config::get('company_url') . '/inventory/queue'; ?>" class="btn btn-warning"><?= _('Queue Sample') ?> </a>

                                        </form> <!-- form ends -->
                                    </div>
                                </div> <!-- col-sm Group Left Ends -->
                                <div class="tab-pane fade" id="card-filebrowse">
                                    <form name="dropzone_request" id="dropzone_request" class="dropzone" action="/inventory/importupload" method="POST" enctype="multipart/form-data">

                                    </form>
                                    <br />
                                    <br />
                                    <br />
                                    <!-- <button type="submit" class="btn btn-primary" id="submit-all">Upload</button> -->
                                </div>
                                <div class="tab-pane fade" id="card-filedelete">
                                    <a href="<?php echo \App\Library\Config::get('company_url') . '/assets/inventory/InventoryRemove.csv' ?>"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;&nbsp;<?= _('Sample File') ?></a>
                                    <form name="dropzone_request" id="dropzone_request" class="dropzone" action="/inventory/importdelete" method="POST" enctype="multipart/form-data">

                                    </form>
                                    <br />
                                    <br />
                                    <br />
                                    <!-- <button type="submit" class="btn btn-primary" id="submit-all">Upload</button> -->
                                </div>

                                <div class="col-sm mt-3 pt-3">
                                    <!-- col-sm Group Right Starts -->
                                </div> <!-- col-sm Group Right Ends -->
                            </div> <!-- Row Ends -->
                        </div>
                    </div><!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div>
    </div> <!-- .page-section ends -->


</div><!-- /.page-inner -->
</div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/javascript/pages/inventory-upload.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
