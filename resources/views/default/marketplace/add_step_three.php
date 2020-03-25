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
<div class="row">
  <div class="col-md-12">

  <div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
    Step 3 Of 3
  </div>
</div>

  </div>
</div>
<!-- /.Horizontal Steppers -->
            <div class="card-body">
                    <div class="container">

                        <div class="row">
                            <div class="col-sm">
                            <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Marketplace Add Successfully..!</h5>
                            </div>
                            </div>
                        </div>
                        </div> <!-- Row -->
                    </div> <!-- Container -->
               
            </div> <!-- Card Body -->

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