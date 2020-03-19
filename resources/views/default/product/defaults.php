<?php
$title_meta = 'Inventory Defaults for Your Store Product Listings, a Multiple Market Inventory Management Service';
$description_meta = 'Inventory Defaults for your store\'s product listings at Tracksz, a Multiple Market Inventory Management Service';
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
                    <h1 class="page-title"> <?=_('Inventory Default Settings')?> </h1>
                </div>
                <p class="text-muted"> <?=_('Configure default settings for your Active Store: ')?><strong> <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <p class="text-muted"> <?=_('Default settings are used to pre-fill certain form fields when you add new inventory.  They are also used on items in an upload file that do not contain certain data values.')?> <i><?=_('Form fields with default values can be changed when adding inventory.')?> </i></p>
            <?php if(isset($alert) && $alert):?>
                 <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
            <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=\Delight\Cookie\Cookie::get('tracksz_active_name')?> - <?=_('Inventory Defaults')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <!-- form -->
                        <form method="post" action="/inventory/defaults" id="inventory-defaults" data-parsley-validate="">
                            <!-- fieldset -->
                            <fieldset>
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="producttype" class="form-label required-field"><?=_('Main Product Type')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Product type determines the use and labeling of some fields and if any other fields are required. You may select different product types when adding a product.')?>"></i></label>
                                        <select name="Type" class="custom-select form-control" id="producttype" title="<?=_('What is your main product type for this store.')?>" data-parsley-required-message="<?=_('Please select main product type.')?>" required>
                                            <?php if(!isset($defaults['Type'])): ?>
                                                <option value="" selected><?=_('Select one...')?></option>
                                            <?php endif; ?>
                                            <?php foreach($types as $type): ?>
                                                <option value="<?=$type['Id']?>"
                                                    <?php if(isset($defaults['Type']) && $defaults['Type'] == $type['Id']): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$type['Name']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="taxclass" class="form-label required-field"><?=_('Main Product Tax Class')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('What tax class applies to most of your inventory items? Default is taxable Product.')?>"></i></label>
                                        <select name="TaxClassId" class="custom-select form-control" id="taxclass" title="<?=_('What is your main product tax class for this store.')?>" data-parsley-required-message="<?=_('Please select main product tax class.')?>" required>
                                            <?php
                                                $tax = 1;
                                                if(isset($defaults['TaxClassId'])) {
                                                    $tax = $defaults['TaxClassId'];
                                                }
                                            ?>
                                            <?php foreach($settings['tax_class'] as $key => $taxes): ?>
                                                <option value="<?=$key?>"
                                                    <?php if($tax == $key): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$taxes?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="subtractstock" class="form-label required-field"><?=_('Subtract Stock')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('When an item is ordered, subtract the order quantity from your inventory.  The default is Yes.')?>"></i></label>
                                        <select name="Subtract" class="custom-select form-control" id="subtractstock" title="<?=_('Subtract Quantity from Order.')?>" data-parsley-required-message="<?=_('Subtract Quantity from Order.')?>" required>
                                            <?php
                                                $subtract_option = ['Yes' => 1, 'No' => 0];
                                                $subtract = 1;
                                                if(isset($defaults['Subtract'])) {
                                                    $subtract = $defaults['Subtract'];
                                                }
                                                foreach($subtract_option as $key => $val): ?>
                                                    <option value="<?=$val?>"
                                                        <?php if($subtract== $val): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=$key?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="zeroquantity" class="form-label required-field"><?=_('Delete Zero Quantity')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('When an item\'s quantity reaches 0 (zero) delete it from inventory? Default is Delete.')?>"></i></label>
                                        <select name="DeleteZero" class="custom-select form-control" id="zeroquantity" title="<?=_('Delete Zero Quantity Inventory Items.')?>" data-parsley-required-message="<?=_('Delete Zero Quantity Inventoy Items.')?>" required>
                                            <?php
                                            $delete_option = ['Yes' => 1, 'No' => 0];
                                            $delete = 1;
                                            if(isset($defaults['DeleteZero'])) {
                                                $delete = $defaults['DeleteZero'];
                                            }
                                            foreach($delete_option as $key => $val): ?>
                                                <option value="<?=$val?>"
                                                    <?php if($delete == $val): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$key?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="stockstatus" class="form-label required-field"><?=_('Out of Stock Status')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Only applies if you do not delete 0 (zero) quantity inventory.  If an item\'s quantity is zero what default Status do you want displayed.')?>"></i></label>
                                        <select name="StockStatusId" class="custom-select form-control" id="stockstatus" title="<?=_('Out of stock status.')?>" data-parsley-required-message="<?=_('Out of Stock Status')?>" required>
                                        <?php
                                            $stock_status = 1;
                                            if(isset($defaults['StockStatusId'])) {
                                                $stock_status = $defaults['StockStatusId'];
                                            }
                                            foreach($settings['stock_status'] as $key => $status): ?>
                                            <option value="<?=$key?>"
                                                <?php if($stock_status == $key): ?>
                                                    selected
                                                <?php endif; ?>
                                            ><?=$status?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="condition" class="form-label required-field"><?=_('Default Condition')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('What condition does your inventory items primarily meet.')?>"></i></label>
                                        <select name="Condition" class="custom-select form-control" id="condition" title="<?=_('Inventory Item Condition')?>" data-parsley-required-message="<?=_('Inventory Item Condition')?>" required>
                                            <?php
                                            $condition = 1;
                                            if(isset($defaults['Condition'])) {
                                                $condition = $defaults['Condition'];
                                            }
                                            foreach($settings['conditions'] as $key => $cond): ?>
                                                <option value="<?=$key?>"
                                                    <?php if($condition == $key): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$cond?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /.form row -->
                            </fieldset> <!-- /fieldset -->
                            <hr class="mb-3">
                            <!-- fieldset -->
                            <fieldset>
                                <strong><?=_('Dimensions & Weight')?></strong> <!-- .form-group -->
                                <p><?=_('Default product length, width, height, and weight based on the length and weight unit of measures you select.')?></p>
                            </fieldset> <!-- /fieldset -->
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="lengthuom" class="form-label required-field"><?=_('Length Unit of Measure')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('What unit of measure do you use for your length measurements.')?>"></i></label>
                                        <select name="LengthClassId" class="custom-select form-control" id="lengthuom" title="<?=_('Length Unit of Measure')?>" data-parsley-required-message="<?=_('Length Unit of Measure')?>" required>
                                            <?php
                                                $length = 1;
                                                if(isset($defaults['LengthClassId'])) {
                                                    $length = $defaults['LengthClassId'];
                                            }
                                            foreach($settings['length_class'] as $key => $val): ?>
                                                <option value="<?=$key?>"
                                                    <?php if($length == $key): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$val['Title']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /.form row -->
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="length" class="required-field"><?=_('Length')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the default length of your items. You might consider using an average length here.')?>"></i></label> <input type="text" class="form-control" id="length" name="Length" title="<?=_('Enter the default length of your items. You might consider using an average length here.')?>" data-parsley-required-message="<?=_('Enter the default length of your items.')?>" required value="<?php if (isset($defaults['Length'])) echo $defaults['Length']; else echo 1;?>">
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="width" class="required-field"><?=_('Width')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the default width of your items. You might consider using an average width here.')?>"></i></label> <input type="text" class="form-control" id="width" name="Width" title="<?=_('Enter the default width of your items. You might consider using an average width here.')?>" data-parsley-required-message="<?=_('Enter the default width of your items.')?>" required value="<?php if (isset($defaults['Width'])) echo $defaults['Width']; else echo 1;?>">
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="height" class="required-field"><?=_('Height')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the default height of your items. You might consider using an average height here.')?>"></i></label> <input type="text" class="form-control" id="height" name="Height" title="<?=_('Enter the default height of your items. You might consider using an average height here.')?>" data-parsley-required-message="<?=_('Enter the default height of your items.')?>" required value="<?php if (isset($defaults['Height'])) echo $defaults['Height']; else echo 1;?>">
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row mb-2">
                                <!-- form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="weightuom" class="form-label required-field"><?=_('Weight Unit of Measure')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('What unit of measure do you use for your weights.')?>"></i></label>
                                        <select name="WeightClassId" class="custom-select form-control" id="weightuom" title="<?=_('Weight Unit of Measure')?>" data-parsley-required-message="<?=_('Weight Unit of Measure')?>" required>
                                            <?php
                                            $weight = 4;
                                            if(isset($defaults['WeightClassId'])) {
                                                $weight = $defaults['WeightClassId'];
                                            }
                                            foreach($settings['weight_class'] as $key => $val): ?>
                                                <option value="<?=$key?>"
                                                    <?php if($weight == $key): ?>
                                                        selected
                                                    <?php endif; ?>
                                                ><?=$val['Title']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                                <div class="col-md-6">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="weight" class="required-field"><?=_('Weight')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the default weight of your items. You might consider using an average weight here.')?>"></i></label> <input type="text" class="form-control" id="weight" name="Weight" title="<?=_('Enter the default weight of your items. You might consider using an average weight here.')?>" data-parsley-required-message="<?=_('Enter the default weight of your items.')?>" required value="<?php if (isset($defaults['Weight'])) echo $defaults['Weight']; else echo 1;?>">
                                    </div><!-- /.form-group -->
                                </div> <!-- /.form column -->
                            </div><!-- /.form row -->
    
                            <hr>
                            <!-- .form-actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary ml-auto"><?=_('Update Defaults');?></button>
                            </div><!-- /.form-actions -->
                        </form><!-- /form -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div> <!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/pace/pace.min.js"></script>
<script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
<script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?=$this->stop()?>