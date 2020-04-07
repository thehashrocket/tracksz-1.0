<?php
$title_meta = 'Inventory Listing for Your Tracksz Store, a Multiple Market Inventory Management Service';
$description_meta = 'Inventory Listing for your Tracksz Store, a Multiple Market Inventory Management Service';
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
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/inventory/browse" title="Store Reports"><?=('Reports')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Sales')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?=_('Sales Listing')?> </h1>
                <p class="text-muted"> <?=_('Sales Reports for your stores.')?></p>
                <a href="/inventory/add" class="btn btn-sm btn-primary" title="<?=_('Add Inventory Item to Your Active Store')?>"><?=_('Add Inventory Item')?></a>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
        
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<?=$this->stop()?>

<?php $this->start('page_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<?=$this->stop()?>

