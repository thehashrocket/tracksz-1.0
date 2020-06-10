<?php
$title_meta = 'Inventory Export for Your Store Product Listings, a Multiple Market Inventory Management Service';
$description_meta = 'Inventory Export for your store\'s product listings at Tracksz, a Multiple Market Inventory Management Service';
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
                                <a href="/inventory/browse" title="Browse Store's Inventory"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Export') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header">Inventory Export</h6><!-- .card-body -->
                    <br>
                    <div class="col-lg-6">

                        <form name="inventory_csv_update" id="inventory_csv_update" action="/inventory/fileexport" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <!-- form starts -->
                            <div class="form-group">
                                <label for="Market-Specific"><?= _('Market-Specific Data') ?></label>
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
                                <label for="Export-Format"><?= _('Export Format') ?></label>
                                <select name="export_format" id="export_format" class="browser-default custom-select market_stores_select">
                                    <option value="" selected><?= _('Select Export Format...') ?></option>
                                    <option value="xlsx">Xlsx</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Request Report</button>

                        </form> <!-- form ends -->
                    </div>

                    <br>
                    </form>
                </div><!-- /.card -->
            </div> <!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?= $this->stop() ?>
