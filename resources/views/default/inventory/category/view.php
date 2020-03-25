<?php
$title_meta = 'Product Listing for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Listing for your Tracksz Store, a Multiple Market Product Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
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
                    <h1 class="page-title"> <?=_('Category Listing')?> </h1>
                </div>
                <p class="text-muted"> <?=_('This is where you can modify, and delete Category for the current Active Store: ')?><strong> <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <?php //if (isset($stores) && is_array($stores) &&  count($stores)> 0): ?>
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=_('Categories')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <table id="category_table" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                            <tr>
                                <!-- <th>Select <i class="fa fa-question-circle" title="<?=_('Select Active Store.  Click the Shopping Cart in the store\'s row to make that store the current active store. All actions you take in administration applies to the current active store.')?>" data-toggle="tooltip" data-placement="right"></i></th> -->
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <!-- <th>Parent Category Name</th>
                                <th>Status</th>                                 -->
                                <th>Action</th>
                            </tr>
                            </thead>
                            <!-- <tfoot>
                                    <tr>
                                        <th style="width:20%">Chassis Number</th>
                                         <th style="width:20%">Customer Name</th>
                                         <th style="width:20%">Policy Number</th>
                                         <th style="width:20%">vehicle_details</th>
                                         <th style="width:15%">View</th>
                                    </tr>
                                </tfoot>                            -->
                        </table>
                    </div>
                </div>
            <?php //endif; ?>
            <a href="/Product/add" class="btn btn-sm btn-primary" title="<?=_('Add Product Item to Your Active Store')?>"><?=_('Add Product Item')?></a>
            <?php if (isset($deleted) &&  count($deleted)> 0): ?>
                <p></p><a href="#" data-toggle="collapse" data-target="#deleted-stores" title="<?=_('View Deleted Stores')?>"><small><?=_('View Deleted Stores.')?></small></a></p>
                <!-- .card -->
                <div id="deleted-stores" class="collapse card card-fluid">
                    <h6 class="card-header"><?=_('Deleted Stores')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                            <tr>
                                <th>Legal Name</th>
                                <th>Display Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($deleted as $store): ?>
                                <tr>
                                    <td><?=$store['LegalName']?></td>
                                    <td><?=$store['DisplayName']?></td>
                                    <td><?=$store['Address1']?></td>
                                    <td><?=$store['City']?></td>
                                    <td><?=$store['ContactFullName']?></td>
                                    <td class="align-middle text-left">
                                        <a href="#" data-toggle="modal" data-target="#restoreStoreModal" data-url="/account/store/restore/<?=$store['Id']?>" class="btn btn-sm btn-icon btn-secondary" id="restore-store-<?=$store['Id']?>" title="<?=_('Restore this Store.')?>" onclick="restoreStore(this)"><i class="fa fa-undo"  data-toggle="tooltip" data-placement="top" title="<?=_('Restore this Store')?>"></i> <span class="sr-only"><?=_('Restore')?></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/pace/pace.min.js"></script>
<script src="/stacked-menu/stacked-menu.min.js"></script>
<script src="/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/pages/category.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<?=$this->stop()?>

