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
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/marketplace/browse"><?=('Marketplaces')?></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <?=('Add')?>
                            </li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            
            <!-- Steppers Starts -->
            <div class="card">
            <!-- .card-body -->
            <div class="card-body">
              <!-- .progress-list -->
              <ol class="progress-list mb-0 mb-sm-4">
                <li class="success">
                  <button type="button">
                    <!-- progress indicator -->
                    <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 1</span>
                </li>
                <li class="active">
                  <button type="button">
                    <!-- progress indicator -->
                    <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 2</span>
                </li>
                <li class="active">
                  <button type="button">
                    <!-- progress indicator -->
                    <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 3</span>
                </li>
              </ol><!-- /.progress-list -->
            </div><!-- /.card-body -->
            <!-- .card-body -->
          </div>
        <!-- Steppers Ends -->

<div class="page-section"> <!-- .page-section starts -->
    <div class="card-deck-xl"> <!-- .card-deck-xl starts -->
            <div class="card card-fluid">   <!-- .card card-fluid starts -->
                <div class="card-body"> <!-- .card-body starts -->
                    <form name="category_market_request" id="category_market_request" action="add/step2"  method="POST" enctype="multipart/form-data" data-parsley-validate=""> <!-- form starts -->
                        <div class="container"> <!-- Container Starts -->
                            <div class="row"> <!-- Row Starts -->
                                <div class="col-sm"> <!-- col-sm Group Left Starts -->
                                    <div class="form-group">
                                    <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select" data-parsley-required-message="<?=_('Select Marketplace to Add')?>" required>
                                    <option value="" selected><?=_('Select Marketplace...')?></option>
                                    <?php
                                    if (isset($market_places) && !empty($market_places)) {
                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                        <option value="<?php echo $mar_val; ?>"><?php echo $mar_val; ?></option>
                                    <?php }} else {?>
                                    <option selected><?=_('No Marketplace found...')?></option>
                                    <?php }?>
                                         </select>
                                     </div>
                                </div> <!-- col-sm Group Left Ends -->
                                <div class="col-sm mt-3 pt-3"> <!-- col-sm Group Right Starts -->
                                </div> <!-- col-sm Group Right Ends -->

                            </div> <!-- Row Ends -->
                        </div> <!-- Container Ends -->

                    <button type="submit" class="submit btn btn-primary ml-auto"><?=_('Next')?> <i class="fa fa-arrow-right"></i></button>

                    </form> <!-- form ends -->
                </div> <!-- Card Body ends -->
        </div> <!-- .card card-fluid ends -->
    </div> <!-- .card-deck-xl ends -->
</div> <!-- .page-section ends -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->

<?=$this->stop()?>
<?php $this->start('plugin_js')?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js')?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>