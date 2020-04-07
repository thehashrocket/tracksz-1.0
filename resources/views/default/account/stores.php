<?php
$title_meta = 'Member Stores at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Member Stores at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('header_extras')?>
<link rel="stylesheet" href="/assets/vendor/datatables/extensions/responsive/responsive.bootstrap4.min.css"><!-- END PLUGINS STYLES -->
<?=$this->stop()?>

<?=$this->start('page_content')?>
<!-- .wrapper -->
<div class="wrapper">
    <!-- .page -->
    <div class="page">
        <!-- .page-inner -->
        <div class="page-inner">
            <header class="page-title-bar">
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Stores')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?=_('Store Information')?> </h1>
                <p class="text-muted"> <?=_('Stores are where you maintain your inventory and where you receive your orders from the marketplaces a store uses. This feature is useful if you have multiple retail locations and want to keep them separate or several different retail locations under various names.')?></p>
                <p class="text-muted"><?=_('Each store is unique with it\'s own inventory, orders, marketplaces, and monthly fees. You may define as many Stores that you need but you must have at least one.')?></p>
                <p class="text-muted"><?=_('<strong>You must have an active store selected to work on marketplaces, inventory, orders, and so on.</strong>')?></p><!-- /title -->
                <a href="/account/store" class="btn btn-sm btn-primary" title="<?=_('Add Store to Your Tracksz Account')?>"><?=_('Add Store')?></a>
                <?php if (is_array($cards) &&  count($cards)<=0): ?>
                    <div class="col-sm-12 alert alert-warning text-center"><?=_('You have not defined any <strong>Payment Cards</strong> in the Member Account area. You may still add and edit your Stores.  However, a Payment Card <u>must</u> be attached to a store before it\'s first billing date. You may define cards in the <a href="/account/payment">Member Area.')?></a></div>
                <?php endif ?>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
        <?php if (is_array($stores) &&  count($stores)> 0): ?>
            <!-- .card -->
            <div class="card card-fluid">
                <h6 class="card-header"><?=_('Your Stores')?></h6><!-- .card-body -->
                <div class="card-body">
                    <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th>Select <i class="fa fa-question-circle" title="<?=_('Select Active Store.  Click the Shopping Cart in the store\'s row to make that store the current active store. All actions you take in administration applies to the current active store.')?>" data-toggle="tooltip" data-placement="right"></i></th>
                            <th>Legal Name</th>
                            <th>Display Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($stores as $store): ?>
                        <tr>
                            <td>
                            <?php if(Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                                     Delight\Cookie\Cookie::get('tracksz_active_store') != $store['Id']): ?>
                                <a href="#" data-toggle="modal" data-target="#setActiveStoreModal" data-url="/account/store/active/<?=$store['Id']?>" class="btn btn-sm btn-icon btn-secondary" id="active-store-<?=$store['Id']?>" title="<?=_('Click Shopping Cart to make this the current active store. All actions you take in administration applies to the current active store.')?>" onclick="setActiveStore(this)"><i class="fa fa-shopping-cart" data-toggle="tooltip" data-placement="left" title="<?=_('Click Shopping Cart to make this the current active store. All actions you take in administration applies to the current active store.')?>"></i> <span class="sr-only"><?=_('Select Active Store')?></span></a></td>
                            <?php else: ?>
                                &nbsp;&nbsp;<i style="color: green;" class="far fa fa-check" data-toggle="tooltip" data-placement="left" title="<?=_('Active Store')?>"> <span class="sr-only"><?=('Can\'t Delete')?></span>&nbsp;</i>
                            <?php endif; ?>
                            <td><?=$store['LegalName']?></td>
                            <td><?=$store['DisplayName']?></td>
                            <td><?=$store['Address1']?></td>
                            <td><?=$store['ContactFullName']?></td>
                            <td class="align-middle text-left">
                                <a href="/account/store/edit/<?=$store['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="<?=_('Edit this Store')?>"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="<?=_('Edit this Store')?>"></i> <span class="sr-only"><?=_('Edit')?></span></a>
                            <?php if(Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                                Delight\Cookie\Cookie::get('tracksz_active_store') != $store['Id']): ?>
                                <a href="#" data-toggle="modal" data-target="#deleteStoreModal" data-url="/account/store/delete/<?=$store['Id']?>" class="btn btn-sm btn-icon btn-secondary" id="delete-store-<?=$store['Id']?>" title="<?=_('Delete this Store.')?>" onclick="deleteStore(this)"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="top" title="<?=_('Delete this Store')?>"></i> <span class="sr-only"><?=_('Delete')?></span></a>
                            <?php else: ?>
                                &nbsp;&nbsp;<i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Cannot delete active store. Change Active store and then delete.')?>"> <span class="sr-only"><?=('Can\'t Delete')?></span>&nbsp;</i>
                            <?php endif; ?>
                            <?php if($store['TrackszListing'] && !$store['StripeSetup']): ?>
                                <a href="/account/stripe/connect/<?=$store['Id']?>" class="btn btn-warning btn-xs" id="delete-store-<?=$store['Id']?>"  data-toggle="tooltip" data-placement="left" title="<?=_('Stripe Not Connected. Required to list on Tracksz Listing Service')?>"><?=_('Stripe')?></a>
                            <?php elseif($store['TrackszListing']): ?>
                                <a href="/account/stripe/connect/<?=$store['Id']?>" class="btn btn-info btn-xs" id="delete-store-<?=$store['Id']?>"  data-toggle="tooltip" data-placement="left" title="<?=_('Stripe Connected. Review Stripe Page')?>"><?=_('Stripe')?></a>
                            <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <?php if (is_array($deleted) &&  count($deleted)> 0): ?>
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
            <p></p><small><?=_('Please Note: Multiple store discount is 10% off monthly fees for each additional store beyond one.  Discount applied to store(s) with lower fees.')?></small>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>

