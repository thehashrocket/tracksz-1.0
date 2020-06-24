<?php
$title_meta = 'Product Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Product Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                                <a href="/inventory/browse" title="View Store's Orders"><?= ('Inventory') ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?= ('Add') ?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    <p><input type='button' class='btn btn-primary btn_catelog d-none' value='Switch to Catalog Item'></p>
                    <p><input type='button' class='btn btn-primary btn_none_catelog' value='Switch to Non-Catalog Item'></p>
                </div>
                <!-- title -->
                <div class="mb-3 d-flex justify-content-between">
                    <h1 class="page-title"> <?= _('Product Add') ?> </h1>
                </div>
                <p class="text-muted"> <?= _('This is where you can add, modify, and delete Product for the current Active Store: ') ?><strong> <?= \Delight\Cookie\Cookie::get('tracksz_active_name') ?></strong></p>
                <?php if (isset($alert) && $alert) : ?>
                    <div class="col-sm-12 alert alert-<?= $alert_type ?> text-center"><?= $alert ?></div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->

            <div class="page-section">
                <!-- .page-section Catlog starts -->
                <div class="card-deck-xl catelog">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_request" id="product_market_request" action="/product/place_market" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Basic Information') ?></h5>
                                            <div class="form-group">
                                                <label for="ProductNameInput"><?= _('Product Name') ?></label>
                                                <input type="text" class="form-control" id="ProductNameInput" name="ProductNameInput" placeholder="Enter Product Name" data-parsley-required-message="<?= _('Enter Product Name') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductNameInput']) && !empty($form['ProductNameInput'])) ? $form['ProductNameInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductSKUInput"><?= _('Product SKU') ?></label>
                                                <input type="text" class="form-control" id="ProductSKUInput" name="ProductSKUInput" placeholder="Enter Product SKU" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductSKUInput']) && !empty($form['ProductSKUInput'])) ? $form['ProductSKUInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductIdInput"><?= _('Product ID') ?></label>
                                                <input type="text" class="form-control" id="ProductIdInput" name="ProductIdInput" placeholder="Enter Product ID" data-parsley-required-message="<?= _('Enter Product Id') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductIdInput']) && !empty($form['ProductIdInput'])) ? $form['ProductIdInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductBasePriceInput"><?= _('Base Price') ?></label>
                                                <input type="number" class="form-control" data-parsley-maxlength="14" id="ProductBasePriceInput" name="ProductBasePriceInput" placeholder="Enter Product Base Price" value="<?php echo (isset($form['ProductBasePriceInput']) && !empty($form['ProductBasePriceInput'])) ? $form['ProductBasePriceInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="inputGroupSelectCondition"><?= _('Conditions') ?></label>
                                                    </div>
                                                    <select class="custom-select" id="ProductCondition" name="ProductCondition">
                                                        <option value="" selected><?= _('Choose...') ?></option>
                                                        <option value="Used- Very Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Very Good') ? 'selected' : ''; ?>>Used; Very Good</option>
                                                        <option value="Used- Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Good') ? 'selected' : ''; ?>>Used; Good</option>
                                                        <option value="Used- Not Good" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'Used- Not Good') ? 'selected' : ''; ?>>Used; Not Good</option>
                                                        <option value="New" <?php echo (isset($form['ProductCondition']) && $form['ProductCondition'] == 'New') ? 'selected' : ''; ?>>New</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProductActive" id="ProductActive" data-parsley-required-message="<?= _('Make Product Active') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProductActive']) && $form['ProductActive'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProductActive']) && $form['ProductActive'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProductActive">
                                                        <?= _('Make Product Active') ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProdInterShip" id="ProdInterShip" data-parsley-required-message="<?= _('International Shipping') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProdInterShip']) && $form['ProdInterShip'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProdInterShip']) && $form['ProdInterShip'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProdInterShip">
                                                        <?= _('International Shipping') ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="ProdExpectedShip" id="ProdExpectedShip" data-parsley-required-message="<?= _('Expected Shipping') ?>" data-parsley-group="fieldset01" value="<?php echo (isset($form['ProdExpectedShip']) && $form['ProdExpectedShip'] == 1) ? 1 : 0; ?>" <?php echo (isset($form['ProdExpectedShip']) && $form['ProdExpectedShip'] == 1) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="ProdExpectedShip">
                                                        <?= _('Expected Shipping') ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="ProdTitleBayInput"><?= _('Override Title For eBay') ?></label>
                                                <input type="text" class="form-control" id="ProdTitleBayInput" name="ProdTitleBayInput" placeholder="Enter eBay Title" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProdTitleBayInput']) && !empty($form['ProdTitleBayInput'])) ? $form['ProdTitleBayInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProdQtyInput"><?= _('Product Qty') ?></label>
                                                <input type="number" class="form-control" id="ProdQtyInput" name="ProdQtyInput" placeholder="Enter Product Qty" data-parsley-required-message="<?= _('Enter Product QTY') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProdQtyInput']) && !empty($form['ProdQtyInput'])) ? $form['ProdQtyInput'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProdImage"><?= _('Product Image') ?></label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="ProdImage" name="ProdImage" multiple=""> <label class="custom-file-label" for="CategoryImage" data-parsley-required-message="<?= _('Select Inventory File Upload') ?>" data-parsley-group="fieldset01" required><?= _('Choose file') ?></label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <select name="CategoryName" id="CategoryName" class="browser-default custom-select category_select">
                                                    <option value="" selected><?= _('Select Category...') ?></option>
                                                    <?php
                                                    if (isset($all_category) && is_array($all_category) && !empty($all_category)) {
                                                        foreach ($all_category as $cat_key => $cat_val) { ?>
                                                            <option value="<?php echo $cat_val['Id']; ?>" <?php echo (isset($form['CategoryName']) && $form['CategoryName'] == $cat_val['Id']) ? 'selected' : ''; ?>><?php echo $cat_val['Name']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Category found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select name="MarketName" id="MarketName" class="browser-default custom-select market_stores_select">
                                                    <option selected><?= _('Select Marketplace...') ?></option>
                                                    <?php
                                                    if (isset($market_places) && !empty($market_places)) {
                                                        foreach ($market_places as $mar_key => $mar_val) { ?>
                                                            <option value="<?php echo $mar_val['Id']; ?>" <?php echo (isset($form['MarketName']) && $form['MarketName'] == $mar_val['Id']) ? 'selected' : ''; ?>><?php echo $mar_val['MarketName']; ?></option>
                                                        <?php }
                                                    } else { ?>
                                                        <option selected><?= _('No Marketplace found...') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->
                                    <div class="form-group">
                                        <label for="ProductNote"><?= _('Notes') ?></label>
                                        <textarea class="form-control" id="ProductNote" name="ProductNote" rows="3"><?php echo (isset($form['ProductNote']) && !empty($form['ProductNote'])) ? $form['ProductNote'] : ''; ?></textarea>
                                    </div>
                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary"><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!--  Catlog ends /.page -->

                <!-- .page-section Non Catlog starts -->
                <div class="card-deck-xl none_catelog d-none">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_nocatalog_request" id="product_nocatalog_request" action="/product/no_catalog" method="POST" enctype="multipart/form-data" data-parsley-validate>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Basic Information') ?></h5>
                                            <div class="form-group">
                                                <label for="ProductSKU"><?= _('SKU') ?></label>
                                                <input type="text" class="form-control" id="ProductSKU" name="ProductSKU" placeholder="Enter SKU" data-parsley-required-message="<?= _('Enter SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductSKU']) && !empty($form['ProductSKU'])) ? $form['ProductSKU'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductEAN"><?= _('EAN') ?></label>
                                                <input type="text" class="form-control" id="ProductEAN" name="ProductEAN" placeholder="Enter EAN" data-parsley-required-message="<?= _('Enter Product SKU') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductEAN']) && !empty($form['ProductEAN'])) ? $form['ProductEAN'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductBasePriceInput"><?= _('Base Price') ?></label>
                                                <input type="number" class="form-control" id="ProductBasePriceInput" name="ProductBasePriceInput" placeholder="Enter Product Base Price" value="<?php echo (isset($form['ProductBasePriceInput']) && !empty($form['ProductBasePriceInput'])) ? $form['ProductBasePriceInput'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="ProductType"><?= _('Type') ?></label>
                                                    </div>
                                                    <select class="custom-select" id="ProductType" name="ProductType">
                                                        <option value="" selected><?= _('Choose...') ?></option>
                                                        <option value="book" <?php echo (isset($form['ProductType']) && $form['ProductType'] == 'book') ? 'selected' : ''; ?>>Book</option>
                                                        <option value="music" <?php echo (isset($form['ProductType']) && $form['ProductType'] == 'music') ? 'selected' : ''; ?>>Music</option>
                                                        <option value="video" <?php echo (isset($form['ProductType']) && $form['ProductType'] == 'video') ? 'selected' : ''; ?>>Video</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductAuthor"><?= _('Author') ?></label>
                                                <input type="text" class="form-control" id="ProductAuthor" name="ProductAuthor" placeholder="Enter Author" data-parsley-required-message="<?= _('Enter Author') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductAuthor']) && !empty($form['ProductAuthor'])) ? $form['ProductAuthor'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <select name="Language" id="Language" class="browser-default custom-select market_language">
                                                    <option selected="">Select Language...</option>
                                                    <option value="english">English</option>
                                                    <option value="french">French</option>
                                                    <option value="german">German</option>
                                                    <option value="italian">Italian</option>
                                                    <option value="latin">Latin</option>
                                                    <option value="spanish">Spanish</option>
                                                </select>
                                            </div>
                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="ProductUPC"><?= _('UPC') ?></label>
                                                <input type="text" class="form-control" id="ProductUPC" name="ProductUPC" placeholder="Enter UPC" data-parsley-required-message="<?= _('Enter UPC') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductUPC']) && !empty($form['ProductUPC'])) ? $form['ProductUPC'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ProductTitle"><?= _('Title') ?></label>
                                                <input type="text" class="form-control" id="ProductTitle" name="ProductTitle" placeholder="Enter Title" data-parsley-required-message="<?= _('Enter Title') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductTitle']) && !empty($form['ProductTitle'])) ? $form['ProductTitle'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="ProductQty"><?= _('Qty') ?></label>
                                                <input type="number" class="form-control" id="ProductQty" name="ProductQty" placeholder="Enter Product Qty" data-parsley-required-message="<?= _('Enter Qty') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductQty']) && !empty($form['ProductQty'])) ? $form['ProductQty'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="ProductBindings"><?= _('Bindings') ?></label>
                                                    </div>
                                                    <select class="custom-select" id="ProductBindings" name="ProductBindings">
                                                        <option value="" selected><?= _('Choose...') ?></option>
                                                        <option value="accessory" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'accessory') ? 'selected' : ''; ?>>Accessory</option>
                                                        <option value="album" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'album') ? 'selected' : ''; ?>>Album</option>
                                                        <option value="bath_book" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'bath_book') ? 'selected' : ''; ?>>Bath Book</option>
                                                        <option value="board_book" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'board_book') ? 'selected' : ''; ?>>Board Book</option>
                                                        <option value="bonded_leather" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'bonded_leather') ? 'selected' : ''; ?>>Bonded Leather</option>
                                                        <option value="calendar" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'calendar') ? 'selected' : ''; ?>>Calendar</option>
                                                        <option value="card_book" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'card_book') ? 'selected' : ''; ?>>Card Book</option>
                                                        <option value="cards" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'cards') ? 'selected' : ''; ?>>Cards</option>
                                                        <option value="cassette" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'cassette') ? 'selected' : ''; ?>>Cassette</option>
                                                        <option value="cd_audio" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'cd_audio') ? 'selected' : ''; ?>>Audio CD</option>
                                                        <option value="cd_rom" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'cd_rom') ? 'selected' : ''; ?>>CD-ROM</option>
                                                        <option value="comic" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'comic') ? 'selected' : ''; ?>>Comic</option>
                                                        <option value="diary" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'diary') ? 'selected' : ''; ?>>Diary</option>
                                                        <option value="dvd_rom" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'dvd_rom') ? 'selected' : ''; ?>>DVD-ROM</option>
                                                        <option value="flexibound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'flexibound') ? 'selected' : ''; ?>>Flexibound</option>
                                                        <option value="foam_book" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'foam_book') ? 'selected' : ''; ?>>Foam Book</option>
                                                        <option value="game" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'game') ? 'selected' : ''; ?>>Game</option>
                                                        <option value="hardcover" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'hardcover') ? 'selected' : ''; ?>>Hardcover</option>
                                                        <option value="hardcover_comic" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'hardcover_comic') ? 'selected' : ''; ?>>Hardcover Comic</option>
                                                        <option value="hardcover_spiral" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'hardcover_spiral') ? 'selected' : ''; ?>>Hardcover Spiral</option>
                                                        <option value="imitation_leather" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'imitation_leather') ? 'selected' : ''; ?>>Imitation Leather</option>
                                                        <option value="journal" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'journal') ? 'selected' : ''; ?>>Journal</option>
                                                        <option value="leather_bound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'leather_bound') ? 'selected' : ''; ?>>Leather Bound</option>
                                                        <option value="library" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'library') ? 'selected' : ''; ?>>Library</option>
                                                        <option value="library_cd_audio" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'library_cd_audio') ? 'selected' : ''; ?>>Library Audio CD</option>
                                                        <option value="library_cd_mp3" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'library_cd_mp3') ? 'selected' : ''; ?>>Library MP3 CD</option>
                                                        <option value="loose_leaf" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'loose_leaf') ? 'selected' : ''; ?>>Loose Leaf</option>
                                                        <option value="map" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'map') ? 'selected' : ''; ?>>Map</option>
                                                        <option value="mass_market_paperback" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'mass_market_paperback') ? 'selected' : ''; ?>>Mass Market Paperback</option>
                                                        <option value="microfiche" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'microfiche') ? 'selected' : ''; ?>>Microfiche</option>
                                                        <option value="microfilm" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'microfilm') ? 'selected' : ''; ?>>Microfilm</option>
                                                        <option value="misc_supplies" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'misc_supplies') ? 'selected' : ''; ?>>Misc Supplies</option>
                                                        <option value="cd_mp3" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'cd_mp3') ? 'selected' : ''; ?>>MP3 CD</option>
                                                        <option value="pamphlet" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'pamphlet') ? 'selected' : ''; ?>>Pamphlet</option>
                                                        <option value="paperback" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'paperback') ? 'selected' : ''; ?>>Paperback</option>
                                                        <option value="paperback_bunko" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'paperback_bunko') ? 'selected' : ''; ?>>Paperback Bunko</option>
                                                        <option value="paperback_shinsho" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'paperback_shinsho') ? 'selected' : ''; ?>>Paperback Shinsho</option>
                                                        <option value="perfect_paperback" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'perfect_paperback') ? 'selected' : ''; ?>>Perfect Paperback</option>
                                                        <option value="plastic_comb" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'plastic_comb') ? 'selected' : ''; ?>>Plastic Comb</option>
                                                        <option value="pop_up" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'pop_up') ? 'selected' : ''; ?>>Pop-up</option>
                                                        <option value="poster" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'poster') ? 'selected' : ''; ?>>Poster</option>
                                                        <option value="preloaded_digital_audio_player" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'preloaded_digital_audio_player') ? 'selected' : ''; ?>>Pre-loaded Digitial Audio Player</option>
                                                        <option value="printed_access_code" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'printed_access_code') ? 'selected' : ''; ?>>Printed Access Code</option>
                                                        <option value="rag_book" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'rag_book') ? 'selected' : ''; ?>>Rag Book</option>
                                                        <option value="ring_bound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'ring_bound') ? 'selected' : ''; ?>>Ring Bound</option>
                                                        <option value="roughcut" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'roughcut') ? 'selected' : ''; ?>>Rough-cut</option>
                                                        <option value="school" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'school') ? 'selected' : ''; ?>>School</option>
                                                        <option value="sheet_music" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'sheet_music') ? 'selected' : ''; ?>>Sheet Music</option>
                                                        <option value="single_issue_magazine" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'pamphlet') ? 'single_issue_magazine' : ''; ?>>Single Issue Magazine</option>
                                                        <option value="spiral_bound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'spiral_bound') ? 'selected' : ''; ?>>Spiral Bound</option>
                                                        <option value="staple" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'staple') ? 'selected' : ''; ?>>Staple Bound</option>
                                                        <option value="stationery" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'stationery') ? 'selected' : ''; ?>>Stationery</option>
                                                        <option value="tankobon_hardcover" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'tankobon_hardcover') ? 'selected' : ''; ?>>Tankobon Hardcover</option>
                                                        <option value="tankobon_softcover" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'tankobon_softcover') ? 'selected' : ''; ?>>Tankobon Softcover</option>
                                                        <option value="textbook" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'textbook') ? 'selected' : ''; ?>>Textbook</option>
                                                        <option value="toy" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'toy') ? 'selected' : ''; ?>>Toy</option>
                                                        <option value="transparency" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'transparency') ? 'selected' : ''; ?>>Transparency</option>
                                                        <option value="turtleback" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'turtleback') ? 'selected' : ''; ?>>Turtleback</option>
                                                        <option value="unbound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'unbound') ? 'selected' : ''; ?>>Unbound</option>
                                                        <option value="unknown" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'unknown') ? 'selected' : ''; ?>>Unknown</option>
                                                        <option value="vinyl_bound" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'vinyl_bound') ? 'selected' : ''; ?>>Vinyl Bound</option>
                                                        <option value="wall_chart" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'wall_chart') ? 'selected' : ''; ?>>Wall Chart</option>
                                                        <option value="workbook" <?php echo (isset($form['ProductBindings']) && $form['ProductBindings'] == 'workbook') ? 'selected' : ''; ?>>Workbook</option>
                                                    </select>
                                                </div>
                                            </div>




                                            <div class="form-group">
                                                <label for="ProductPublisher"><?= _('Publisher') ?></label>
                                                <input type="text" class="form-control" id="ProductPublisher" name="ProductPublisher" placeholder="Enter Publisher" data-parsley-required-message="<?= _('Enter Publisher') ?>" data-parsley-group="fieldset01" required value="<?php echo (isset($form['ProductPublisher']) && !empty($form['ProductPublisher'])) ? $form['ProductPublisher'] : ''; ?>">
                                            </div>



                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary"><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!--  Non Catlog ends /.page -->

                <!-- .page-section Market prices starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_price" id="product_market_price" action="/product/market_price" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Market Specific Prices') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="AbeBooks"><?= _('AbeBooks') ?></label>
                                                <input type="number" class="form-control" id="AbeBooks" name="ProductNameInput" placeholder="Enter AbeBooks" data-parsley-group="fieldset01" value="<?php echo (isset($form['AbeBooks']) && !empty($form['AbeBooks'])) ? $form['AbeBooks'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Alibris"><?= _('Alibris') ?></label>
                                                <input type="number" class="form-control" id="Alibris" name="Alibris" placeholder="Enter Alibris" data-parsley-group="fieldset01" value="<?php echo (isset($form['Alibris']) && !empty($form['Alibris'])) ? $form['Alibris'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Amazon"><?= _('Amazon') ?></label>
                                                <input type="number" class="form-control" id="Amazon" name="Amazon" placeholder="Enter Amazon" data-parsley-group="fieldset01" value="<?php echo (isset($form['Amazon']) && !empty($form['Amazon'])) ? $form['Amazon'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonEurope"><?= _('AmazonEurope') ?></label>
                                                <input type="number" class="form-control" id="AmazonEurope" name="AmazonEurope" placeholder="Enter Amazon Europe" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonEurope']) && !empty($form['AmazonEurope'])) ? $form['AmazonEurope'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="BarnesAndNoble"><?= _('Barnes And Noble') ?></label>
                                                <input type="number" class="form-control" id="BarnesAndNoble" name="BarnesAndNoble" placeholder="Enter Barnes And Noble" data-parsley-group="fieldset01" value="<?php echo (isset($form['BarnesAndNoble']) && !empty($form['BarnesAndNoble'])) ? $form['BarnesAndNoble'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Biblio"><?= _('Biblio') ?></label>
                                                <input type="number" class="form-control" id="Biblio" name="Biblio" placeholder="Enter Biblio" data-parsley-group="fieldset01" value="<?php echo (isset($form['Biblio']) && !empty($form['Biblio'])) ? $form['Biblio'] : ''; ?>">
                                            </div>
                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="Chrislands"><?= _('Chrislands') ?></label>
                                                <input type="number" class="form-control" id="Chrislands" name="Chrislands" placeholder="Enter Chrislands" data-parsley-group="fieldset01" value="<?php echo (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBay"><?= _('eBay') ?></label>
                                                <input type="number" class="form-control" id="eBay" name="eBay" placeholder="Enter eBay" data-parsley-group="fieldset01" value="<?php echo (isset($form['Chrislands']) && !empty($form['Chrislands'])) ? $form['Chrislands'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="eCampus"><?= _('eCampus') ?></label>
                                                <input type="number" class="form-control" id="eCampus" name="eCampus" placeholder="Enter eCampus" data-parsley-group="fieldset01" value="<?php echo (isset($form['eCampus']) && !empty($form['eCampus'])) ? $form['eCampus'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="TextbookRush"><?= _('TextbookRush') ?></label>
                                                <input type="number" class="form-control" id="TextbookRush" name="TextbookRush" placeholder="Enter TextbookRush" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookRush']) && !empty($form['TextbookRush'])) ? $form['TextbookRush'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="TextbookX"><?= _('TextbookX') ?></label>
                                                <input type="number" class="form-control" id="TextbookX" name="TextbookX" placeholder="Enter TextbookX" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookX']) && !empty($form['TextbookX'])) ? $form['TextbookX'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="Valore"><?= _('Valore') ?></label>
                                                <input type="number" class="form-control" id="Valore" name="Valore" placeholder="Enter Valore" data-parsley-group="fieldset01" value="<?php echo (isset($form['Valore']) && !empty($form['Valore'])) ? $form['Valore'] : ''; ?>">
                                            </div>
                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_marketprice" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- marketprices /.page -->

                <!-- .page-section shipping_templates starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="product_market_template" id="product_market_template" action="/product/market_template" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Shipping Templates') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="DefaultTemplate"><?= _('Default Template') ?></label>
                                                <input type="text" class="form-control" id="DefaultTemplate" name="DefaultTemplate" placeholder="Enter Default Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['DefaultTemplate']) && !empty($form['DefaultTemplate'])) ? $form['DefaultTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AbeBooksTemplate"><?= _('AbeBooks Template') ?></label>
                                                <input type="text" class="form-control" id="AbeBooksTemplate" name="AbeBooksTemplate" placeholder="Enter AbeBooks Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AbeBooksTemplate']) && !empty($form['AbeBooksTemplate'])) ? $form['AbeBooksTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AlibrisTemplate"><?= _('Alibris Template') ?></label>
                                                <input type="text" class="form-control" id="AlibrisTemplate" name="AlibrisTemplate" placeholder="Enter Alibris Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AlibrisTemplate']) && !empty($form['AlibrisTemplate'])) ? $form['AlibrisTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonTemplate"><?= _('Amazon Template') ?></label>
                                                <input type="text" class="form-control" id="AmazonTemplate" name="AmazonTemplate" placeholder="Enter Amazon Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonTemplate']) && !empty($form['AmazonTemplate'])) ? $form['AmazonTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonEuropeTemplate"><?= _('AmazonEurope Template') ?></label>
                                                <input type="text" class="form-control" id="AmazonEuropeTemplate" name="AmazonEuropeTemplate" placeholder="Enter Amazon Europe Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonEuropeTemplate']) && !empty($form['AmazonEuropeTemplate'])) ? $form['AmazonEuropeTemplate'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="BarnesAndNobleTemplate"><?= _('Barnes And Noble Template') ?></label>
                                                <input type="text" class="form-control" id="BarnesAndNobleTemplate" name="BarnesAndNobleTemplate" placeholder="Enter Barnes And Noble Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['BarnesAndNobleTemplate']) && !empty($form['BarnesAndNobleTemplate'])) ? $form['BarnesAndNobleTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="BiblioTemplate"><?= _('Biblio Template') ?></label>
                                                <input type="text" class="form-control" id="BiblioTemplate" name="BiblioTemplate" placeholder="Enter Biblio Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['BiblioTemplate']) && !empty($form['BiblioTemplate'])) ? $form['BiblioTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsTemplate"><?= _('Chrislands Template') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsTemplate" name="ChrislandsTemplate" placeholder="Enter Chrislands Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayTemplate"><?= _('eBay Template') ?></label>
                                                <input type="text" class="form-control" id="eBayTemplate" name="eBayTemplate" placeholder="Enter eBay Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="eCampusTemplate"><?= _('eCampus Template') ?></label>
                                                <input type="text" class="form-control" id="eCampusTemplate" name="eCampusTemplate" placeholder="Enter eCampus Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eCampusTemplate']) && !empty($form['eCampusTemplate'])) ? $form['eCampusTemplate'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="TextbookRushTemplate"><?= _('TextbookRush Template') ?></label>
                                                <input type="text" class="form-control" id="TextbookRushTemplate" name="TextbookRushTemplate" placeholder="Enter TextbookRush Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookRushTemplate']) && !empty($form['TextbookRushTemplate'])) ? $form['TextbookRushTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="TextbookXTemplate"><?= _('TextbookX Template') ?></label>
                                                <input type="text" class="form-control" id="TextbookXTemplate" name="TextbookXTemplate" placeholder="Enter TextbookX Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookXTemplate']) && !empty($form['TextbookXTemplate'])) ? $form['TextbookXTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ValoreTemplate"><?= _('Valore Template') ?></label>
                                                <input type="text" class="form-control" id="ValoreTemplate" name="ValoreTemplate" placeholder="Enter Valore Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ValoreTemplate']) && !empty($form['ValoreTemplate'])) ? $form['ValoreTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsTemplate"><?= _('Chrislands Template') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsTemplate" name="ChrislandsTemplate" placeholder="Enter Chrislands Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsTemplate']) && !empty($form['ChrislandsTemplate'])) ? $form['ChrislandsTemplate'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayTemplate"><?= _('eBay Template') ?></label>
                                                <input type="text" class="form-control" id="eBayTemplate" name="eBayTemplate" placeholder="Enter eBay Template" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayTemplate']) && !empty($form['eBayTemplate'])) ? $form['eBayTemplate'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_market_template" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- shipping_templates /.page -->

                <!-- .page-section ebay shipping rates starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="market_ship_rates" id="market_ship_rates" action="/product/market_shiprates" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('eBay Shipping Rate Tables') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="Domestic"><?= _('Domestic Shipping Rate Table Id') ?></label>
                                                <input type="text" class="form-control" id="Domestic" name="Domestic" placeholder="Enter Domestic Shipping Rate Table Id" data-parsley-group="fieldset01" value="<?php echo (isset($form['Domestic']) && !empty($form['Domestic'])) ? $form['Domestic'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="International"><?= _('International Shipping Rate Table Id') ?></label>
                                                <input type="text" class="form-control" id="International" name="International" placeholder="Enter International Shipping Rate Table Id" data-parsley-group="fieldset01" value="<?php echo (isset($form['International']) && !empty($form['International'])) ? $form['International'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_market_shipping_rate" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- ebay shipping rates /.page -->

                <!-- .page-section handling time starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="market_hindling_time" id="market_hindling_time" action="/product/market_handletime" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Handling Time') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="DefaultHandlingTime"><?= _('Default Handling Time') ?></label>
                                                <input type="text" class="form-control" id="DefaultHandlingTime" name="DefaultHandlingTime" placeholder="Enter Default HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['DefaultHandlingTime']) && !empty($form['DefaultHandlingTime'])) ? $form['DefaultHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AbeBooksHandlingTime"><?= _('AbeBooks Handling Time') ?></label>
                                                <input type="text" class="form-control" id="AbeBooksHandlingTime" name="AbeBooksHandlingTime" placeholder="Enter AbeBooks HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['AbeBooksHandlingTime']) && !empty($form['AbeBooksHandlingTime'])) ? $form['AbeBooksHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AlibrisHandlingTime"><?= _('Alibris Handling Time') ?></label>
                                                <input type="text" class="form-control" id="AlibrisHandlingTime" name="AlibrisHandlingTime" placeholder="Enter Alibris HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['AlibrisHandlingTime']) && !empty($form['AlibrisHandlingTime'])) ? $form['AlibrisHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonHandlingTime"><?= _('Amazon Handling Time') ?></label>
                                                <input type="text" class="form-control" id="AmazonHandlingTime" name="AmazonHandlingTime" placeholder="Enter Amazon HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonHandlingTime']) && !empty($form['AmazonHandlingTime'])) ? $form['AmazonHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="AmazonEuropeHandlingTime"><?= _('AmazonEurope Handling Time') ?></label>
                                                <input type="text" class="form-control" id="AmazonEuropeHandlingTime" name="AmazonEuropeHandlingTime" placeholder="Enter Amazon Europe HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['AmazonEuropeHandlingTime']) && !empty($form['AmazonEuropeHandlingTime'])) ? $form['AmazonEuropeHandlingTime'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="BarnesAndNobleHandlingTime"><?= _('Barnes And Noble Handling Time') ?></label>
                                                <input type="text" class="form-control" id="BarnesAndNobleHandlingTime" name="BarnesAndNobleHandlingTime" placeholder="Enter Barnes And Noble HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['BarnesAndNobleHandlingTime']) && !empty($form['BarnesAndNobleHandlingTime'])) ? $form['BarnesAndNobleHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="BiblioHandlingTime"><?= _('Biblio Handling Time') ?></label>
                                                <input type="text" class="form-control" id="BiblioHandlingTime" name="BiblioHandlingTime" placeholder="Enter Biblio HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['BiblioHandlingTime']) && !empty($form['BiblioHandlingTime'])) ? $form['BiblioHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsHandlingTime"><?= _('Chrislands Handling Time') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsHandlingTime" name="ChrislandsHandlingTime" placeholder="Enter Chrislands HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsHandlingTime']) && !empty($form['ChrislandsHandlingTime'])) ? $form['ChrislandsHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayHandlingTime"><?= _('eBay Handling Time') ?></label>
                                                <input type="text" class="form-control" id="eBayHandlingTime" name="eBayHandlingTime" placeholder="Enter eBay HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayHandlingTime']) && !empty($form['eBayHandlingTime'])) ? $form['eBayHandlingTime'] : ''; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="eCampusHandlingTime"><?= _('eCampus Handling Time') ?></label>
                                                <input type="text" class="form-control" id="eCampusHandlingTime" name="eCampusHandlingTime" placeholder="Enter eCampus HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['eCampusHandlingTime']) && !empty($form['eCampusHandlingTime'])) ? $form['eCampusHandlingTime'] : ''; ?>">
                                            </div>


                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <label for="TextbookRushHandlingTime"><?= _('TextbookRush Handling Time') ?></label>
                                                <input type="text" class="form-control" id="TextbookRushHandlingTime" name="TextbookRushHandlingTime" placeholder="Enter TextbookRush HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookRushHandlingTime']) && !empty($form['TextbookRushHandlingTime'])) ? $form['TextbookRushHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="TextbookXHandlingTime"><?= _('TextbookX Handling Time') ?></label>
                                                <input type="text" class="form-control" id="TextbookXHandlingTime" name="TextbookXHandlingTime" placeholder="Enter TextbookX HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['TextbookXHandlingTime']) && !empty($form['TextbookXHandlingTime'])) ? $form['TextbookXHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ValoreHandlingTime"><?= _('Valore Handling Time') ?></label>
                                                <input type="text" class="form-control" id="ValoreHandlingTime" name="ValoreHandlingTime" placeholder="Enter Valore HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['ValoreHandlingTime']) && !empty($form['ValoreHandlingTime'])) ? $form['ValoreHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ChrislandsHandlingTime"><?= _('Chrislands Handling Time') ?></label>
                                                <input type="text" class="form-control" id="ChrislandsHandlingTime" name="ChrislandsHandlingTime" placeholder="Enter Chrislands HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['ChrislandsHandlingTime']) && !empty($form['ChrislandsHandlingTime'])) ? $form['ChrislandsHandlingTime'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="eBayHandlingTime"><?= _('eBay Handling Time') ?></label>
                                                <input type="text" class="form-control" id="eBayHandlingTime" name="eBayHandlingTime" placeholder="Enter eBay HandlingTime" data-parsley-group="fieldset01" value="<?php echo (isset($form['eBayHandlingTime']) && !empty($form['eBayHandlingTime'])) ? $form['eBayHandlingTime'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->

                                    </div> <!-- Row -->

                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_market_shipping_rate" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- handling time /.page -->

                <!-- .page-section Additional Information starts -->
                <div class="card-deck-xl">
                    <!-- .card-deck-xl starts -->
                    <div class="card card-fluid">
                        <!-- .card card-fluid starts -->

                        <div class="card-body">
                            <form name="market_additional_information" id="market_additional_information" action="/product/market_additional_info" method="POST">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <h5 class="card-title"><?= _('Additional Information') ?></h5>
                                            <input type="hidden" class="form-control" id="Id" name="Id" value="">
                                            <div class="form-group">
                                                <label for="Cost"><?= _('Cost') ?></label>
                                                <input type="text" class="form-control" id="Cost" name="Cost" placeholder="Enter Cost" data-parsley-group="fieldset01" value="<?php echo (isset($form['Cost']) && !empty($form['Cost'])) ? $form['Cost'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Location"><?= _('Location') ?></label>
                                                <input type="text" class="form-control" id="Location" name="Location" placeholder="Enter Location" data-parsley-group="fieldset01" value="<?php echo (isset($form['Location']) && !empty($form['Location'])) ? $form['Location'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Brand"><?= _('Brand') ?></label>
                                                <input type="text" class="form-control" id="Brand" name="Brand" placeholder="Enter Brand" data-parsley-group="fieldset01" value="<?php echo (isset($form['Brand']) && !empty($form['Brand'])) ? $form['Brand'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->

                                        <div class="col-sm">
                                            <h5 class="card-title">&nbsp;</h5>
                                            <div class="form-group">
                                                <select name="Language" id="Language" class="browser-default custom-select market_language">
                                                    <option selected="">Select Language...</option>
                                                    <option value="english">English</option>
                                                    <option value="french">French</option>
                                                    <option value="german">German</option>
                                                    <option value="italian">Italian</option>
                                                    <option value="latin">Latin</option>
                                                    <option value="spanish">Spanish</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Source"><?= _('Source') ?></label>
                                                <input type="text" class="form-control" id="Source" name="Source" placeholder="Enter Source" data-parsley-group="fieldset01" value="<?php echo (isset($form['Source']) && !empty($form['Source'])) ? $form['Source'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="Category"><?= _('Category') ?></label>
                                                <input type="text" class="form-control" id="Category" name="Category" placeholder="Enter Category" data-parsley-group="fieldset01" value="<?php echo (isset($form['Category']) && !empty($form['Category'])) ? $form['Category'] : ''; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="ManufacturerPartNumber"><?= _('Manufacturer Part Number') ?></label>
                                                <input type="text" class="form-control" id="ManufacturerPartNumber" name="ManufacturerPartNumber" placeholder="Enter Manufacturer Part Number" data-parsley-group="fieldset01" value="<?php echo (isset($form['ManufacturerPartNumber']) && !empty($form['ManufacturerPartNumber'])) ? $form['ManufacturerPartNumber'] : ''; ?>">
                                            </div>

                                        </div> <!-- col-sm -->
                                    </div> <!-- Row -->
                                    <div class="form-group">
                                        <label for="AdditionalUIEE"><?= _('Additional UIEE') ?></label>
                                        <textarea class="form-control" id="AdditionalUIEE" name="AdditionalUIEE" rows="3"><?php echo (isset($form['AdditionalUIEE']) && !empty($form['AdditionalUIEE'])) ? $form['AdditionalUIEE'] : ''; ?></textarea>
                                    </div>
                                </div> <!-- Container -->

                                <button type="submit" class="btn btn-primary btn_market_shipping_rate" disabled><?= _('Submit') ?></button>
                            </form>
                        </div> <!-- Card Body -->

                    </div><!-- /.page-inner -->
                </div><!-- Additional Information /.page -->
            </div><!-- /.wrapper -->
            <?= $this->stop() ?>

            <?php $this->start('plugin_js') ?>
            <script src="/assets/vendor/pace/pace.min.js"></script>
            <script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
            <script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
            <script src="/assets/javascript/pages/product.js"></script>
            <?= $this->stop() ?>

            <?php $this->start('page_js') ?>
            <?= $this->stop() ?>

            <?php $this->start('footer_extras') ?>
            <script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
            <?php $this->stop() ?>