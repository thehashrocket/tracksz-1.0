<?php
$title_meta = 'List Example Data at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'List Example Data at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('header_extras')?>
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
                    <h1 class="page-title"> <?=_('Example Information')?> </h1>
                </div>
                <?php if(isset($alert) && $alert):?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <?php if (is_array($zPage) &&  count($zPage)> 0): ?>
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=_('Example Data Table')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <?php if (isset($zPage)): ?>
                            <table class="table table-borderless table-hover table-responsive-md">
                                <thead class="bg-light">
                                <tr>
                                    <th class="py-3 text-uppercase text-sm"><?=_('Name')?></th>
                                    <th class="py-3 text-uppercase text-sm"><?=_('Id')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($zPage as $page): ?>
                                    <tr>
                                        <td class="py-1 align-middle"><?=$page['ExampleName']?></td>
                                        <td class="py-1 align-middle"><?=$page['Id']?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <?php \App\Library\Paginate::render('/example/page') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<?php $this->stop() ?>
