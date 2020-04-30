<?php
$title_meta = 'Category Add for Your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/inventory/view" title="Inventory Listings"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/category/view" title="Categories"><?= ('Categories') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?= _('Category Add') ?> </h1>
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

                            <form name="category_market_request" id="category_market_request" action="/category/insert_category" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group">
                                                <label for="CategoryName">Category Name</label>
                                                <input type="text" class="form-control" id="CategoryName" name="CategoryName" placeholder="Enter Category Name" data-parsley-required-message="<?= _('Enter Category Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['CategoryName']) && !empty($form['CategoryName'])) ? $form['CategoryName'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="CategoryDescription">Category Description</label>
                                                <textarea class="form-control" id="CategoryDescription" name="CategoryDescription" rows="3" data-parsley-required-message="<?= _('Enter Category Description') ?>" placeholder="Enter Category Description" data-parsley-group="fieldset01" required><?php echo (isset($form['CategoryDescription']) && !empty($form['CategoryDescription'])) ? $form['CategoryDescription'] : ''; ?></textarea>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="ParentCategory"><?= _('Parent Category') ?></label>
                                                </div>
                                                <select class="custom-select" id="ParentCategory" name="ParentCategory">
                                                    <option value="" selected><?= _('Select Parent Category...') ?></option>
                                                    <?php
                                                    if (isset($all_category) && !empty($all_category)) {
                                                        foreach ($all_category as $cat_key => $cat_val) { ?>
                                                            <div class="page-section">
                                                                <!-- .page-section starts -->
                                                                <div class="card-deck-xl">
                                                                    <!-- .card-deck-xl starts -->
                                                                    <div class="card card-fluid">
                                                                        <!-- .card card-fluid starts -->
                                                                        <div class="card-body">
                                                                            <!-- .card-body starts -->

                                                                            <form name="category_market_requ                             <option value=" <?php echo $cat_val['Id']; ?>" <?php echo (isset($form['ParentCategory']) && $form['ParentCategory'] == $cat_val['Id']) ? 'selected' : ''; ?>><?php echo $cat_val['Name']; ?></option>
                                                                            <?php }
                                                                    } else { ?>
                                                                            <option selected><?= _('No Parent Category found...') ?></option>
                                                                        <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="CategoryImage"><?= _('Category Image') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="CategoryImage" name="CategoryImage" multiple="" data-parsley-required-message="<?= _('Select Category Image') ?>" data-parsley-group="fieldset01" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" required> <label class="custom-file-label" for="CategoryImage"><?= _('Choose file') ?></label>
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
