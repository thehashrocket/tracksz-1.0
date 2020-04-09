<?php
$title_meta = 'Add/Edit Inventory Item at your Tracksz Store, a Multiple Market Inventory and Order Management Service';
$description_meta = 'Add/Edit Inventory Item at your Tracksz Store, a Multiple Market Inventory & Order Management Service';
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="/inventory/view"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=_('Inventory')?></a>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title"> <?=_('Inventory Item')?> </h1>
                
            </header><!-- /.page-title-bar -->
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <!-- .page-section -->
            <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                    <!-- Default Steps -->
                    <!-- .bs-stepper -->
                    <div id="stepper" class="bs-stepper">
                        <!-- .card -->
                        <div class="card">
                            <!-- .card-header -->
                            <div class="card-header">
                                <!-- .steps -->
                                <div class="steps steps-" role="tablist">
                                    <ul>
                                        <li class="step" data-target="#item-l-1">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fas fa-play"></i></span> <span class="d-none d-sm-inline"><?=_('Initial')?></span></a>
                                        </li>
                                        <li class="step" data-target="#item-l-2">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-list-alt"></i></span> <span class="d-none d-sm-inline"><?=_('Details')?></span></a>
                                        </li>
                                        <li class="step" data-target="#item-l-3">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-cog"></i></span> <span class="d-none d-sm-inline"><?=_('Options')?></span></a>
                                        </li>
                                        <li class="step" data-target="#item-l-4">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-fire"></i></span> <span class="d-none d-sm-inline"><?=_('Specials')?></span></a>
                                        </li>
                                        <li class="step" data-target="#item-l-5">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-image"></i></span> <span class="d-none d-sm-inline"><?=_('Images')?></span></a>
                                        <li class="step" data-target="#item-l-6">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="oi oi-check"></i></span> <span class="d-none d-sm-inline"><?=_('Confirm')?></span></a>
                                        </li>
                                    </ul>
                                </div><!-- /.steps -->
                            </div><!-- /.card-header -->
                            <!-- .card-body -->
                            <div class="card-body">
                                <!-- .form -->
                                <form id="stepper-form" action="/account/store" method="post">
                                    <?php if(isset($store['Id'])): ?>
                                        <input type="hidden" name="Id" value="<?=$store['Id']?>">
                                    <?php endif; ?>
                                    <!-- .content -->
                                    <div id="item-l-1" class="content dstepper-none fade">
                                        <!-- fieldset -->
                                        <fieldset>
                                            <legend><?=_('Initial Product Information')?></legend>
                                            herer
                                            <hr class="mt-5">
                                            <!-- .d-flex -->
                                            <div class="d-flex">
                                                <p><a href="/inventory/view"><?=_('Return to Inventory List')?></a> </p><button type="button" class="next btn btn-primary ml-auto"  data-validate="fieldset01"><?=_('Next Step')?></button>
                                            </div><!-- /.d-flex -->
                                            <div class="text-center" style="font-size: 90%; color: #B76BA3">
                                                <?=_('Form Data is NOT saved until Last Step.')?>
                                            </div>
                                        </fieldset>
                                    </div><!-- /.content -->
                                    <!-- .content -->
                                    <div id="item-l-2" class="content dstepper-none fade">
                                        <fieldset>
                                            <legend><?=_('Product Details')?></legend>
                                            
                                        </fieldset>
                                    </div>
                                    <!-- .content -->
                                    <div id="item-l-3" class="content dstepper-none fade">
                                        <!-- fieldset -->
                                        <fieldset>
                                            <legend><?=_('Product Options')?></legend> <!-- .custom-control --><div class="d-flex">
                                                <button type="button" class="prev btn btn-secondary">Previous</button> <button type="button" class="next btn btn-primary ml-auto" data-validate="fieldset01"><?=_('Next Step')?></button>
                                            </div>
                                            <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Form Data is NOT saved until Last Step.')?></div>
                                        </fieldset>
                                    </div><!-- /.content -->
                                    <!-- .content -->
                                    <div id="item-l-4" class="content dstepper-none fade">
                                        <!-- fieldset -->
                                        <fieldset>
                                            <legend><?=_('Terms Agreement')?></legend> <!-- .card -->
                                            
                                            <div class="d-flex">
                                                <button type="button" class="prev btn btn-secondary"><?=_('Previous')?></button> <button type="submit" class="submit btn btn-primary ml-auto"><?=_('Submit')?></button>
                                            </div>
                                            <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Click <strong>Submit</strong> to Save your entries.')?></div>
                                        </fieldset>
                                    </div><!-- /.content -->
                                    <!-- .content -->
                                    <div id="item-l-5" class="content dstepper-none fade">
                                        <!-- fieldset -->
                                        <fieldset>
                                            <legend><?=_('Terms Agreement')?></legend> <!-- .card -->
            
                                            <div class="d-flex">
                                                <button type="button" class="prev btn btn-secondary"><?=_('Previous')?></button> <button type="submit" class="submit btn btn-primary ml-auto"><?=_('Submit')?></button>
                                            </div>
                                            <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Click <strong>Submit</strong> to Save your entries.')?></div>
                                        </fieldset>
                                    </div><!-- /.content -->
                                    <!-- .content -->
                                    <div id="item-l-6" class="content dstepper-none fade">
                                        <!-- fieldset -->
                                        <fieldset>
                                            <legend><?=_('Terms Agreement')?></legend> <!-- .card -->
            
                                            <div class="d-flex">
                                                <button type="button" class="prev btn btn-secondary"><?=_('Previous')?></button> <button type="submit" class="submit btn btn-primary ml-auto"><?=_('Submit')?></button>
                                            </div>
                                            <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Click <strong>Submit</strong> to Save your entries.')?></div>
                                        </fieldset>
                                    </div><!-- /.content -->

                                </form>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div><!-- /.bs-stepper -->
                </div><!-- /.section-block -->
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>

