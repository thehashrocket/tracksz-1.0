<?php
$title_meta = 'Assign Shipping Zones at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Assign Shipping Zones at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
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
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?=_('Bulk Assign Shipping Zones')?> </h1>
                </div>
                <p class="text-muted"> <?=_('Here you can assign shipping zones to entire countries.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .card -->
            <div class="card card-fluid">
                <div class="card-body">
                    <p>Select an area to assign a shipping zone:</p>
                    <blockquote>
                        <form action="/account/shipping-assign/bulk-assign" method="POST">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Country" id="US" value="US">
                                <label class="form-check-label" for="US">All of United States</label>
                            </div>
                            <div class="form-check disabled">
                                <input class="form-check-input" type="radio" name="Country" id="CA" value="CA">
                                <label class="form-check-label" for="CA">All of Canada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Country" id="GB" value="GB">
                                <label class="form-check-label" for="GB">All of United Kingdom</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Country" id="AU" value="AU">
                                <label class="form-check-label" for="AU">All of Australia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Country" id="US_CA" value="US_CA">
                                <label class="form-check-label" for="US_CA">All of United States and Canada</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="Country" id="GB_AU" value="GB_AU">
                                <label class="form-check-label" for="GB_AU">All of United Kingdom and Australia</label>
                            </div>
                            <br>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-4">
                                        <p>Select a shipping zone to apply to selected region</p>
                                    </div>
                                    <div class="col-8">
                                        <select class="form-control" id="zone" name="ZoneId" style="width: 70%">
                                            <?php foreach ($shippingZones as $zone): ?>
                                                <option value="<?=$zone['Id']?>"><?=$zone['Name']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </form>
                    </blockquote>

                    <br>
                    <a href="/account/shipping-assign/individual/countries">Assign shipping zones to individual countries</a>
                </div>
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
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/extensions/fixedheader/dataTables.fixedHeader.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/dataTables.responsive.min.js"></script>
<script src="/assets/vendor/datatables/extensions/responsive/responsive.bootstrap4.min.js"></script>
<script src="/assets/javascript/pages/dataTables.bootstrap.js"></script>
<?php $this->stop() ?>
