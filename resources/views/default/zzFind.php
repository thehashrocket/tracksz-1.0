<?php
$title_meta = 'Find Example Data at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Find Example Data at Tracksz, a Multiple Market Inventory Management Service. Manage all aspects of your store.';
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
            <!-- .page-title-bar -->
            <header class="page-title-bar">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            <a href="/account/stores"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=_('Stores')?></a>
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title"> <?=_('Example Data')?> </h1>
            </header><!-- /.page-title-bar -->
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <div class="card">
                    <!-- .card-body -->
                    <div class="card-body">
                        <!-- .form -->
                        <form id="example-find" action="/example/find" method="post" data-parsley-validate="">
                            <!-- .content -->
                            <div id="test-l-1" class="content">
                                <!-- fieldset -->
                                <fieldset>
                                    <legend><?=_('Find Example')?></legend>
                                    <!-- form row -->
                                    <div class="form-row mb-2">
                                        <!-- form column -->
                                        <div class="col-md-6">
                                            <!-- .form-group -->
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="ExampleName" class="form-label"><?=_('Example Name')?> * </label>
                                                    <input id="ExampleName" name="ExampleName" type="text" maxlength="200" class="form-control" title="<?=_('Enter the name you want to find..')?>"  placeholder="<?=_('Enter name you want to find....')?>" data-parsley-required-message="<?=_('Please enter the name you want to find.')?>" required maxlength="255" <?php if (isset($ExampleName)) echo ' value="' . $ExampleName .'"' ?>>
                                                </div>
                                            </div><!-- /.form-group -->
                                        </div>
                                    </div><!-- /form row -->
                                    <div class="form-row mb-2">
                                        <!-- .content -->
                                        <div id="test-l-4" class="content">
                                            <div class="d-flex">
                                                <button type="submit" class="submit btn btn-primary ml-auto"  title="<?=_('Click to Find Example')?>" ><?=_('Find Example')?></button>
                                            </div>
                                        </div><!-- /.content -->
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.page-section -->
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
    <script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>