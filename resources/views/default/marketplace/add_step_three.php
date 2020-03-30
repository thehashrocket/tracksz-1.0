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
                    <h1 class="page-title"> <?=_('Marketplace')?> </h1>
                    <p> <a href="/account/panel"><?=_('Dashboard')?></a> / <a href="/marketplace/dashboard"> <?=_('Marketplace')?></a> </p>
                </div>
                <p class="text-muted">
                    <?=_('Thanks you, Your Marketplace is added succussfully...!')?><strong>
                        <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if (isset($alert) && $alert): ?>
                <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif?>
            </header><!-- /.page-title-bar -->
            <!-- Horizontal Steppers -->
              <!--  <div class="row">  row Starts -->
                   <!-- <div class="col-md-12">  col-md-12 Starts -->
                         <!-- <div class="progress">  progress Starts -->
                           <!--  <div class="progress-bar" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
                           <?=_('Step 1 Of 3')?>  progress-bar Starts -->
                        <!--    </div>  progress-bar Ends -->
                     <!--     </div> progress Ends -->
                <!--   </div> col-md-12 Ends -->
              <!--  </div>  row Ends -->
            <!-- /.Horizontal Steppers -->

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
                        <li class="success">
                          <button type="button">
                            <!-- progress indicator -->
                            <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 2</span>
                        </li>
                        <li class="success">
                          <button type="button">
                            <!-- progress indicator -->
                            <span class="progress-indicator"></span></button> <span class="progress-label d-none d-sm-inline-block">Step 3</span>
                        </li>
                      </ol><!-- /.progress-list -->
                    </div><!-- /.card-body -->
                    <!-- .card-body -->
            </div>
            <!-- Steppers Ends -->
            <div class="section-block">
                  <div class="alert alert-success has-icon alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <div class="alert-icon">
                      <span class="oi oi-flag"></span>
                    </div>
                    <h4 class="alert-heading"> <?=_('Success!')?> </h4>
                    <p class="mb-0"> <?=_('Marketplace Added successfully...!')?></a>. </p>
                  </div><!-- grid row -->
                </div>

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

<?php $this->start('footer_extras')?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop()?>