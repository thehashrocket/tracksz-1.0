<?php
$title_meta = 'Marketplace Add for Your Tracksz Store, a Multiple Market Product Management Service';
$description_meta = 'Marketplace Add for your Tracksz Store, a Multiple Market Product Management Service';
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
                    <h1 class="page-title"> <?=_('Add Marketplace')?> </h1>
                    <p> <a href="/account/panel"><?=_('Dashboard')?></a> / <a href="/marketplace/dashboard"> <?=_('Marketplace')?></a> </p>
                </div>
                <p class="text-muted">
                    <?=_('This is where you can add, modify, and delete Marketplace for the current Active Store: ')?><strong>
                        <?=\Delight\Cookie\Cookie::get('tracksz_active_name')?></strong></p>
                <?php if (isset($alert) && $alert): ?>
                <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                <?php endif?>
            </header><!-- /.page-title-bar -->
            <!-- Horizontal Steppers -->
<div class="row">
  <div class="col-md-12">

  <div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
    Step 2 Of 3
  </div>
</div>

  </div>
</div>
<!-- /.Horizontal Steppers -->
            <div class="card-body">
                <form name="category_market_request" id="category_market_request" action="dashboard/step3/thanks"
                    method="POST" enctype="multipart/form-data" data-parsley-validate>
                    <div class="container">

                        <div class="row">
                            <div class="col-sm">
                            <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Marketplace Credentials</h5>
                                <div class="form-group">
                                    <label for="FtpId">FTP ID</label>
                                    <input type="text" class="form-control" id="FtpId" name="FtpId" placeholder="Enter Ftp Id" data-parsley-required-message="<?=_('Enter Ftp Id')?>" data-parsley-group="fieldset01" required <?php if (isset($store['FtpId'])) {
                                        echo ' value="' . $store['FtpId'] . '"'; } ?>>
                                </div>
                                <div class="form-group">
                                    <label for="FtpPwd">FTP Pwd</label>
                                    <input type="password" class="form-control" id="FtpPwd" name="FtpPwd" placeholder="Enter Ftp Password" data-parsley-required-message="<?=_('Enter Ftp Password')?>" data-parsley-group="fieldset01" required <?php if (isset($store['FtpPwd'])) {
                                        echo ' value="' . $store['FtpPwd'] . '"'; } ?>>
                                    <small id="FtpHelp" class="form-text text-muted" aria-describedby="FtpHelp">Note: Value will always appear as asterisks for security purposes</small>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div> <!-- Row -->

                        <!-- Columns are always 50% wide, on mobile and desktop -->
                        <div class="row">
                            <div class="col-12">
                                <h5>Marketplace Settings</h5>
                            </div>   
                        <div class="col-6">
                                <div class="form-group">
                                        <label for="PrependVenue">Prepend venue-specific text to item note:</label>
                                        <input type="text" class="form-control" id="PrependVenue" name="PrependVenue" placeholder="Enter Prepend Venue" data-parsley-required-message="<?=_('Enter Prepend Venue')?>" data-parsley-group="fieldset01" required <?php if (isset($store['PrependVenue'])) {
                                                         echo ' value="' . $store['PrependVenue'] . '"';
                                                 } ?>>

                                </div>
                                <div class="form-group">
                                    <label for="AppendVenue">Append venue-specific text to item note:</label>
                                    <input type="text" class="form-control" id="AppendVenue" name="AppendVenue" placeholder="Enter Ftp Append Venue" data-parsley-required-message="<?=_('Enter Append Venue')?>" data-parsley-group="fieldset01" required <?php if (isset($store['AppendVenue'])) {
                            echo ' value="' . $store['AppendVenue'] . '"';  } ?>>
                                </div>
                        </div> <!-- col-6 -->
                        <div class="col-6">   
                            <div class="form-group">
                                <label for="PrependVenue">File Format</label>
                                <input type="text" class="form-control" id="PrependVenue" name="PrependVenue" placeholder="Enter Prepend Venue" data-parsley-required-message="<?=_('Enter Prepend Venue')?>" data-parsley-group="fieldset01" required <?php if (isset($store['PrependVenue'])) {
                                        echo ' value="' . $store['PrependVenue'] . '"'; } ?>>
                            </div>
                            
                            <div class="form-group">
                                <label for="AppendVenue">Order retrieval interval:</label>
                                <input type="text" class="form-control" id="AppendVenue" name="AppendVenue" placeholder="Enter Ftp Append Venue" data-parsley-required-message="<?=_('Enter Append Venue')?>" data-parsley-group="fieldset01" required <?php if (isset($store['AppendVenue'])) {
                                        echo ' value="' . $store['AppendVenue'] . '"'; } ?>>
                                        <small id="FtpHelp" class="form-text text-muted" aria-describedby="FtpHelp">Enter off to turn off automatic retrieval</small>
                            </div>
                            <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck">
                                            <label class="form-check-label" for="gridCheck">
                                                Suspend exports
                                            </label>
                                    </div>
                            </div>

                            <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck">
                                            <label class="form-check-label" for="gridCheck">
                                            Send deletes only
                                            </label>
                                    </div>
                            </div>
                                        
                        </div>  <!-- col-6 -->
                        </div> <!-- row -->


                        <div class="row">
                            <div class="col-12">
                                 <h5>Currency Settings</h5>
                            </div>   
                            <div class="col-6">
                                <div class="form-group">
                                     <label for="PrependVenue">Marketplace accepts prices in:</label>
                                        <select class="browser-default custom-select">                                                
                                            <option value="USD" selected>US Dollars</option>
                                        </select>
                                </div>
                            </div>
                            <div class="col-6">                                     
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">( Price +</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="0.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text">) X</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="1.00000">
                                </div>
                                        
                                <div class="form-group">
                                    <p class="market_price"><a href="http://www.fillz.com/help/glossary.html#m_market_specific_prices" target="_blank">Market-specific price</a>                                             
                                    <select class="browser-default market_price custom-select">                                                
                                        <?php
                                            if (isset($market_price) && !empty($market_price)) {
                                                     foreach ($market_price as $mar_key => $mar_val) { ?>                                  
                                                        <option value="<?php echo $mar_val; ?>"><?php echo $mar_val; ?></option>
                                                    <?php }} else {?>
                                                            <option selected>No Market Price found...</option>
                                                        <?php }?>
                                    </select>
                                    <span>will be sent as:</span>
                                    </p>
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">( Price +</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="0.00">
                                    <div class="input-group-append">
                                        <span class="input-group-text market_price_wise">) X</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" value="1.00000">
                                </div>
                            </div>
                        </div> <!-- row -->
                        <button type="submit" class="btn btn-primary">Next <i class="fa fa-arrow-right"></i></button>
                    </div> <!-- Container -->
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
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<?php $this->stop()?>