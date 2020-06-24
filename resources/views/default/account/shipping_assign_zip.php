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
                    <h1 class="page-title"> <?=_('Assign Shipping Zones to ' . $stateName)?> </h1>
                </div>
                <p class="text-muted"> <?=_('Here you can assign shipping zones to zip code ranges within a state.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .card -->
            <div class="card card-fluid">
                <div class="card-body">
                    <form id="shipping-assign-zip" action="/account/shipping-assign/individual/zip" method="POST">
                        <input type="hidden" name="StateId" value="<?= $stateId ?>">
                        <input type="hidden" name="StateZipCodeMin" value="<?= $stateZipCodeMin ?>">
                        <input type="hidden" name="StateZipCodeMax" value="<?= $stateZipCodeMax ?>">
                        <label for="ZoneId">Shipping Zone:</label>
                        <select name="ZoneId" class="form-control">
                            <?php foreach($shippingZones as $zone): ?>
                            <option value="<?= $zone['Id'] ?>"><?= $zone['Name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="ZipCodeMin">Start:</label>
                        <input type="number" min="<?= $stateZipCodeMin ?>" max="<?= $stateZipCodeMax ?>" name="ZipCodeMin" placeholder="min: <?= $stateZipCodeMin ?>" class="form-control" required>
                        <label for="ZipCodeMax">End:</label>
                        <input type="number" min="<?= $stateZipCodeMin ?>" max="<?= $stateZipCodeMax ?>" name="ZipCodeMax" placeholder="max: <?= $stateZipCodeMax ?>" class="form-control" required>
                        <button type="submit" class="btn btn-primary">Add Range</button>
                    </form>
                    <blockquote>
                        <?php if($zipCodeAssignments): ?>
                        <table class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Shipping Zone</th>
                                    <th>Zip Range Start</th>
                                    <th>Zip Range End</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($zipCodeAssignments as $assignment): ?>
                                <?php if(isset($conflicts) && in_array($assignment['Id'], $conflicts)): ?>
                                    <tr style="background: #ff00004f">
                                <?php else: ?>
                                    <tr>
                                <?php endif; ?>
                                    <td><?= $assignment['Name'] ?></td>
                                    <td><?= $assignment['ZipCodeMin'] ?></td>
                                    <td><?= $assignment['ZipCodeMax'] ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-secondary btn-icon" href="/account/shipping-assign/individual/zip/delete/<?= $assignment['Id'] ?>/<?= $stateId ?>"><i class="fa fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
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
