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
                                            <a href="/account/shipping-zones/assign/<?=$shippingMethod['Id']?>/<?=$zone['Id']?>" class="btn btn-sm btn-icon btn-secondary" title="Assign this Shipping Method."><i class="fa fa-plus-square" data-toggle="tooltip" data-placement="left" title="" data-original-title="Assign this Shipping Method."></i> <span class="sr-only">Assign</span></a>
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
<div class="modal fade" id="stripeModalCenter" tabindex="-1" role="dialog" aria-labelledby="stripeModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="stripeModalCenterLabel" class="modal-title"> Stripe Connect & Tracksz </h5>
            </div>
            <div class="modal-body">
                <p> <strong>Stripe Connect</strong> <?=_('allows Tracksz to accept credit cards on your behalf. Transactions from your sales on Tracksz go through your Stripe Connect account, the transactions are immediately recorded at your Stripe Connect dashboard and, when scheduled, the funds are deposited directly into your bank account.')?></p>
                <p> <strong>Stripe Connect</strong> <?=_('provides added security for credit card processing. Credit cards entered by customers are validated by the Address, Zip/Post Code, and Card Validation Code (CVS). Stripe offers customers the convenience of storing credit cards on Stripe\'s secure network for use later. Tracksz does not store credit card information and cannot access credit card information entered on forms, substantially reducing risk.')?></p>
                <p> <?=_('After you complete the store form, you will be given an opportunity to connect your Stripe account to your Tracksz account.  This is <strong>required</strong> to list on Tracksz Listing Site. <u>You must connnect your Stripe account to your store using the form we provide.</u>')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=_('Close')?></button> <button type="button" class="btn btn-light" onclick=" window.open('https://www.stripe.com','_blank')" data-dismiss="modal"><?=_('More About Stripe')?></button>
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

