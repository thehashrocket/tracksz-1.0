<?php
$title_meta = 'parent Category Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Category Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?= ('Dashboard') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/inventory/parent_category" title="Inventory Listings"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/category/parent_category" title="Categories"><?= ('Parent Categories') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add Parent') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Parent Category Add') ?> </h1>
                <p class="text-muted">
                    <?= _('This is where you can add, modify, and delete Category for the current Active Store: ') ?><strong><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <!-- .page-section starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->
                        <div class="card-body">
                            <!-- .card-body starts -->

                            <form name="category_market_request" id="category_market_request" action="/category/insert_parent_category" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="CategoryName">Category Name</label>
                                                <input type="text" class="form-control" id="ParentCategoryName" name="ParentCategoryName" placeholder="Enter Parent Category Name" data-parsley-required-message="<?= _('Enter Parent Category Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ParentCategoryName']) && !empty($form['ParentCategoryName'])) ? $form['ParentCategoryName'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="CategoryDescription">Category Description</label>
                                                <textarea class="form-control" id="ParentCategoryDescription" name="ParentCategoryDescription" rows="3" data-parsley-required-message="<?= _('Enter Parent Category Description') ?>" placeholder="Enter Parent Category Description" data-parsley-group="fieldset01" required><?php echo (isset($form['ParentCategoryDescription']) && !empty($form['ParentCategoryDescription'])) ? $form['ParentCategoryDescription'] : ''; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="CategoryImage"><?= _('Parent Category Image') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="ParentCategoryImage" name="ParentCategoryImage" multiple="" data-parsley-required-message="<?= _('Select Category Image') ?>" data-parsley-group="fieldset01" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" required> <label class="custom-file-label" for="CategoryImage"><?= _('Choose file') ?></label>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->
                                        <div class="col-sm mt-3 pt-3">
                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div> <!-- Card Body -->

                    </div> <!-- .card card-fluid ends -->
                </div> <!-- .card-deck-xl ends -->
            </div> <!-- .page-section ends -->

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
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>
