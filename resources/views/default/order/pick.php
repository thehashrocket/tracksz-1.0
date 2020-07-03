<?php
$title_meta = 'Pick Listing for Your Tracksz Store, a Multiple Market Inventory Management Service';
$description_meta = 'Pick Listing for your Tracksz Store, a Multiple Market Inventory Management Service';
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
                                <a href="/order/browse" title="View Store's Orders"><?= ('Orders') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Pick') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?= _('Pick Listing') ?> </h1>
                <p class="text-muted"> <?= _('Pick Listing Page Browse, add, edit, or delete Orders received by the current store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <form name="order_pick" id="order_pick" action="/order/order_pick" method="POST">
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->
                            <div class="container">
                                <form></form>
                                <div class="row">
                                    <div class="col-sm">
                                        <!-- form starts -->
                                        <div class="form-group" style="width: 150px;">
                                           <label for="OrderStatus">Order Status</label>
                                            <select name="OrderStatus" id="OrderStatus" class="browser-default custom-select market_stores_select" required="">
                                                <option value="" selected="" disabled>Order Status...</option>
                                                <option value="all">All</option>
                                                <option value="new">New</option>
                                                <option value="packed">In Process</option>
                                                <option value="shipped">Shipped</option>
                                                <option value="unconfirm">Unconfirmed</option>
                                                <option value="deferred">Deferred</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm">
                                        <!-- form starts -->
                                        <div class="form-group" style="width: 150px;">
                                             <label for="OrderSortBy">Sort By</label>
                                            <select name="OrderSortBy" id="OrderSortBy" class="browser-default custom-select market_stores_select">
                                                <option value="" selected="" disabled>Sort By...</option>
                                                <option value="all">All</option>
                                                <option value="new">New</option>
                                            </select>
                                        </div>
                                    </div> <!-- col-sm -->
                                    <div class="col-sm">
                                        <div class="form-group">
                                              <label for="Split_Order"></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="ckb7"> <label class="custom-control-label" for="ckb7"><?= _('Split Order') ?></label>
                                            </div>
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
                                    <button type="button" class="btn btn-primary btn_pick_download">Download PDF</button> &nbsp;<button type="button" class="btn btn-primary btn_pick_view">View PDF</button>
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
<script src="/assets/javascript/pages/orderpick.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
