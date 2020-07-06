<?php
$title_meta = 'Marketplace Listing for Your Tracksz Store, a Multiple Marketplace Management Service';
$description_meta = 'Marketplace Listing for your Tracksz Store, a Multiple Marketpalce Management Service';
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
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?= _('Marketplace Listing') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can modify, and delete Marketplace for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <a href="/marketplace/dashboard" class="btn btn-sm btn-primary" title="<?= _('Add Marketplace') ?>"><?= _('Add Marketplace') ?></a>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <?php if (is_array($marketplace) &&  count($marketplace) > 0) : ?>
                            <table id="marketplace_table" name="marketplace_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= _('MarketName') ?></th>
                                        <th><?= _('EmailAddress') ?></th>
                                        <th><?= _('SellerID') ?></th>
                                        <th><?= _('FtpUserId') ?></th>
                                        <th><?= _('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($marketplace as $market) : ?>
                                        <tr>
                                            <td><?= $market['MarketName'] ?></td>
                                            <td><?= $market['EmailAddress'] ?></td>
                                            <td><?= $market['SellerID'] ?></td>
                                            <td><?= $market['FtpUserId'] ?></td>
                                            <td>
                                             <?php
                                                    $button = '';
                                                    $edit_button = '<a href="' . \App\Library\Config::get('company_url') . '/marketplace/edit/' . $market['Id'] . '" class="btn btn-sm btn-icon btn-secondary btn_edit" title="edit"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="Edit this Marketplace"></i></a> &nbsp;';
                                                    $delete_button = '<a href="#delete-' . $market['Id'] . '" delete_id="' . $market['Id'] . '" class="btn btn-sm btn-icon btn-danger btn_delete" title="delete"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="left" title="Delete this Marketplace"></i> </a>';
                                                    $button .= $edit_button;
                                                    $button .= $delete_button;
                                                    echo $button;

                                                    ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div><!-- card-body ends --> 
                </div><!-- /.card card-fluid ends --> 
             </div><!-- /.page-section ends -->  

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
<?= $this->stop() ?>
