<?php
$title_meta = 'Shipping Zones at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Shipping Zones at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
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
                            <li class="breadcrumb-item">
                                <a href="/account/stores" title="Tracksz Member's Stores"><?=('Stores')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Shipping Zones')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?=_('Shipping Zones')?> </h1>
                </div>
                <p class="text-muted"> <?=_('Here you can manage shipping zones and assign shipping methods to them.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
        <?php if (is_array($shippingZones) &&  count($shippingZones)> 0): ?>
            <!-- .card -->
            <div class="card card-fluid">
                <h6 class="card-header"><?=_('Shipping Zones')?></h6><!-- .card-body -->
                <div class="card-body">
                    <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($shippingZones as $shippingZone): ?>
                            <tr>
                                <td width="60%"><?=$shippingZone['Name']?></td>
                                <td class="align-middle text-left">
                                    <a href="/account/shipping-zones/manage/<?=$shippingZone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Assign Shipping Methods."><i class="fa fa-cog" data-toggle="tooltip" data-placement="left" title="" data-original-title="Assign Shipping Methods."></i> <span class="sr-only">Assign</span></a>
                                    <a href="/account/shipping-zones/edit/<?=$shippingZone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Edit this Shipping Zone."><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="" data-original-title="Edit this Shipping Zone."></i> <span class="sr-only">Edit</span></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteZone-<?=$shippingZone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Delete this Shipping Zone."><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete this Shipping Zone."></i> <span class="sr-only">Delete</span></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <a href="/account/shipping-zones/add" class="btn btn-sm btn-primary" title="<?=_('Add a Shipping Zone')?>"><?=_('Add Shipping Zone')?></a>
        </div><!-- /.page-inner -->

        <!-- Modals to delete shipping methods -->
        <?php foreach($shippingZones as $shippingZone): ?>
            <div class="modal fade" id="deleteZone-<?=$shippingZone['Id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Shipping Zone</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this shipping zone?</div>
                    <div class="modal-footer">
                        <form action="/account/shipping-zones/delete/<?=$shippingZone['Id']?>" method="POST">
                            <button type="submit" class="btn btn-primary">Delete</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div><!-- /.page -->
</div>
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/extensions/fixedheader/dataTables.fixedHeader.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/dataTables.responsive.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/responsive.bootstrap4.min.js"></script>
<script src="/assets/javascript/pages/dataTables.bootstrap.js"></script>
<?php $this->stop() ?>
