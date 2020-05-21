<?php
$title_meta = 'Order Defaults for Your Store Product Listings, a Multiple Market Order Management Service';
$description_meta = 'Order Defaults for your store\'s product listings at Tracksz, a Multiple Market Order Management Service';
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
                            <li class="breadcrumb-item active"><?= ('Export Order') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Export Order') ?> </h1>
                <p class="text-muted"> <?= _('Configure default settings for your Active Store: ') ?><strong> <?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
               
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" />
 <form name="order_csv_update" id="order_csv_update" action="/order/export-order-data"
                        method="POST" enctype="multipart/form-data" data-parsley-validate>
            <div class="page-section">
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header">Export Order</h6><!-- .card-body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <label>Select export type:</label>
                                <select class="form-control" id="exportType" name="exportType">
                                    <option label="New" value="current">New Since Last Export</option>
                                    <option label="By Date" value="range">By Date</option>
                                    <option label="By Status" value="status">By Status</option>
                                    <option label="All Orders" value="all">All Orders</option>
                                 </select>
                             </div>
                             <div class="col-md-4">
                             </div>
                        </div>
                        <br>

                       <div class="row input-daterange">
            <div class="col-md-2 date_range">
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly required="" style="display: none;" />
            </div>
            <div class="col-md-2 date_range">
                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly required="" style="display: none;" />
            </div>
            <div class="col-md-4" id="current_time">
                <input type="checkbox" name="current" value="1" checked=""> Update Last Export Time on Request
            </div>
            <div class="col-md-4 orderStatus" id="orderStatus" style="display: none;">
                <select class="form-control"  name="orderStatus">
                                                    
                        <option value="all">All</option>
                    
                        <option value="new">New</option>
                    
                        <option value="packed">In Process</option>
                    
                        <option value="shipped">Shipped</option>
                    
                        <option value="unconfirm">Unconfirmed</option>
                    
                        <option value="deferred">Deferred</option>
                    
                        <option value="cancelled">Cancelled</option>
                    
                </select>
            </div>
             <div class="col-md-4">
            <div class="form-group">
                  <select class="form-control" id="export_format" name="export_format" required="">
                     <option value="" selected><?= _('Select Export Format...') ?></option>
                                                    <option value="xlsx">Xlsx</option>
                                                    <option value="csv">CSV</option>
                  </select>
                 </div>
              </div>
            <div class="col-md-4">
                <button type="submit" name="export" id="export" class="btn btn-primary">Export</button>
               
            </div>
        </div>






                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div> <!-- /.page-section -->
        </form>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
        
    <script type="text/javascript">
        $(document).ready(function() {
             $('.input-daterange').datepicker({
                 format: 'yyyy-mm-dd',
              });
        });
    </script>

<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?= $this->stop() ?>