<?php
$title_meta = 'Inventory Listing for Your Tracksz Store, a Multiple Market Inventory Management Service';
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
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?= ('Dashboard') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/inventory/browse" title="View Store's Orders"><?= ('Orders') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Browse') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?= _('Order Listing') ?> </h1>
                <p class="text-muted"> <?= _('Browse, add, edit, or delete Orders received by the current store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <a href="/order/add" class="btn btn-sm btn-primary" title="<?= _('Manually Add an Order for this store.') ?>"><?= _('Add Order') ?></a>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <?php

                        if (isset($all_order) && is_array($all_order) &&  count($all_order) > 0) : ?>
                            <table id="order_table" name="order_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= _('MarketName') ?></th>
                                        <th><?= _('OrderStatus') ?></th>
                                        <th><?= _('Currency') ?></th>
                                        <th><?= _('PaymentStatus') ?></th>
                                        <th><?= _('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_order as $order) : ?>
                                        <tr>
                                            <td><?php echo $order['MarketplaceName']; ?></td>
                                            <td><?php echo $order['OrderStatus']; ?></td>
                                            <td><?php echo $order['OrderCurrency']; ?></td>
                                            <td><?php echo $order['OrderPaymentStatus']; ?></td>
                                            <td> <?php
                                                    $button = '';
                                                    $edit_button = '<a href="' . \App\Library\Config::get('company_url') . '/order/edit/' . $order['OrderId'] . '" class="btn btn-xs btn-warning btn_edit"><i class="far fa-edit"></i> Edit</a> &nbsp;';
                                                    $delete_button = '<a href="#delete-' . $order['OrderId'] . '" delete_id="' . $order['OrderId'] . '" class="btn btn-xs btn-danger btn_delete"><i class="far fa-trash-alt"></i> Delete</a>';
                                                    $button .= $edit_button;
                                                    $button .= $delete_button;
                                                    echo $button;

                                                    ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div> <!-- card-body ends -->
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
<script src="/assets/javascript/pages/order.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
