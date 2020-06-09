<?php
$title_meta = 'Shipping Methods at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Shipping Methods at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
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
                            <li class="breadcrumb-item active"><?=('Shipping Methods')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?=_('Shipping Methods')?> </h1>
                <p class="text-muted"> <?=_('This page provides you with an opportunity to edit or create a set of shipping methods. You may use these methods when assigning methods to shipping zones. However, shipping methods are not automatically assigned to shipping zones.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
        <?php if (is_array($shippingMethods) &&  count($shippingMethods)> 0): ?>
            <!-- .card -->
            <div class="card card-fluid">
                <h6 class="card-header"><?=_('Shipping Methods')?></h6><!-- .card-body -->
                <div class="card-body">
                    <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Delivery Time</th>
                            <th>First Item Fee</th>
                            <th>Additional Item Fee</th>
                            <th>Minimum Order Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($shippingMethods as $shippingMethod): ?>
                            <tr>
                                <td><?=$shippingMethod['Name']?></td>
                                <td><?=$shippingMethod['DeliveryTime']?></td>
                                <td><?=$shippingMethod['InitialFee']?></td>
                                <td><?=$shippingMethod['DiscountFee']?></td>
                                <td><?=$shippingMethod['Minimum']?></td>
                                <td class="align-middle text-left">
                                    <a href="/account/shipping-methods/edit/<?=$shippingMethod['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Edit this Shipping Method."><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="" data-original-title="Edit this Shipping Method."></i> <span class="sr-only">Edit</span></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteMethod-<?=$shippingMethod['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Delete this Shipping Method."><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete this Shipping Method."></i> <span class="sr-only">Delete</span></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <a href="/account/shipping-methods/add" class="btn btn-sm btn-primary" title="<?=_('Add a Shipping Method')?>"><?=_('Add Shipping Method')?></a>
        </div><!-- /.page-inner -->

        <!-- Modals to delete shipping methods -->
        <?php foreach($shippingMethods as $shippingMethod): ?>
            <div class="modal fade" id="deleteMethod-<?=$shippingMethod['Id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Shipping Method</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this shipping method?</div>
                    <div class="modal-footer">
                        <form action="/account/shipping-methods/delete/<?=$shippingMethod['Id']?>" method="POST">
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
