<?php
$title_meta = 'Packing Listing for Your Tracksz Store, a Multiple Market Inventory Management Service';
$description_meta = 'Packing Listing for your Tracksz Store, a Multiple Market Inventory Management Service';
?>
<?= $this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta]) ?>

<?= $this->start('page_content') ?>
<style type="text/css">
    label.enter_order_labelk {
        margin-left: -150px;
    }
</style>
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
                                <a href="/order/browse" title="View Store's Orders"><?= ('Orders') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Packing') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?= _('Packing Listing') ?> </h1>
                <p class="text-muted"> <?= _('Packing Listing Page Browse, add, edit, or delete Orders received by the current store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center error_class">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <form name="order_packing" id="order_packing" action="/order/order_packing" method="POST">
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->
                            <div class="container">

                                <div class="row">
                                    <div class="col-sm">
                                        <!-- form starts -->
                                        <div class="form-group" style="width: 150px;">
                                            <label for="OrderSortBy">Sort By</label>
                                            <select name="OrderSortBy" id="OrderSortBy" class="browser-default custom-select market_stores_select">
                                                <option value="" selected="" disabled>Sort By...</option>
                                                <option value="order" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'order'){ echo 'selected' ;} ?>>Order #</option>
                                                <option value="location/sku" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'Olocation/sku'){ echo 'selected' ;} ?>>Location/SKU</option>
                                                <option value="zipcode" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'zipcode'){ echo 'selected' ;} ?>>ZIP Code</option>
                                                <option value="country/zip" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'country/zip'){ echo 'selected' ;} ?>>Country/ZIP</option>
                                                <option value="author" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'author'){ echo 'selected' ;} ?>>Author</option>
                                                <option value="title" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'title'){ echo 'selected' ;} ?>>Title</option>
                                                <option value="marketplace/order" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'marketplace/order'){ echo 'selected' ;} ?>>Marketplace/Order #</option>
                                                <option value="shippingmethod" <?php
                                                 if(isset($pdf_parameter['SortOrders']) && $pdf_parameter['SortOrders'] == 'shippingmethod'){ echo 'selected' ;} ?>>Shipping Method</option>
                                            </select>
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm">
                                        <!-- form starts -->
                                        <div class="form-group" style="width: 180px;">
                                            <label for="OrderLayout">Packing Slip Version</label>
                                            <select name="OrderLayout" id="OrderLayout" class="browser-default custom-select order_layout_select">
                                                <option value="" selected="" disabled>Packing Slip Version...</option>
                                                <option value="full" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == 'full'){ echo 'selected' ;} ?>>Full</option>
                                                <option value="small" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == 'small'){ echo 'selected' ;} ?>>Small</option>
                                                <option value="self-sticklabel" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == 'self-sticklabel'){ echo 'selected' ;} ?>>Self-Stick Labels</option>
                                                <option value="92mmfold" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == '92mmfold'){ echo 'selected' ;} ?>>92mm Fold</option>
                                                <option value="mailingslip" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == 'mailingslip'){ echo 'selected' ;} ?>>Mailing Slip</option>
                                                <option value="integrated label" <?php
                                                 if(isset($pdf_parameter['DefaultTemplate']) && $pdf_parameter['DefaultTemplate'] == 'integratedlabel'){ echo 'selected' ;} ?>>Integrated Label</option>
                                            </select>
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm">
                                        <label for="enter_order_labelk" class="enter_order_labelk">Enter Order</label>
                                        <div class="form-group enter_order" style="width: 150px;float: left;display: -webkit-inline-box; padding-top: 27px;">

                                            <input type="text" class="form-control" id="OrderID" name="OrderID" placeholder="Enter Order #" value=""> &nbsp; <a href="#" class="btn btn-primary">Go To Order</a>
                                        </div>
                                    </div> <!-- col-sm -->
                                </div> <!-- Row -->

                            </div> <!-- Container -->
                        </div> <!-- Card Body -->
                    </div> <!-- .card card-fluid ends -->
                </div>

                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->
                            <div class="container">
                                <div class="row">
                                    <button type="button" class="btn btn-primary btn_packing_download">Download PDF</button> &nbsp;<button type="button" class="btn btn-primary btn_packing_view">View PDF</button>
                                </div>
                            </div> <!-- Container -->
                        </div> <!-- Card Body -->
                    </div> <!-- .card card-fluid ends -->
                </div>

            </form>

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<!-- <script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script> -->
<script src="/assets/javascript/pages/orderpacking.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
