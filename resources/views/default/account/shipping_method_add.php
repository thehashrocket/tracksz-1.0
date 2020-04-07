<?php
$title_meta = 'Add a Shipping Method at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Add a Shipping Method at Tracksz, a Multiple Market Inventory Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
<div class="wrapper">
    <div class="page">
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
                            <li class="breadcrumb-item">
                                <a href="/account/shipping-methods" title="Store's Shipping Methods"><?=('Shipping Methods')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Add')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?=_('Add a Shipping Method')?> </h1>
                <p class="text-muted"> <?=_('Here you can define shipping methods which can be applied to regional zones.')?></p>
            </header>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <div class="page-section">
                <div class="section-block">
                    <div class="card">
                        <div class="card-body">
                            <?php if(isset($update_id)): ?>
                            <form action="/account/shipping-methods/edit" method="POST" data-parsley-validate>
                            <input type="hidden" name="update_id" value="<?=$update_id?>">
                            <?php else: ?>
                            <form action="/account/shipping-methods/create" method="POST" data-parsley-validate>
                            <?php endif; ?>
                                <div class="content">
                                    <fieldset>
                                        <legend><?=_('Method Information')?></legend>
                                        <div class="form-row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Name" class="required-field"><?=_('Method Name')?></label> <input type="text" data-toggle="tooltip" data-placement="left" title="<?=_('Enter a name to identify this shipping method.')?>" class="form-control" id="Name" name="Name" placeholder="<?=_('Ex. International Standard')?>" data-parsley-required-message="<?=_('Enter a name to identify this shipping method.')?>" data-parsley-group="fieldset01" required maxlength="75" value="<?php echo isset($update_name) ? $update_name : '';?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="DeliveryTime" class="required-field"><?=_('Delivery Time')?></label> <input type="text" title="<?=_('Enter the delivery time this method applies to.')?>" class="form-control" id="DeliveryTime" name="DeliveryTime" placeholder="<?=_('Ex. 21-36 Business Days')?>" maxlength="75" required data-parsley-required-message="<?=_('Enter the delivery time this method applies to.')?>" value="<?php echo isset($update_delivery) ? $update_delivery : '';?>">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr class="mb-3">
                                    <fieldset>
                                        <legend><?=_('Shipping Fees')?></legend>
                                        <div class="form-row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="InitialFee" class="required-field"><?=_('First Item Fee')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the fee to be charged on the first item.')?>"></i></label> <input name="InitialFee" type="number" class="form-control" id="initialFee" title="<?=_('Enter the fee to be charged on the first item.')?>" placeholder="Ex. 20.00" data-parsley-required-message="<?=_('Enter the fee to be charged on the first item.')?>" required data-parsley-type="number"  data-parsley-maxlength="10" value="<?php echo isset($update_fee) ? $update_fee : '';?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="DiscountFee" class="required-field"><?=_('Additional Item Fee')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the fee to be charged on additional items.')?>"></i></label> <input type="number" title="<?=_('Enter the fee to be charged on additional items.')?>" class="form-control" id="DiscountFee" name="DiscountFee" placeholder="Ex. 20.00"  data-parsley-required-message="Enter the fee to be charged on additional items." required data-parsley-type="number" data-parsley-maxlength="10" value="<?php echo isset($update_discount_fee) ? $update_discount_fee : '';?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="Minimum" class="required-field"><?=_('Minimum Order Amount')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the minimum cost for this shipping method to apply.')?>"></i></label> <input type="number" title="<?=_('Enter the minimum cost for this shipping method to apply.')?>" class="form-control" id="Minimum" name="Minimum" placeholder="Ex. 20.00"  data-parsley-required-message="Enter the minimum cost for this shipping method to apply." required data-parsley-type="number" data-parsley-maxlength="10" value="<?php echo isset($update_minimum) ? $update_minimum : '';?>">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr class="mb-3">
                                    <fieldset>
                                        <hr class="mt-5">
                                        <div class="d-flex">
                                            <p><a href="/account/shipping-methods"><?=_('Return to Shipping Methods')?></a> </p>
                                            <button type="submit" class="next btn btn-primary ml-auto"><?=_(isset($update_id) ? 'Update Method' : 'Create Method')?></button>
                                        </div>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                    </div>
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
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>

