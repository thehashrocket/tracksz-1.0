<?php
$title_meta = 'Product Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                    <h1 class="page-title"> <?=_('Product Add')?> </h1>
                </div>
                <p class="text-muted"> <?=_('This is where you can add, modify, and delete Product for the current Active Store: ')?><strong> <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if (isset($alert) && $alert): ?>
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif?>
            </header><!-- /.page-title-bar -->
            <div class="card-body">
            <form name="product_market_request" id="product_market_request" action="/product/place_market" method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                                <div class="col-sm">
                                        <div class="form-group">
                                            <label for="ProductNameInput">Product Name</label>
                                            <input type="text" class="form-control" id="productNameInput" name="productNameInput" aria-describedby="nameHelp" placeholder="Enter Product Name">
                                            <small id="nameHelp" class="form-text text-muted">You have to Type your product name here</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="ProductSKUInput">Product SKU</label>
                                            <input type="text" class="form-control" id="productSKUInput" name="productSKUInput" aria-describedby="skuHelp" placeholder="Enter Product SKU">
                                            <small id="skuHelp" class="form-text text-muted">You have to Type your product SKU here</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="ProductIdInput">Product ID</label>
                                            <input type="text" class="form-control" id="productIdInput" name="productIdInput" aria-describedby="idHelp" placeholder="Enter Product ID">
                                            <small id="idHelp" class="form-text text-muted">You have to Type your product ID here</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="ProductBasePriceInput">Base Price</label>
                                            <input type="text" class="form-control" id="productBasePriceInput" name="productBasePriceInput" aria-describedby="priceHelp" placeholder="Enter Product Base Price">
                                            <small id="priceHelp" class="form-text text-muted">You have to Type your product Base Price here</small>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="inputGroupSelectCondition">Conditions</label>
                                                </div>
                                                <select class="custom-select" id="productCondition" name="productCondition">
                                                    <option selected>Choose...</option>
                                                    <option value="1">Used; Very Good</option>
                                                    <option value="2">Used; Good</option>
                                                    <option value="3">Used; Not Good</option>
                                                    <option value="3">New</option>
                                                </select>
                                             </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox" id="productActive" name="productActive" aria-label="Checkbox for following text input"> &nbsp;&nbsp;
                                                    <small id="activeHelp" class="form-text text-muted">Make Product Active</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox" id="productinternationalShip" name="productinternationalShip" aria-label="Checkbox for following text input"> &nbsp;&nbsp;
                                                    <small id="shipHelp" class="form-text text-muted">International Shipping</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="checkbox" id="productExpectedShip" name="productExpectedShip" aria-label="Checkbox for following text input"> &nbsp;&nbsp;
                                                    <small id="expectedHelp" class="form-text text-muted">Expected Shipping</small>
                                                </div>
                                            </div>
                                        </div>
                                </div>  <!-- col-sm -->


                                <div class="col-sm">
                                     <div class="form-group">
                                            <label for="titleeBayInput">Override Title For eBay</label>
                                            <input type="text" class="form-control" id="productTitleeBayInput" name="productTitleeBayInput" aria-describedby="ebayHelp" placeholder="Enter eBay Title">
                                            <small id="ebayHelp" class="form-text text-muted">You have to Type your product ebay title here</small>
                                     </div>
                                     <div class="form-group">
                                            <label for="ProductQtyInput">Product Qty</label>
                                            <input type="number" class="form-control" id="productQtyInput" name="productQtyInput" aria-describedby="qtyHelp" placeholder="Enter Product Qty">
                                            <small id="qtyHelp" class="form-text text-muted">You have to Type your product Qty here</small>
                                     </div>
                                     <div class="form-group">
                                     <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="productImage"><i class="fas fa-cloud-upload-alt"></i>&nbsp;&nbsp;Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="productImage" name="productImage" aria-describedby="productImage">
                                                <label class="custom-file-label" for="image">Choose Product Image</label>
                                            </div>
                                            </div>
                                     </div>
                                </div> <!-- col-sm -->
                                
                        </div> <!-- Row -->
                        <div class="form-group">
                                        <label for="noteProduct">Notes</label>
                                        <textarea class="form-control" id="productNote" name="productNote" rows="3"></textarea>
                                </div>
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

<?php $this->start('footer_extras')?>
<?=$this->stop()?>

