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
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?= ('Dashboard') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/inventory/browse" title="View Store's Orders"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Browse') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?= _('Product Listing') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can modify, and delete Product for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
<div class="card-deck-xl">
                <!-- .card-deck-xl starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <div class="container">
                            <form name="product_filter" id="product_filter" action="/inventory/filter_inventory" method="POST"> 
                           
                                <div class="row">
                                    <div class="col-sm">
                                        <h5 class="card-title"><?= _('Search') ?></h5>
                                        <!-- form starts -->
                                        <div class="form-group">
                                            <label for="SKU"><?= _('SKU') ?></label>
                                            <input type="text" class="form-control" id="SKU" name="SKU" placeholder="Enter SKU" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="Title"><?= _('Title') ?></label>
                                            <input type="text" class="form-control" id="EbayTitle" name="EbayTitle" placeholder="Enter Title" value="">
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm" style="margin-top: 2.1rem;">
                                        <!-- form starts -->
                                        <div class="form-group">
                                            <label for="ISBN"><?= _('Product Id') ?></label>
                                            <input type="text" class="form-control" id="ProdId" name="ProdId" placeholder="Enter Product Id" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="Author"><?= _('Note') ?></label>
                                            <input type="text" class="form-control" id="Notes" name="Notes" placeholder="Enter Notes" value="">
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm" style="margin-top: 2.1rem;">
                                        <!-- form starts -->
                                        <div class="form-group">
                                            <label for="Order"><?= _('Price') ?></label>
                                            <input type="text" class="form-control" id="BasePrice" name="BasePrice" placeholder="Enter BasePrice" value="">

                                        </div>
                                        <div class="form-group">
                                            <label for="Customer"><?= _('Condition') ?></label>
                                           
                                            <select id="ProdCondition" name="ProdCondition" class="form-control">
                                                <option value="all">All Condition</option>
                                          <?php foreach($getProdcondition as $getProd)
                                            { ?>
                                                <option value="<?php echo $getProd['ProdCondition'];?>"><?php echo $getProd['ProdCondition'];?></option>
                                            <?php } ?>
                                            </select>
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm" style="margin-top: 2.1rem;">
                                        <!-- form starts -->
                                        <div class="form-group">
                                            <label for="Location"><?= _('Location:') ?></label>
                                            <input type="text" class="form-control" id="Location" name="Location" placeholder="Enter Location" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="Note"><?= _('Display') ?></label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="all">All Display</option>
                                                <option value="1">Active</option>
                                                <option value="0">In Active</option>
                                            </select>
                                        </div>
                                        <input type='hidden' id='clear_filter' name='clear_filter' value=''>
                                    </div> <!-- col-sm -->
                                </div> <!-- Row -->
                                <button type="submit" class="btn btn-primary" >Submit</button>
                                <button type="submit" class="btn btn-primary btn_clear">Clear</button>
                            </form>
                        </div> <!-- Container -->
                    </div> <!-- Card Body -->
                </div> <!-- .card card-fluid ends -->
            </div>

                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <form></form>
                        <form name="change_marketplace" id="change_marketplace" action="/product/change_marketplace" method="POST">
                            <a href="/product/add" class="btn btn-primary" title="<?= _('Add Product') ?>"><?= _('Add Product') ?></a>
                            <div class="btn-group"><button type="button" id="btn_delete" class="btn btn-danger">Delete Selected Items</button></div>
                            <select class="browser-default custom-select" id="selected_export_product" name="export_format" required="" style="width: 150px;">
                                <option value="" selected disabled><?= _('Selected Export') ?></option>
                                <option value="xlsx">Xlsx</option>
                                <option value="csv">CSV</option>
                            </select>

                            <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select" style="width: 200px;">
                                <option value="null" selected disabled><?= _('Select Marketplace...') ?></option>
                                <?php
                                if (isset($market_places) && !empty($market_places)) {
                                    foreach ($market_places as $mar_key => $mar_val) { ?>
                                        <option value="<?php echo $mar_val['Id']; ?>"><?php echo $mar_val['MarketName']; ?></option>
                                    <?php }
                                } else { ?>
                                    <option selected><?= _('No Marketplace found...') ?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">

                        </div>
                    </div> -->
                    <div class="col-md-3">
                        <div class="form-group">

                        </div>
                    </div>
                </div>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <!--Drop search Box-->




                <!--End search-->
                <!-- .page-section starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <?php

                        if (is_array($all_product) &&  count($all_product) > 0) : ?>
                            <table id="product_table" name="product_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="select_all_chkbox" class="select_all_chkbox" value="0" id="select_all_chkbox"></th>
                                        <th><?= _('SKU') ?></th>
                                        <th><?= _('Product ID') ?></th>
                                        <th><?= _('QTY') ?></th>
                                        <th><?= _('Condition') ?></th>
                                        <th><?= _('Note') ?></th>
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
                                            <td><input type="checkbox" class="child_chkbox" name="child_chkbox[]" value="<?php echo $product['Id']; ?>"></td>
                                            <td><?= $product['SKU'] ?></td>
                                            <td><?= $product['ProdId'] ?></td>
                                            <td><?= $product['Qty'] ?></td>
                                            <td><?= $product['ProdCondition'] ?></td>
                                            <td><?= $product['Notes'] ?></td>
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
                        <?php endif; ?>

                    </div><!-- card-body ends -->
                </div><!-- /.card card-fluid ends -->
            </div><!-- /.page-section ends -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<!-- <script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
<script src="/assets/javascript/pages/product.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
