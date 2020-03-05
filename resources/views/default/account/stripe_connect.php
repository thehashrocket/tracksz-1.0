<?php
$title_meta = 'Stripe Connect Store at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Stripe Connect Store at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="/account/stores"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=_('Stores')?></a>
                        </li>
                    </ol>
                </nav>
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?=_('Stripe Connect')?> </h1>
                </div>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
                <p> <strong>Stripe Connect</strong> <?=_('allows Tracksz to accept credit cards on your behalf. Transactions from your sales on Tracksz go through your Stripe Connect account, the transactions are immediately recorded at your Stripe Connect dashboard and, when scheduled, the funds are deposited directly into your bank account.')?></p>
                <p> <strong>Stripe Connect</strong> <?=_('provides added security for credit card processing. Credit cards entered by customers are validated by the Address, Zip/Post Code, and Card Validation Code (CVS). Stripe offers customers the convenience of storing credit cards on Stripe\'s secure network for use later. Tracksz does not store credit card information and cannot access credit card information entered on forms, substantially reducing risk.')?></p>
                <p> <?=_('Click "Setup Stripe Connect" below to connect your store accout to your Tracksz store account.')?></p>
            </header><!-- /.page-title-bar -->
            <div class="text-center">
            <?php if(isset($StoreName) && $StoreName): ?>
                <p><strong><?=_('Stripe Connnect for: ')?> <?=$StoreName?></strong></p>
            <?php endif; ?>
            <?php if (isset($StripeSetup) && $StripeSetup != 1): ?>
                <a href="<?=getenv('STRIPE_STANDARD_URL')?><?=getenv('STRIPE_CONNECT')?>&scope=read_write&state=<?=\Delight\Cookie\Session::get('connect_state')?>" target="_blank" class="btn btn-primary"><i class="fa fa-award mr-2"></i> <?=_('Setup Stripe Connect')?></a>
            <?php else: ?>
                <?=_('You are all set. Your Stripe Connect is setup.');?>
                <p class="mt-3"><a href="https://www.stripe.com" target="_blank" class="btn btn-outline-primary"><i class="fa fa-award mr-2"></i> <?=_('Access Your Stripe Account')?></a></p>
            <?php endif; ?>
            </div>
            <div class="text-center">
                <p class="mt-3"><a href="/account/stores" class="btn btn-primary btn-sm"><i class="fa fa-award mr-2"></i> <?=_('Return to Store Listing')?></a></p>
            </div>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<?php $this->stop() ?>
