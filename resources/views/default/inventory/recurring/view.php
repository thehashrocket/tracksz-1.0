<?php
$title_meta = 'Recurring Listing for Your Tracksz Store, a Multiple Marketplace Management Service';
$description_meta = 'Recurring Listing for your Tracksz Store, a Multiple Marketpalce Management Service';
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
                    <h1 class="page-title"> <?= _('Recurring Listing') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can modify, and delete Recurring for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card card-fluid">
                    <!-- .card card-fluid starts -->
                    <div class="card-body">
                        <!-- .card-body starts -->
                        <?php

                        if (is_array($all_recurring) &&  count($all_recurring) > 0) : ?>
                            <table id="recurring_table" name="recurring_table" class="table table-striped table-bordered nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= _('Name') ?></th>
                                        <th><?= _('Price') ?></th>
                                        <th><?= _('Frequency') ?></th>
                                        <th><?= _('Duration') ?></th>
                                        <th><?= _('Cycle') ?></th>
                                        <th><?= _('TrialStatus') ?></th>
                                        <th><?= _('Action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    // echo "<pre> test";
                                    // print_r($all_recurring);
                                    // exit;
                                    foreach ($all_recurring as $recurring) : ?>
                                        <tr>
                                            <td><?= $recurring['Name'] ?></td>
                                            <td><?= $recurring['Price'] ?></td>
                                            <td><?= $recurring['Frequency'] ?></td>
                                            <td><?= $recurring['Duration'] ?></td>
                                            <td><?= $recurring['Cycle'] ?></td>
                                            <td><?= ($recurring['TrialStatus'] && $recurring['TrialStatus'] == 1) ? 'Enable' : 'Disable'; ?></td>
                                            <td> <?php
                                                    $button = '';
                                                    $edit_button = '<a href="' . \App\Library\Config::get('company_url') . '/recurring/edit/' . $recurring['Id'] . '" class="btn btn-xs btn-warning btn_edit"><i class="far fa-edit"></i> Edit</a> &nbsp;';
                                                    $delete_button = '<a href="#delete-' . $recurring['Id'] . '" delete_id="' . $recurring['Id'] . '" class="btn btn-xs btn-danger btn_delete"><i class="far fa-trash-alt"></i> Delete</a>';
                                                    $button .= $edit_button;
                                                    $button .= $delete_button;
                                                    echo $button;
                                                    ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>

                    </div><!-- card-body ends -->
                </div><!-- /.card card-fluid ends -->
            </div><!-- /.page-section ends -->

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/javascript/pages/recurring.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>
<?= $this->stop() ?>
