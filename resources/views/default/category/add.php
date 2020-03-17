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
                <form name="category_market_request" id="category_market_request" action="/category/place_market"
                    method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="ProductNameInput">Category Name</label>
                                    <input type="text" class="form-control" id="categoryNameInput"
                                        name="categoryNameInput" aria-describedby="nameHelp"
                                        placeholder="Enter Category Name">
                                    <small id="nameHelp" class="form-text text-muted">You have to Type your category
                                        name here</small>
                                </div>
                                <div class="form-group">
                                    <label for="categoryProduct">Category Description</label>
                                    <textarea class="form-control" id="categoryDesc" name="categoryDesc"
                                        rows="3"></textarea>
                                </div>

                                <div class="input-group mb-3 ">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="parentCategory">Parent Category</label>
                                    </div>
                                    <select class="custom-select" id="parentCategory">
                                        <option selected>Choose...</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="categoryLevel">Category Level</label>
                                    <input type="text" class="form-control" id="categoryLevel"
                                        name="categoryLevel" aria-describedby="catlevelHelp"
                                        placeholder="Enter Category Level">
                                    <small id="catlevelHelp" class="form-text text-muted">You have to Type your category
                                        level here</small>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="productImage">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="productImage"
                                                name="productImage" aria-describedby="productImage">
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
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js')?>
<?=$this->stop()?>

<?php $this->start('footer_extras')?>
<?=$this->stop()?>