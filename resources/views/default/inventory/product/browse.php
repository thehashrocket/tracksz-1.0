<?php
$title_meta = 'Product Listing for Your Tracksz Store, a Multiple Marketplace Management Service';
$description_meta = 'Product Listing for your Tracksz Store, a Multiple Marketpalce Management Service';
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
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/inventory/browse" title="Browse Store Inventory"><?=('Inventory')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Browse')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?=_('Inventory Listing')?> </h1>
                <p class="text-muted"> <?=_('Browse, add, edit, or delete Inventory attached to the current store: ')?><strong> <?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?></strong></p>
                <a href="/inventory/add" class="btn btn-sm btn-primary" title="<?=_('Add Inventory Item to Your Active Store')?>"><?=_('Add Inventory Item')?></a>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <?php if (is_array($all_product) &&  count($all_product) > 0) : ?>
                <!-- .page-section starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <table id="product_table" name="product_table" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?= _('Name') ?></th>
                                    <th><?= _('Notes') ?></th>
                                    <th><?= _('SKU') ?></th>
                                    <th><?= _('BasePrice') ?></th>
                                    <th><?= _('Condition') ?></th>
                                    <th><?= _('Image') ?></th>
                                    <th><?= _('Action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($all_product as $product) : ?>
                                    <tr>
                                        <?php
                                        $img_path = '';
                                        $img_path = \App\Library\Config::get('company_url') . '/assets/images/product/' . $product['Image'];
                                        ?>
                                        <td><?= $product['Name'] ?></td>
                                        <td><?= $product['Notes'] ?></td>
                                        <td><?= $product['SKU'] ?></td>
                                        <td><?= $product['BasePrice'] ?></td>
                                        <td><?= $product['ProdCondition'] ?></td>
                                        <td><?= (isset($product['Image']) && !empty($product['Image'])) ? "<img height=50 width=50 src='" . $img_path . "' >" : ""; ?></td>
                                        <td> <?php
                                                $button = '';
                                                $edit_button = '<a href="' . \App\Library\Config::get('company_url') . '/product/edit/' . $product['Id'] . '" class="btn btn-xs btn-warning btn_edit"><i class="far fa-edit"></i> Edit</a> &nbsp;';
                                                $delete_button = '<a href="#delete-' . $product['Id'] . '" delete_id="' . $product['Id'] . '" class="btn btn-xs btn-danger btn_delete"><i class="far fa-trash-alt"></i> Delete</a>';
                                                $button .= $edit_button;
                                                $button .= $delete_button;
                                                echo $button;
                                                ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div><!-- card-body ends -->
                </div><!-- /.card card-fluid ends -->
                <?php endif; ?>
            </div><!-- /.page-section ends -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/javascript/pages/product.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
