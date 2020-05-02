<?php
$title_meta = 'Manage a Shipping Zone at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Manage a Shipping Zone at Tracksz, a Multiple Market Inventory Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
<div class="wrapper">
    <div class="page">
        <div class="page-inner">
            <header class="page-title-bar">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="/account/shipping-zones"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=_('Shipping Zones')?></a>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title"> <?=_('Manage Shipping Methods for <em>' . $zone['Name'] . '</em>')?> </h1>
            </header>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <div class="page-section">
                <div class="section-block">
                    <?php if(!$assignedMethods && !$unassignedMethods): ?>
                    <p>No shipping methods exist for this store.</p>
                    <a href="/account/shipping-methods" class="btn btn-primary">Create Shipping Methods</a>
                    <?php endif; ?>
                    <?php if($assignedMethods): ?>
                    <div class="card">
                        <h6 class="card-header"><?=_('Assigned')?></h6>
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
                                <?php foreach($assignedMethods as $shippingMethod): ?>
                                    <tr>
                                        <td><?=$shippingMethod['Name']?></td>
                                        <td><?=$shippingMethod['DeliveryTime']?></td>
                                        <td><?=$shippingMethod['InitialFee']?></td>
                                        <td><?=$shippingMethod['DiscountFee']?></td>
                                        <td><?=$shippingMethod['Minimum']?></td>
                                        <td class="align-middle text-left">
                                            <a href="/account/shipping-methods/unassign/<?=$shippingMethod['MethodId']?>/<?=$zone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Unassign this Shipping Method."><i class="fa fa-minus-square" data-toggle="tooltip" data-placement="left" title="" data-original-title="Unassign this Shipping Method."></i> <span class="sr-only">Unassign</span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($unassignedMethods): ?>
                    <div class="card">
                        <h6 class="card-header"><?=_('Unassigned')?></h6>
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
                                <?php foreach($unassignedMethods as $shippingMethod): ?>
                                    <tr>
                                        <td><?=$shippingMethod['Name']?></td>
                                        <td><?=$shippingMethod['DeliveryTime']?></td>
                                        <td><?=$shippingMethod['InitialFee']?></td>
                                        <td><?=$shippingMethod['DiscountFee']?></td>
                                        <td><?=$shippingMethod['Minimum']?></td>
                                        <td class="align-middle text-left">
                                            <a href="/account/shipping-methods/assign/<?=$shippingMethod['Id']?>/<?=$zone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Assign this Shipping Method."><i class="fa fa-plus-square" data-toggle="tooltip" data-placement="left" title="" data-original-title="Assign this Shipping Method."></i> <span class="sr-only">Assign</span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/vendor/bs-stepper/js/bs-stepper.min.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>

