<?php
$title_meta = 'Panel Dashboard for Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Panel Dashboard for Tracksz, a Multiple Market Inventory Management Service';

use Delight\Cookie\Session;
use Delight\Cookie\Cookie;
?>
<?= $this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta]) ?>

<?= $this->start('page_content') ?>

<!-- .wrapper -->
<div class="wrapper">
    <!-- .page -->
    <div class="page">
        <?php if (
            Cookie::exists('tracksz_active_store') &&
            Cookie::get('tracksz_active_store') > 0 &&
            Session::get('current_page') == '/account'
        ) : ?>
            <div class="page-message" role="alert">
                <i class="fa fa-fw fa-shopping-cart"></i> <?= _('Current Active Store') ?>: <span class="text-muted-dark"><?= urldecode(Cookie::get('tracksz_active_name')) ?></span> <a href="/account/stores" class="btn btn-sm btn-warning circle ml-3"><?= _('Change Active Store') ?></a> <a href="#" class="btn btn-sm btn-icon btn-warning ml-1" aria-label="Close" onclick="$(this).parent().fadeOut()"><span aria-hidden="true"><i class="fa fa-times"></i></span></a>
            </div><!-- /.page-message -->
        <?php elseif (Session::get('current_page') == '/account') : ?>
            <div class="page-message" role="alert">
                <i class="fa fa-fw fa-shopping-cart"></i> <?= _('No Active Store Selected') ?>: <span class="text-muted-dark"><?= urldecode(Cookie::get('tracksz_active_name')) ?></span> <a href="/account/stores" class="btn btn-sm btn-danger circle ml-3"><?= _('Select Active Store') ?></a> <a href="#" class="btn btn-sm btn-icon btn-danger ml-1" aria-label="Close" onclick="$(this).parent().fadeOut()"><span aria-hidden="true"><i class="fa fa-times"></i></span></a>
            </div><!-- /.page-message -->
        <?php endif; ?>
        <!-- .page-inner -->
        <div class="page-inner">
            <header class="page-title-bar">
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?= ('Dashboard') ?></a>
                            </li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
                <!-- title -->
                <p class="lead">
                    <span class="font-weight-bold"><?= Session::get('member_name') ?></span> <span class="d-block text-muted"><?= _('Here’s what’s happening with your business today.') ?></span>
                </p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?= $this->stop() ?>


<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="/assets/vendor/flatpickr/flatpickr.min.js"></script>
<script src="/assets/vendor/easy-pie-chart/jquery.easypiechart.min.js"></script>
<script src="/assets/vendor/chart.js/Chart.min.js"></script>
<?= $this->stop() ?>

<?php $this->start('page_js') ?>
<script src="/assets/javascript/pages/dashboard.js"></script>
<?= $this->stop() ?>

<?php $this->start('footer_extras') ?>

<?= $this->stop() ?>