<!-- Normal modal -->
<div class="modal fade" id="stripeModalCenter" tabindex="-1" role="dialog" aria-labelledby="stripeModalCenterLabel" aria-hidden="true">
    <!-- .modal-dialog -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- .modal-content -->
        <div class="modal-content">
            <!-- .modal-header -->
            <div class="modal-header">
                <h5 id="stripeModalCenterLabel" class="modal-title"> Stripe Connect & Tracksz </h5>
            </div><!-- /.modal-header -->
            <!-- .modal-body -->
            <div class="modal-body">
                <p> <strong>Stripe Connect</strong> <?=_('allows Tracksz to accept credit cards on your behalf. Transactions from your sales on Tracksz go through your Stripe Connect account, the transactions are immediately recorded at your Stripe Connect dashboard and, when scheduled, the funds are deposited directly into your bank account.')?></p>
                <p> <strong>Stripe Connect</strong> <?=_('provides added security for credit card processing. Credit cards entered by customers are validated by the Address, Zip/Post Code, and Card Validation Code (CVS). Stripe offers customers the convenience of storing credit cards on Stripe\'s secure network for use later. Tracksz does not store credit card information and cannot access credit card information entered on forms, substantially reducing risk.')?></p>
                <p> <?=_('After you complete the store form, you will be given an opportunity to connect your Stripe account to your Tracksz account.  This is <strong>required</strong> to list on Tracksz Listing Site. <u>You must connnect your Stripe account to your store using the form we provide.</u>')?></p>
            
            </div><!-- /.modal-body -->
            <!-- .modal-footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=_('Close')?></button> <button type="button" class="btn btn-light" onclick=" window.open('https://www.stripe.com','_blank')" data-dismiss="modal"><?=_('More About Stripe')?></button>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/vendor/bs-stepper/js/bs-stepper.min.js"></script> <!-- END PLUGINS JS -->
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<script src="/assets/javascript/pages/steps-store.js"></script>
<?php $this->stop() ?>