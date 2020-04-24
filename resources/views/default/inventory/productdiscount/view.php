<?php
$title_meta = 'Product Discount Listing for Your Tracksz Store, a Multiple Marketplace Management Service';
$description_meta = 'Product Discount Listing for your Tracksz Store, a Multiple Marketpalce Management Service';
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
                    <h1 class="page-title"> <?= _('Product Discount Listing') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can modify, and delete Product Discount for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
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
                        <?php

                        if (isset($all_productdiscount) && is_array($all_productdiscount) &&  count($all_productdiscount) > 0) : ?>
                            <table id="productdiscount_table" name="productdiscount_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= _('Product') ?></th>
                                        <th><?= _('CustomerGroup') ?></th>
                                        <th><?= _('Priority') ?></th>
                                        <th><?= _('Price') ?></th>
                                        <th><?= _('Date Start') ?></th>
                                        <th><?= _('Date End') ?></th>
                                        <th><?= _('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($all_productdiscount as $productdiscount) : ?>
                                        <tr>
                                            <td><?= $productdiscount['ProdName'] ?></td>
                                            <td><?= $productdiscount['CustGroupName'] ?></td>
                                            <td><?= $productdiscount['ProdPriority'] ?></td>
                                            <td><?= $productdiscount['ProdPrice'] ?></td>
                                            <td><?= date("d-m-Y", strtotime($productdiscount['ProdStartDate'])) ?></td>
                                            <td><?= date("d-m-Y", strtotime($productdiscount['ProdEndDate'])) ?></td>
                                            <td> <?php
                                                    $button = '';
                                                    $edit_button = '<a href="' . \App\Library\Config::get('company_url') . '/productdiscount/edit/' . $productdiscount['ProdDiscId'] . '" class="btn btn-xs btn-warning btn_edit"><i class="far fa-edit"></i> Edit</a> &nbsp;';
                                                    $delete_button = '<a href="#delete-' . $productdiscount['ProdDiscId'] . '" delete_id="' . $productdiscount['ProdDiscId'] . '" class="btn btn-xs btn-danger btn_delete"><i class="far fa-trash-alt"></i> Delete</a>';
                                                    $button .= $edit_button;
                                                    $button .= $delete_button;
                                                    echo $button;
                                                    ?></td>
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
<script src="/assets/javascript/pages/productdiscount.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>