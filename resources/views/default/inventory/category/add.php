<?php
$title_meta = 'Category Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Category Add for your Tracksz Store, a Multiple Market Product Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
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
                    <h1 class="page-title"> <?=_('Category Add')?> </h1>
                </div>
                <p class="text-muted">
                    <?=_('This is where you can add, modify, and delete Category for the current Active Store: ')?><strong>
                        <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if (isset($alert) && $alert): ?>
                <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif?>
            </header><!-- /.page-title-bar -->
            <div class="card-body">
                <form name="category_market_request" id="category_market_request" action="/category/insert_category"
                    method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="container">

                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="CategoryName">Category Name</label>
                                    <input type="text" class="form-control" id="CategoryName" name="CategoryName" placeholder="Enter Category Name" data-parsley-required-message="<?=_('Enter Category Name')?>" data-parsley-group="fieldset01" required <?php if (isset($store['CategoryName'])) echo ' value="' . $store['CategoryName'] .'"' ?>>
                                </div>
                                <div class="form-group">
                                    <label for="CategoryDescription">Category Description</label>
                                    <textarea class="form-control" id="CategoryDescription" name="CategoryDescription"
                                        rows="3" data-parsley-required-message="<?=_('Enter Category Description')?>" placeholder="Enter Category Description" data-parsley-group="fieldset01" required></textarea>
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="ParentCategory">Parent Category</label>
                                    </div>

                                    <select class="custom-select" id="ParentCategory" name="ParentCategory" data-parsley-required-message="<?=_('Select Parent Category')?>" data-parsley-group="fieldset01" required>
                                        <option selected>Select Category...</option>
                                    <?php
                                    if (isset($all_category) && !empty($all_category)) {
                                        foreach ($all_category as $cat_key => $cat_val) { ?>                                  
                                        <option value="<?php echo $cat_val['Id']; ?>"><?php echo $cat_val['Name']; ?></option>
                                    <?php }} else {?>
                                        <option selected>No Category found...</option>
                                    <?php }?>
                                    </select>
                                </div>
                                                                
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="ProductImage">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="productImage"
                                                name="ProductImage" aria-describedby="productImage" data-parsley-required-message="<?=_('Upload Product Image')?>" data-parsley-group="fieldset01" required>
                                            <label class="custom-file-label" for="image">Choose Product Image</label>
                                        </div>
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

        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js')?>
<script src="/pace/pace.min.js"></script>
<script src="/stacked-menu/stacked-menu.min.js"></script>
<script src="/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js')?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop() ?>
