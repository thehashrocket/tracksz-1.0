<?php
$title_meta = 'Inventory Listing for Your Tracksz Store, a Multiple Market Inventory Management Service';
$description_meta = 'Inventory Listing for your Tracksz Store, a Multiple Market Inventory Management Service';
?>
<?= $this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta]) ?>

<?= $this->start('page_content') ?>
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
                    <h1 class="page-title"> <?= _('Inventory Listing') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can add, modify, and delete inventory for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <?php if (isset($stores) && is_array($stores) &&  count($stores) > 0) : ?>
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?= _('Your Stores') ?></h6><!-- .card-body -->
                    <div class="card-body">
                        <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Select <i class="fa fa-question-circle" title="<?= _('Select Active Store.  Click the Shopping Cart in the store\'s row to make that store the current active store. All actions you take in administration applies to the current active store.') ?>" data-toggle="tooltip" data-placement="right"></i></th>
                                    <th>Legal Name</th>
                                    <th>Display Name</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stores as $store) : ?>
                                    <tr>
                                        <td>
                                            <?php if (
                                                Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                                                Delight\Cookie\Cookie::get('tracksz_active_store') != $store['Id']
                                            ) : ?>
                                                <a href="#" data-toggle="modal" data-target="#setActiveStoreModal" data-url="/account/store/active/<?= $store['Id'] ?>" class="btn btn-sm btn-icon btn-secondary" id="active-store-<?= $store['Id'] ?>" title="<?= _('Click Shopping Cart to make this the current active store. All actions you take in administration applies to the current active store.') ?>" onclick="setActiveStore(this)"><i class="fa fa-shopping-cart" data-toggle="tooltip" data-placement="left" title="<?= _('Click Shopping Cart to make this the current active store. All actions you take in administration applies to the current active store.') ?>"></i> <span class="sr-only"><?= _('Select Active Store') ?></span></a></td>
                                    <?php else : ?>
                                        &nbsp;&nbsp;<i style="color: green;" class="far fa fa-check" data-toggle="tooltip" data-placement="left" title="<?= _('Active Store') ?>"> <span class="sr-only"><?= ('Can\'t Delete') ?></span>&nbsp;</i>
                                    <?php endif; ?>
                                    <td><?= $store['LegalName'] ?></td>
                                    <td><?= $store['DisplayName'] ?></td>
                                    <td><?= $store['Address1'] ?></td>
                                    <td><?= $store['ContactFullName'] ?></td>
                                    <td class="align-middle text-left">
                                        <a href="/account/store/edit/<?= $store['Id'] ?>" class="btn btn-sm btn-icon btn-secondary" title="<?= _('Edit this Store') ?>"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="<?= _('Edit this Store') ?>"></i> <span class="sr-only"><?= _('Edit') ?></span></a>
                                        <?php if (
                                            Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                                            Delight\Cookie\Cookie::get('tracksz_active_store') != $store['Id']
                                        ) : ?>
                                            <a href="#" data-toggle="modal" data-target="#deleteStoreModal" data-url="/account/store/delete/<?= $store['Id'] ?>" class="btn btn-sm btn-icon btn-secondary" id="delete-store-<?= $store['Id'] ?>" title="<?= _('Delete this Store.') ?>" onclick="deleteStore(this)"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="top" title="<?= _('Delete this Store') ?>"></i> <span class="sr-only"><?= _('Delete') ?></span></a>
                                        <?php else : ?>
                                            &nbsp;&nbsp;<i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?= _('Cannot delete active store. Change Active store and then delete.') ?>"> <span class="sr-only"><?= ('Can\'t Delete') ?></span>&nbsp;</i>
                                        <?php endif; ?>
                                        <?php if ($store['TrackszListing'] && !$store['StripeSetup']) : ?>
                                            <a href="/account/stripe/connect/<?= $store['Id'] ?>" class="btn btn-warning btn-xs" id="delete-store-<?= $store['Id'] ?>" data-toggle="tooltip" data-placement="left" title="<?= _('Stripe Not Connected. Required to list on Tracksz Listing Service') ?>"><?= _('Stripe') ?></a>
                                        <?php elseif ($store['TrackszListing']) : ?>
                                            <a href="/account/stripe/connect/<?= $store['Id'] ?>" class="btn btn-info btn-xs" id="delete-store-<?= $store['Id'] ?>" data-toggle="tooltip" data-placement="left" title="<?= _('Stripe Connected. Review Stripe Page') ?>"><?= _('Stripe') ?></a>
                                        <?php endif; ?>
                                    </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <a href="/inventory/add" class="btn btn-sm btn-primary" title="<?= _('Add Inventory Item to Your Active Store') ?>"><?= _('Add Inventory Item') ?></a>
            <?php if (isset($deleted) &&  count($deleted) > 0) : ?>
                <p></p><a href="#" data-toggle="collapse" data-target="#deleted-stores" title="<?= _('View Deleted Stores') ?>"><small><?= _('View Deleted Stores.') ?></small></a></p>
                <!-- .card -->
                <div id="deleted-stores" class="collapse card card-fluid">
                    <h6 class="card-header"><?= _('Deleted Stores') ?></h6><!-- .card-body -->
                    <div class="card-body">
                        <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Legal Name</th>
                                    <th>Display Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deleted as $store) : ?>
                                    <tr>
                                        <td><?= $store['LegalName'] ?></td>
                                        <td><?= $store['DisplayName'] ?></td>
                                        <td><?= $store['Address1'] ?></td>
                                        <td><?= $store['City'] ?></td>
                                        <td><?= $store['ContactFullName'] ?></td>
                                        <td class="align-middle text-left">
                                            <a href="#" data-toggle="modal" data-target="#restoreStoreModal" data-url="/account/store/restore/<?= $store['Id'] ?>" class="btn btn-sm btn-icon btn-secondary" id="restore-store-<?= $store['Id'] ?>" title="<?= _('Restore this Store.') ?>" onclick="restoreStore(this)"><i class="fa fa-undo" data-toggle="tooltip" data-placement="top" title="<?= _('Restore this Store') ?>"></i> <span class="sr-only"><?= _('Restore') ?></span></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
