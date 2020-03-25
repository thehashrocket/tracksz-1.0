<?php
$title_meta = 'Marketplace Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Marketplace Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?=_('Add Marketplace')?> </h1>
                    <p> <a href="/account/panel"><?=_('Dashboard')?></a> / <a href="/marketplace/dashboard"> <?=_('Marketplace')?></a> </p>
                </div>
                <p class="text-muted">
                    <?=_('This is where you can add, modify, and delete Marketplace for the current Active Store: ')?><strong>
                        <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if (isset($alert) && $alert): ?>
                <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif?>
            </header><!-- /.page-title-bar -->
            <!-- Horizontal Steppers -->
<div class="row">
  <div class="col-md-12">

  <div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
    Step 1 Of 3
  </div>
</div>

  </div>
</div>
<!-- /.Horizontal Steppers -->
            <div class="card-body">
                <form name="category_market_request" id="category_market_request" action="dashboard/step2"
                    method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                            <div class="form-group">
                                <select name="market_stores" id="market_stores" class="browser-default custom-select market_stores_select">
                                <option selected>Select Marketplace...</option>
                                    <?php
                                    if (isset($market_places) && !empty($market_places)) {
                                        foreach ($market_places as $mar_key => $mar_val) { ?>                                  
                                        <option value="<?php echo $mar_val; ?>"><?php echo $mar_val; ?></option>
                                    <?php }} else {?>
                                        <option selected>No Marketplace found...</option>
                                    <?php }?>
                                </select>
                                </div>
                            </div> <!-- col-sm -->

                            <div class="col-sm mt-3 pt-3">
                            </div> <!-- col-sm -->

                        </div> <!-- Row -->
                    </div> <!-- Container -->

                    <button type="submit" class="btn btn-primary">Next <i class="fa fa-arrow-right"></i></button>
                </form>
            </div> <!-- Card Body -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js')?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/javascript/pages/market_place.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js')?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>