<!-- .modal -->
<form id="deleteStoreForm" name="deleteStoreForm" method="post">
    <div class="modal fade" id="deleteStoreModal" tabindex="-1" role="dialog" aria-labelledby="deleteStoreModalLabel" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog" role="document">
            <!-- .modal-content -->
            <div class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h6 id="deleteStoreModalLabel" class="modal-title inline-editable">
                        <?=('Delete This Address')?>
                    </h6>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <p class="mb-4 text-muted-dark"><?=_('Are you sure you want to delete this store?')?></p>
                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=_('Delete')?></button> <button type="button" class="btn btn-light" data-dismiss="modal"><?=_('Cancel')?></button>
                </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form><!-- /.modal -->
<!-- .modal -->
<form id="restoreStoreForm" name="restoreStoreForm" method="post">
    <div class="modal fade" id="restoreStoreModal" tabindex="-1" role="dialog" aria-labelledby="restoreStoreModalLabel" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog" role="document">
            <!-- .modal-content -->
            <div class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h6 id="restoreStoreModalLabel" class="modal-title inline-editable">
                        <?=('Re-Open/Restore This Store')?>
                    </h6>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <p class="mb-4 text-muted-dark"><?=_('Are you sure you want to re-open/restore this store?')?></p>
                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=_('Restore')?></button> <button type="button" class="btn btn-light" data-dismiss="modal"><?=_('Cancel')?></button>
                </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form><!-- /.modal -->
<!-- .modal -->
<form id="setActiveStoreForm" name="setActiveStoreForm" method="post">
    <div class="modal fade" id="setActiveStoreModal" tabindex="-1" role="dialog" aria-labelledby="setActiveStoreModalLabel" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog" role="document">
            <!-- .modal-content -->
            <div class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h6 id="setActiveStoreModalLabel" class="modal-title inline-editable">
                        <?=('Make Active Store')?>
                    </h6>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <p class="mb-4 text-muted-dark"><?=_('Are you sure you want to make this your "Active" store?')?></p>
                    <p class="mb-4 text-muted-dark"><?=_('Remember, all actions taken in administration applies to the current active store.')?></p>
                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=_('Make Active')?></button> <button type="button" class="btn btn-light" data-dismiss="modal"><?=_('Cancel')?></button>
                </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form><!-- /.modal -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/extensions/fixedheader/dataTables.fixedHeader.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/dataTables.responsive.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/responsive.bootstrap4.min.js"></script>
<script src="/assets/javascript/pages/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#stores').DataTable( {
            responsive: true,
            "ordering": false,
            "bPaginate": false,
            "bInfo": false,
        } );
    
        <?php if (is_array($stores) &&  count($stores)> 0): ?>
            new $.fn.dataTable.FixedHeader( table );
        <?php endif; ?>
    });
    
    function deleteStore(el) {
        var url = $('#'+el.id).data("url");
        $('#deleteStoreForm').attr('action', url);
    };

    function restoreStore(el) {
        var url = $('#'+el.id).data("url");
        $('#restoreStoreForm').attr('action', url);
    };

    function setActiveStore(el) {
        var url = $('#'+el.id).data("url");
        $('#setActiveStoreForm').attr('action', url);
    };
</script>
<?php $this->stop() ?>
