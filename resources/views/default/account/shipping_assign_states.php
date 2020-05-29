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
                    <h1 class="page-title"> <?=_('Assign Shipping Zones to Specific States')?> </h1>
                </div>
                <p class="text-muted"> <?=_('Here you can assign shipping zones to individual states/provinces within countries.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
                <ul class="list-inline">
                    <li class="list-inline-item"><a <?php if($countryId != 223): ?>href="/account/shipping-assign/individual/states/223"<?php endif;?>>United States</a></li>
                    <li class="list-inline-item"><a <?php if($countryId != 38): ?>href="/account/shipping-assign/individual/states/38"<?php endif;?>>Canada</a></li>
                    <li class="list-inline-item"><a <?php if($countryId != 13): ?>href="/account/shipping-assign/individual/states/13"<?php endif;?>>Australia</a></li>
                </ul>
            </header><!-- /.page-title-bar -->
            <!-- .card -->
            <div class="card card-fluid">
                <div class="card-body">
                    <blockquote>
                        <table id="shipping-countries" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                            <tr>
                                <th>State/Province</th>
                                <th>Shipping Zones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $zipCodes = include(__DIR__.'\..\..\..\..\config\zip_codes.php');
                            $states = $zipCodes['US'];
                            ?>
                            <?php foreach($stateZoneAssignments as $state => $zone): ?>
                                <?php $state = json_decode($state, true); ?>
                                <tr>
                                    <td>
                                        <?= $state['Name'] ?>
                                        <?php if($countryId == 223 && array_key_exists($state['Id'], $states)): ?>
                                            <br>
                                            <small><a href="/account/shipping-assign/individual/zip/<?= $state['Id'] ?>">Zip code assignment</a></small>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <form action="/account/shipping-assign/individual/states" method="POST">
                                            <input type="hidden" name="StateId" value="<?= $state['Id'] ?>">
                                            <input type="hidden" name="CountryId" value="<?= $countryId ?>">
                                            <select class="form-control" name="ZoneId" style="display: inline-block">
                                            <?php if(!$zone): ?>
                                                <option value="null">--</option>
                                                <?php foreach($shippingZones as $shippingZone): ?>
                                                    <option value="<?= $shippingZone['Id'] ?>"><?= $shippingZone['Name'] ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="<?= $zone['ZoneId'] ?>"><?= $zone['Name'] ?></option> 
                                                <?php foreach($shippingZones as $shippingZone): ?>
                                                    <?php if($shippingZone['Id'] != $zone['ZoneId']): ?>
                                                        <option value="<?= $shippingZone['Id'] ?>"><?= $shippingZone['Name'] ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </blockquote>

                    <?php \App\Library\Paginate::render('/account/shipping-assign/country'); ?>
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
