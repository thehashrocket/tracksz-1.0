<?php
$title_meta = 'Add a Shipping Zone at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Add a Shipping Zone at Tracksz, a Multiple Market Inventory Management Service';
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
                <h1 class="page-title"> <?=_(isset($updateName) ? 'Update <em>' . $updateName . '</em>' : 'Add a Shipping Zone')?> </h1>
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
                            <?php if(isset($updateId)): ?>
                            <form action="/account/shipping-zones/edit" method="POST" data-parsley-validate>
                            <input type="hidden" name="updateId" value="<?=$updateId?>">
                            <?php else: ?>
                            <form action="/account/shipping-zones/create" method="POST" data-parsley-validate>
                            <?php endif; ?>
                                <div class="content">
                                    <fieldset>
                                        <div class="form-row mb-2">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="Name" class="required-field"><?=_('Zone Name')?></label> <input type="text" data-toggle="tooltip" data-placement="left" title="<?=_('Enter a name to identify this shipping zone.')?>" class="form-control" id="Name" name="Name" placeholder="<?=_('Ex. International Zone 1')?>" data-parsley-required-message="<?=_('Enter a name to identify this shipping zone.')?>" required maxlength="75" value="<?php echo isset($updateName) ? $updateName : '';?>">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <hr class="mb-3">
                                    <fieldset>
                                        <hr class="mt-5">
                                        <div class="d-flex">
                                            <p><a href="/account/shipping-zones"><?=_('Return to Shipping Zones')?></a> </p>
                                            <button type="submit" class="next btn btn-primary ml-auto"><?=_(isset($updateId) ? 'Update Zone' : 'Create Zone')?></button>
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
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/vendor/bs-stepper/js/bs-stepper.min.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>

