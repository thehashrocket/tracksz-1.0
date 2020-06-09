<?php
$title_meta = 'Order Confirm Files for Your Store Product Listings, a Multiple Market Order Management Service';
$description_meta = 'Order Confirm Files for your store\'s product listings at Tracksz, a Multiple Market Order Management Service';
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
                            <li class="breadcrumb-item active"><?= ('Confirmation Files') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Confirmation Files') ?> </h1>
                <p class="text-muted"> <?= _('Configure default settings for your Active Store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <div id="ajaxMsg"></div>
                <?php if (isset($alert) && $alert) : ?>
                    <!-- <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div> -->
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"> <?= _('Confirmation Files') ?></h6><!-- .card-body -->
                    <div class="card-body">
                        <div id="card-filebrowse">
                            <form name="dropzone_request" id="dropzone_request" class="dropzone" action="/order/confirmation_files" method="POST" enctype="multipart/form-data">

                            </form>
                            <br />
                            <br />
                            <br />
                            <!-- <button type="submit" class="btn btn-primary" id="submit-all">Upload</button> -->
                        </div>
                    </div><!-- /.card-body -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <?php

                        if (isset($all_order) && is_array($all_order) &&  count($all_order) > 0) : ?>
                            <table id="confirm_table" name="confirm_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= _('File ID') ?></th>
                                        <th><?= _('File Name') ?></th>
                                        <th><?= _('Upload Date') ?></th>
                                        <th><?= _('Status') ?></th>
                                        <th><?= _('Report') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($all_order as $order) : ?>
                                        <tr>
                                            <td><?php echo $order['OrderId']; ?></td>
                                            <td><?php echo $order['FileName']; ?></td>
                                            <td><?php echo $order['UploadDate']; ?></td>
                                            <td><?php echo (isset($order['Status']) && $order['Status'] == 1) ? 'Done' : 'Fail'; ?></td>
                                            <td> <?php
                                                    $button = '';
                                                    $download = '<a href="#" class="btn btn-xs btn-warning btn_download" id="' . $order['Id'] . '"><i class="fas fa-download"></i> Download</a> &nbsp;';
                                                    $view_button = '<a href="#view-' . $order['Id'] . '" view_id="' . $order['Id'] . '" class="btn btn-xs btn-danger btn_view"><i class="fas fa-file-code"></i> View</a>';
                                                    $button .= $download;
                                                    $button .= $view_button;
                                                    echo $button;

                                                    ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div> <!-- card-body ends -->
                </div><!-- /.card -->
            </div> <!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/javascript/pages/confirmationfiles.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?= $this->stop() ?>
