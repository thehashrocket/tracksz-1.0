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
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?=_('Shipping Methods')?> </h1>
                </div>
                <p class="text-muted"> <?=_('This page provides you with an opportunity to edit or create a set of shipping methods. You may use these methods when assigning methods to shipping zones. However, shipping methods are not automatically assigned to shipping zones.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
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
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($shippingMethods as $shippingMethod): ?>
                            <tr>
                                <td><?=$shippingMethod['Name']?></td>
                                <td><?=$shippingMethod['DeliveryTime']?></td>
                                <td><?=$shippingMethod['FirstItemFee']?></td>
                                <td><?=$shippingMethod['AdditionalItemFee']?></td>
                                <td><?=$shippingMethod['MinOrderAmount']?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
            <a href="#" class="btn btn-sm btn-primary" title="<?=_('Add a Shipping Method')?>"><?=_('Add Shipping Method')?></a>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>

<form id="addShippingMethod" name="addShippingMethod" method="POST">
    <input class="" type="text" name="name" placeholder="Method Name">

    <input type="submit">
</form>
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
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
    
        <?php if (is_array($shippingMethods) &&  count($shippingMethods)> 0): ?>
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
