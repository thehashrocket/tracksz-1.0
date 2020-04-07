<?php
$title_meta = 'Add/Edit Store at Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Add/Edit Stores at Tracksz, a Multiple Market Inventory Management Service';
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
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/account/stores" title="Tracksz Member's Stores"><?=('Stores')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Add')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <h1 class="page-title"> <?=_('Store')?> </h1>
                <p class="text-muted"> <?=_('Adding a store changes the "Active" store to the new store added. If this is not what you want, you will have to change the Active in the Stores listing after you add this store.')?></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header><!-- /.page-title-bar -->
            <!-- .page-section -->
            <div class="page-section">
                <!-- .section-block -->
                <div class="section-block">
                    <!-- Default Steps -->
                    <!-- .bs-stepper -->
                    <div id="stepper" class="bs-stepper">
                        <!-- .card -->
                        <div class="card">
                            <!-- .card-header -->
                            <div class="card-header">
                                <!-- .steps -->
                                <div class="steps steps-" role="tablist">
                                    <ul>
                                        <li class="step" data-target="#test-l-1">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-shopping-cart"></i></span> <span class="d-none d-sm-inline"><?=_('Store')?></span></a>
                                        </li>
                                        <li class="step" data-target="#test-l-2">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="fa fa-list-alt"></i></span> <span class="d-none d-sm-inline"><?=_('Details')?></span></a>
                                        </li>
                                        <li class="step" data-target="#test-l-3">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="oi oi-credit-card"></i></span> <span class="d-none d-sm-inline"><?=_('Payment')?></span></a>
                                        </li>
                                        <li class="step" data-target="#test-l-4">
                                            <a href="#" class="step-trigger" tabindex="-1"><span class="step-indicator step-indicator-icon"><i class="oi oi-check"></i></span> <span class="d-none d-sm-inline"><?=_('Confirm')?></span></a>
                                        </li>
                                    </ul>
                                </div><!-- /.steps -->
                            </div><!-- /.card-header -->
                            <!-- .card-body -->
                            <div class="card-body">
                            
                    <!-- .form -->
                    <form id="stepper-form" action="/account/store" method="post">
                        <input type="hidden" name="MemberId" value="<?=$MemberId?>">
                        <?php if(isset($store['Id'])): ?>
                            <input type="hidden" name="Id" value="<?=$store['Id']?>">
                        <?php endif; ?>
                        <!-- .content -->
                        <div id="test-l-1" class="content dstepper-none fade">
                            <!-- fieldset -->
                            <fieldset>
                                <legend><?=_('Store Information')?></legend>
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="legalname" class="required-field"><?=_('Legal Name')?></label> <input type="text" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the store\'s Legal Name. Must be unique to your member account.')?>" class="form-control" id="legalname" name="LegalName" placeholder="<?=_('My Superior Store')?>" data-parsley-required-message="<?=_('Please insert store\'s legal name.')?>" data-parsley-group="fieldset01" required maxlength="75" <?php if (isset($store['LegalName'])) echo ' value="' . $store['LegalName'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="dba"><?=_('Doing Business As')?></label> <input type="text" title="<?=_('Optional. Enter the store\'s DBA if different from legal name.')?>" class="form-control" id="dba" name="DBA" placeholder="<?=_('My Store\'s Other Name')?>" maxlength="75" <?php if (isset($store['DBA'])) echo ' value="' . $store['DBA'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-12">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="displayname" class="required-field"><?=_('Display Name')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('This must be unique to your member account.  Enter a name you\'d like displayed on Tracksz if that occurs.')?>"></i></label> <input type="text" title="<?=_('Enter name to display on Tracksz.')?>" class="form-control" id="displayname" name="DisplayName" placeholder="<?=_('My Superior Store')?>" data-parsley-required-message="<?=_('Please insert store\'s display name.')?>" data-parsley-group="fieldset01" required maxlength="75" <?php if (isset($store['DisplayName'])) echo ' value="' . $store['DisplayName'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="storetype" class="form-label required-field"><?=_('Business Type')?></label>
                                            <select name="Type" class="custom-select form-control" id="storetype" title="<?=_('The legal type for this store.')?>" data-parsley-group="fieldset01" data-parsley-required-message="<?=_('Please select store type.')?>" required>
                                                <?php if(!isset($store['Type'])): ?>
                                                    <option value="" selected><?=_('Select one...')?></option>
                                                <?php endif; ?>
                                                <?php foreach($storeTypes as $type => $label): ?>
                                                    <option value="<?=$type?>"
                                                        <?php if(isset($store['Type']) && $store['Type'] == $type): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=$label?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="TaxId" class="required-field"><?=_('Tax Identification (EIN/SSN)')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the identification number used when filing taxes for this store.')?>"></i></label>
                                            <input type="text" title="<?=_('Enter the identification number used when filing taxes for this store.')?>" class="form-control" id="TaxId" name="TaxId" data-parsley-required-message="<?=_('Please insert your tax identification number for this store.')?>" data-parsley-group="fieldset01" required maxlength="75"<?php if (isset($store['TaxId'])) echo ' value="' . $store['TaxId'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-3">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label class="required-field" for="Email"><?=_('Store\'s Email')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the store\'s valid email address. It does not have to be unique.')?>"></i></label> <input name="Email" type="email" class="form-control" id="Email" title="<?=_('Enter a valid email for this store.')?>"  data-parsley-required-message="<?=_('Please insert a valid email.')?>" required data-parsley-type="email" data-parsley-group="fieldset01" maxlength="191"<?php if (isset($store['Email'])) echo ' value="' . $store['Email'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="telephone" class="required-field"><?=_('Store\'s Telephone')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the Store\'s full Telephone Number without Country code. Only Enter Numbers.')?>"></i></label> <input type="text" title="<?=_('Store\'s Telephone Number. Only Enter Numbers.')?>" class="form-control" id="telephone" name="Telephone" placeholder="1234567890" data-parsley-required-message="<?=_('Please insert store\'s telephone number.')?>" required data-parsley-type="integer" data-parsley-group="fieldset01" data-parsley-minlength="10" data-parsley-maxlength="31"<?php if (isset($store['Telephone'])) echo ' value="' . $store['Telephone'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                            </fieldset>
                            <hr class="mb-3">
                            <fieldset>
                                <legend><?=_('Store Primary Contact')?></legend> <!-- .form-group -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-12">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="contactfullname" class="required-field"><?=_('Contact\'s Name')?></label> <input type="text" title="<?=_('Enter contact\'s name.')?>" class="form-control" id="contactfullname" name="ContactFullName" placeholder="Joey Doe" data-parsley-required-message="<?=_('Please insert contact\'s name.')?>" data-parsley-group="fieldset01" required maxlength="100" <?php if (isset($store['ContactFullName'])) echo ' value="' . $store['ContactFullName'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-3">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label class="required-field" for="ContactEmail"><?=_('Contact\'s Email')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the store contact\'s valid email address. It does not have to be unique.')?>"></i></label> <input name="ContactEmail" type="email" class="form-control" id="ContactEmail" title="<?=_('Enter a valid email for this contact.')?>" data-parsley-required-message="<?=_('Please insert contact\'s email address.')?>" required data-parsley-group="fieldset01" data-parsley-type="email"  maxlength="191"<?php if (isset($store['ContactEmail'])) echo ' value="' . $store['ContactEmail'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="ContactTelephone" class="required-field"><?=_('Contact\'s Telephone')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the Contact\'s full Telephone Number without Country code. Only Enter Numbers.')?>"></i></label> <input type="text" title="<?=_('Contact\'s Telephone Number. Only Enter Numbers.')?>" class="form-control" id="ContactTelephone" name="ContactTelephone" placeholder="1234567890"  data-parsley-required-message="Please insert contact's email address." required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="31"<?php if (isset($store['ContactTelephone'])) echo ' value="' . $store['ContactTelephone'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                            </fieldset>
                            <hr class="mb-3">
                            <fieldset>
                                <legend><?=_('Store Address')?></legend> <!-- .form-group -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="address" class="required-field"><?=_('Address')?></label> <input type="text"  title="<?=_('Enter Store\'s First Address Line')?>" class="form-control" id="address" name="Address1" placeholder="<?=_('123 Any Lane')?>" data-parsley-required-message="<?=_('Please insert store\'s first address line.')?>" data-parsley-group="fieldset01" required maxlength="128"<?php if (isset($store['Address1'])) echo ' value="' . $store['Address1'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="address2"><?=_('Address 2')?></label> <input type="text" title="<?=_('Optional. Enter Store\'s Second Address Line.')?>" class="form-control" id="address2" name="Address2" placeholder="<?=_('Suite, Apt, Attn')?>"  maxlength="128"<?php if (isset($store['Address2'])) echo ' value="' . $store['Address2'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="city" class="required-field"><?=_('City')?></label> <input type="text" title="<?=_('Enter Store\'s City')?>" class="form-control" id="city" name="City" placeholder="<?=_('City Name')?>" data-parsley-required-message="<?=_('Please insert store\'s city name.')?>" data-parsley-group="fieldset01" required maxlength="128" <?php if (isset($store['City'])) echo ' value="' . $store['City'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="zipcode" class="required-field"><?=_('Zip/Postal Code')?></label> <input type="text" title="<?=_('Enter Store\'s Zip/Post Code')?>" class="form-control" id="zipcode" name="PostCode" placeholder="12345" data-parsley-required-message="<?=_('Please insert store\'s zip/post code.')?>" data-parsley-group="fieldset01" required data-parsley-length="[4, 10]" <?php if (isset($store['PostCode'])) echo ' value="' . $store['PostCode'] .'"' ?>>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="CountryId" class="required-field"><?=_('Country')?></label>
                                            <select name="CountryId" id="CountryId" class="form-control" title="<?=_('Select Store\'s  Country')?>">
                                                <option value=""><?=_('-- Please Select---')?></option>
                                                <?php foreach ($countries as $country): ?>
                                                    <?php if($country['Id'] == $store['CountryId']): ?>
                                                        <option value="<?=$country['Id']?>" selected="selected"><?=$country['Name']?></option>
                                                    <?php else: ?>
                                                        <option value="<?=$country['Id']?>"><?=$country['Name']?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div><!-- /.form-group -->
                                    </div>
                                    <!-- form column -->
                                    <div class="col-md-6">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="ZoneId" class="required-field"><?=_('State/Region')?></label>
                                            <select name="ZoneId" id="ZoneId" class="form-control" title="<?=_('Select Store\'s State or Region')?>">
                                            </select>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                            </fieldset>
                            <hr class="mb-3">
                            <fieldset>
                                <legend><?=_('Notification')?></legend>
                                <div class="col-md-8 list-group list-group-flush">
                                    <!-- .list-group-item -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center"><?=_('Receive emails for orders and from customers?')?>
                                        <!-- .switcher -->
                                        <label class="switcher-control switcher-control-lg"  data-toggle="tooltip" data-placement="left" title="<?=_('Slide bar on to receive order emails and communication from customers.')?>"><input type="checkbox" name="Newsletter" class="switcher-input" <?php if (isset($store['Newsletter']) && $store['Newsletter']=='on') echo ' checked'?>> <span class="switcher-indicator"></span> <span class="switcher-label-on">ON</span> <span class="switcher-label-off">OFF</span> </label> <!-- /.switcher -->
                                    </div>
                                    <!-- .list-group-item -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center"><?=_('Use Text messaging for Order Confirmation to Customers, if they allow it?')?>
                                        <!-- .switcher -->
                                        <label class="switcher-control switcher-control-lg"  data-toggle="tooltip" data-placement="left" title="<?=_('Slide bar on for Text message order confirmation.')?>"><input type="checkbox" name="Texts" class="switcher-input" <?php if (isset($store['Texts']) && $store['Texts']=='on') echo ' checked'?>> <span class="switcher-indicator"></span><span class="switcher-label-on">ON</span> <span class="switcher-label-off">OFF</span></label> <!-- /.switcher -->
                                    </div><!-- /.list-group-item -->
                                </div>
                                <hr class="mt-5">
                                <!-- .d-flex -->
                                <div class="d-flex">
                                    <p><a href="/account/stores"><?=_('Return to Store List')?></a> </p><button type="button" class="next btn btn-primary ml-auto"  data-validate="fieldset01"><?=_('Next Step')?></button>
                                </div><!-- /.d-flex -->
                                <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Form Data is NOT saved until Last Step.')?></div>
                            </fieldset>
                        </div><!-- /.content -->
                        <!-- .content -->
                        <div id="test-l-2" class="content dstepper-none fade">
                            <fieldset>
                                <legend><?=_('Store Details')?></legend>
                                <!-- .row -->
                                <div class="form-row">
                                    <div class="col-md-12 list-group list-group-flush">
                                        <p><?=_('Tracksz offers a full ecommerce listing service that is completely compatible with Tracksz Inventory and Order Management. There is no additional costs other than a per sale commission fee based on your fee level. You are already here, might was well list with Tracksz too!')?> <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#stripeModalCenter">Requires Stripe Account</button></p>
                                    </div>
                                </div><!-- /form row -->
                                <!-- .row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-6 list-group list-group-flush">
                                        <!-- .list-group-item -->
                                        <div class="list-group-item d-flex justify-content-between align-items-center"><?=_('Include your products on Tracksz Listing Site?')?>
                                            <!-- .switcher -->
                                            <label class="switcher-control switcher-control-lg" title="<?=_('Slide bar on or off to list your products on Tracksz Listing Site or not.')?>"> <input type="checkbox" name="TrackszListing" class="switcher-input" <?php if (isset($store['TrackszListing']) && $store['TrackszListing']=='on') echo ' checked'?>> <span class="switcher-indicator"></span> <span class="switcher-label-on">ON</span> <span class="switcher-label-off">OFF</span></label><!-- /.switcher-->
                                        </div>
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <!-- form column -->
                                    <div class="col-md-12">
                                        <!-- .form-group -->
                                        <div class="form-group">
                                            <label for="description" class="required-field"><?=_('Describe Your Store')?> <small>(No HTML)</small>  <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('This is displayed on various areas of our site, in particular, your main store page if you choose to list your products on Tracksz Listing Service.')?>"></i></label> <textarea title="<?=_('Enter a good description for your store.')?>" class="form-control" id="description" name="Description" data-parsley-required-message="<?=_('Please insert your store\'s description. No html please.')?>" data-parsley-group="fieldset02" required rows="4"><?php if (isset($store['Description'])) echo  $store['Description']; ?></textarea>
                                        </div><!-- /.form-group -->
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="currency"><?=_('Currrency')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('Tracksz lists prices in US Dollars. You may specify a currency here that you will use when adding inventory. Tracksz converts it to US Dollars when displaying. The default is US Dollars.  Mainly used with Tracksz Listing Service')?>"></i></label>
                                            <select name="Currency" class="form-control" id="currency" title="<?=_('Select your store\'s currency.')?>">
                                                <?php if(!isset($store['Currency'])):
                                                    $store['Currency'] = 'USD';
                                                endif; ?>
                                                <?php foreach(App\Library\Config::get('currency') as $code => $label): ?>
                                                    <option value="<?=$code?>"
                                                        <?php if(isset($store['Currency']) && $store['Currency'] == $code): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=$label?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="zerostock"><?=_('Zero Stock Items')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('Display and allow customers to purchase items with a quantity of zero. You are obligated to find items purchased with a zero stock level. The default is No.')?>"></i></label><br>
                                            <select name="ZeroStock" class="form-control" id="zerostock">
                                                <?php if(!isset($store['ZeroStock'])):
                                                    $store['ZeroStock'] = 'no';
                                                endif; ?>
                                                <?php foreach(['No', 'Yes'] as $key): ?>
                                                    <option value="<?=$key?>"
                                                        <?php if(isset($store['ZeroStock']) && $store['ZeroStock'] == $key): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=$key?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div><!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category" class="required-field"><?=_('Best Category')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('Please select the Best top level category that describes your primary inventory.')?>"></i></label><br>
                                            <select name="Category" class="form-control" id="category" title="<?=_('Please select the Best top level category that describes your primary inventory.')?>" data-parsley-required-message="<?=_('Please select the Best top level category that describes your primary inventory.')?>" required data-parsley-group="fieldset02">
                                                <?php if(!isset($store['Category'])): ?>
                                                    <option value="" selected><?=_('Select one...')?></option>
                                                <?php endif; ?>
                                                <?php foreach($categories as $cid => $label): ?>
                                                    <option value="<?=$label['Id']?>"
                                                        <?php if(isset($store['Category']) && $store['Category'] == $label['Id']): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=$label['Name']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="SuggestCategory"><?=_('Suggest a Category')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('Suggest a category you think we need to add.')?>"></i></label> <input type="text" title="<?=_('Suggest a category if you think we need to add one.')?>" class="form-control" id="SuggestCategory" name="SuggestCategory" maxlength="100" <?php if (isset($store['SuggestCategory'])) echo ' value="' . $store['SuggestCategory'] .'"' ?>>
                                        </div>
                                    </div>
                                </div> <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="GeoCode"><?=_('Provide Your Geocode')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('This is the Latitude/Longitude of your business address. This is used when searching nearby for stores nearby the visitor.  NOT IMPLEMENTED YET.')?>"></i></label>
                                            <input id="GeoCode" name="GeoCode" type="text" maxlength="80" placeholder="38.185468,-92.71928" class="form-control" <?php if (isset($store['GeoCode'])) echo ' value="' . $store['GeoCode'] .'"' ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?=_('Find your geocode here: ')?><a href="https://google-developers.appspot.com/maps/documentation/utils/geocoder/" target="_blank" title="Find Geocode at Google"><?=_('Google GeoCode')?></a>. <?=_('Enter your address (top left), click "GeoCode".  On the results page, it can be found in the lower left results box: <strong>Location: </strong> latitude, longitude')?>
                                        </div>
                                    </div>
                                </div> <!-- /form row -->
                            </fieldset>
                            <hr class="mb-3">
                            <fieldset>
                                <legend><?=_('SEO')?></legend>
                                <!-- form row -->
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="metatitle"><?=_('META Title')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('The META Title is a hidden field on your store and product pages that help search engines index your store. This is added to the end of your product META Title on product detail pages. The total META Title is 70 characters long.')?>"></i></label>
                                            <input id="metatitle" name="MetaTitle" type="text" maxlength="70" class="form-control" title="<?=_('Enter the META Title for your store\'s main page.  Primarily used on Tracksz Listing Service.')?>"
                                                <?php if (isset($store['MetaTitle'])) echo ' value="' . $store['MetaTitle'] .'"' ?>>
                                        </div>
                                    </div>
                                </div> <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="metadescription"><?=_('META Description')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('The META Description is a hidden field on your store and product pages that helps search engines index your store. This is added to your store page. The total META Description is 160 characters long.')?>"></i></label>
                                            <textarea id="metadescription" name="MetaDescription" rows="4" maxlength="160" title="<?=_('Enter the META Description for your store\'s main page.  Primarily used on Tracksz Listing Service.')?>" class="form-control"><?php if (isset($store['MetaDescription'])) echo $store['MetaDescription'] ?></textarea>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                            </fieldset>
                            <hr class="mb-3">
                            <fieldset>
                                <legend><?=_('Website and Social Media')?></legend>
                                <p><?=_('Provide the full address as you see in the example for Web Address and Facebook. Without the full address your link will not work. It is often easiest to open up your website or social media page in your browser and copy the address in the browser\'s address bar and past it into the correct entry.')?></p>
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="twitter" class="form-label"><?=_('Web Address')?> <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?=_('Enter your website address if you have your own website.')?>"></i></label>
                                            <input id="WebSite" name="WebSite" type="url" data-parsley-type="url" data-parsley-group="fieldset02" maxlength="200" placeholder="https://www.MyFullWebsiteAddress.com" class="form-control" title="<?=_('Enter the full address of your web page.')?>" <?php if (isset($store['WebSite'])) echo ' value="' . $store['WebSite'] .'"' ?>>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="facebook"><?=_('FaceBook Address')?> </label>
                                            <input id="facebook" name="FaceBook" type="url" data-parsley-type="url" data-parsley-group="fieldset02" maxlength="200" class="form-control" placeholder="https://www.facebook.com/Tracksz/" title="<?=_('Enter the full address of your Facebook page.')?>" <?php if (isset($store['FaceBook'])) echo ' value="' . $store['FaceBook'] .'"' ?>>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="Instagram"><?=_('Instagram Address')?> </label>
                                            <input id="Instagram" name="Instagram" type="url" data-parsley-type="url" data-parsley-group="fieldset02" maxlength="200" class="form-control" title="<?=_('Enter the full address of your Instagram page.')?>" <?php if (isset($store['Instagram'])) echo ' value="' . $store['Instagram'] .'"' ?>>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="twitter"><?=_('Twitter Address')?> </label>
                                            <input id="twitter" name="Twitter" type="url" data-parsley-type="url" data-parsley-group="fieldset02" maxlength="200" class="form-control" title="<?=_('Enter the full address of your Twitter page.')?>"<?php if (isset($store['Twitter'])) echo ' value="' . $store['Twitter'] .'"' ?>>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                                <!-- form row -->
                                <div class="form-row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pinterest" class="form-label"><?=_('Pinterest Address')?> </label>
                                            <input id="pinterest" name="Pinterest" type="url" data-parsley-type="url" data-parsley-group="fieldset02" maxlength="200" class="form-control" title="<?=_('Enter the full address of your Pinterest page.')?>" <?php if (isset($store['Pinterest'])) echo ' value="' . $store['Pinterest'] .'"' ?>>
                                        </div>
                                    </div>
                                </div>  <!-- /form row -->
                                <hr class="mt-5">
                                <div class="d-flex">
                                    <button type="button" class="prev btn btn-secondary"><?=_('Previous')?></button> <button type="button" class="next btn btn-primary ml-auto"  data-validate="fieldset02"><?=_('Next Step')?></button>
                                </div>
                                <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Form Data is NOT saved until Last Step.')?></div>
                            </fieldset>
                        </div>
                        <!-- .content -->
                        <div id="test-l-3" class="content dstepper-none fade">
                            <!-- fieldset -->
                            <fieldset>
                                <legend><?=_('Payment Information')?></legend> <!-- .custom-control -->
                                <!-- .row -->
                                <div class="form-row">
                                    <div class="col-md-12 list-group list-group-flush">
                                        <p><?=_('Select one of your cards listed below as the payment method for this store.  You manage cards in the Member Account account area. If you have not defined any Payment Cards in the member area, complete this store setup first then define a payment method.  You may attach the payment method to this store later.')?></p>
                                    </div>
                                </div><!-- /form row -->
                            <?php if (is_array($cards) &&  count($cards)> 0): ?>
                                <!-- .card -->
                                <div class="card card-fluid">
                                    <h6 class="card-header"><?=_('Your Payment Methods')?></h6><!-- .card-body -->
                                    <div class="card-body">
                                        <table id="stores" class="table table-striped table-bordered nowrap" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th><?=_('Select')?></th>
                                                    <th><?=_('Name On Card')?></th>
                                                    <th><?=_('Card')?></th>
                                                    <th><?=_('Expiration')?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($cards as $card): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-radio">
                                                            <?php if($card_rows[$card['id']]['ZipCheck'] != 'fail' &&
                                                                $card_rows[$card['id']]['StreetCheck'] != 'fail'): ?>
                                                                <input type="radio" class="custom-control-input" name="MemberCardId" id="payment-card-<?=$card_rows[$card['id']]['Id']?>" title="<?=_('Select this card as a payment method for this store.')?>" value="<?=$card_rows[$card['id']]['Id']?>"  <?php if (isset($store['MemberCardId'])) echo ' checked'?>> <label class="custom-control-label" for="payment-card-<?=$card_rows[$card['id']]['Id']?>">x<?=$card['last4']?></label>
                                                            </div>
                                                            <?php else: ?>
                                                                <i class="fa fa-exclamation-circle fa-fw" title="<?=_('There is a problem with the address on this card. It cannot be used until corrected.')?>" data-toggle="tooltip" data-placement="right"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?=$card['name']?></td>
                                                        <td><?=$card['brand']?> x<?=$card['last4']?>
                                                            <?php if($card_rows[$card['id']]['ZipCheck'] == 'fail' ||
                                                                $card_rows[$card['id']]['StreetCheck'] == 'fail'): ?>
                                                                <i class="fa fa-exclamation-circle fa-fw" title="<?=_('There is a problem with the address on this card. It cannot be used until corrected.')?>" data-toggle="tooltip" data-placement="right"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td> <?=sprintf("%02d", $card['exp_month'])?> / <?=substr($card['exp_year'], 2, 2)?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table><!-- /.table -->
                                        </div><!-- /.card-body -->
                                    </div><!-- /.card -->
                                    <!-- .card -->
                                <?php endif; ?>
                                
                                <div class="d-flex">
                                    <button type="button" class="prev btn btn-secondary">Previous</button> <button type="button" class="next btn btn-primary ml-auto" data-validate="fieldset01"><?=_('Next Step')?></button>
                                </div>
                                <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Form Data is NOT saved until Last Step.')?></div>
                            </fieldset>
                        </div><!-- /.content -->
                        <!-- .content -->
                        <div id="test-l-4" class="content dstepper-none fade">
                            <!-- fieldset -->
                            <fieldset>
                                <legend><?=_('Terms Agreement')?></legend> <!-- .card -->
    
                                <div class="d-flex">
                                    <button type="button" class="prev btn btn-secondary"><?=_('Previous')?></button> <button type="submit" class="submit btn btn-primary ml-auto"><?=_('Submit')?></button>
                                </div>
                                <div class="text-center" style="font-size: 90%; color: #B76BA3"><?=_('Click <strong>Submit</strong> to Save your entries.')?></div>
                            </fieldset>
                        </div><!-- /.content -->
                    </form>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div><!-- /.bs-stepper -->
                </div><!-- /.section-block -->
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>

<!-- Normal modal -->
<div class="modal fade" id="stripeModalCenter" tabindex="-1" role="dialog" aria-labelledby="stripeModalCenterLabel" aria-hidden="true">
    <!-- .modal-dialog -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- .modal-content -->
        <div class="modal-content">
            <!-- .modal-header -->fg
            <div class="modal-header">
                <h5 id="stripeModalCenterLabel" class="modal-title"> Stripe Connect & Tracksz </h5>
            </div><!-- /.modal-header -->
            <!-- .modal-body -->
            <div class="modal-body">
                <p> <strong>Stripe Connect</strong> <?=_('allows Tracksz to accept credit cards on your behalf. Transactions from your sales on Tracksz go through your Stripe Connect account, the transactions are immediately recorded at your Stripe Connect dashboard and, when scheduled, the funds are deposited directly into your bank account.')?></p>
                <p> <strong>Stripe Connect</strong> <?=_('provides added security for credit card processing. Credit cards entered by customers are validated by the Address, Zip/Post Code, and Card Validation Code (CVS). Stripe offers customers the convenience of storing credit cards on Stripe\'s secure network for use later. Tracksz does not store credit card information and cannot access credit card information entered on forms, substantially reducing risk.')?></p>
                <p> <?=_('After you complete the store form, you will be given an opportunity to connect your Stripe account to your Tracksz account.  This is <strong>required</strong> to list on Tracksz Listing Site. <u>You must connnect your Stripe account to your store using the form we provide.</u>')?></p>

            </div><!-- /.modal-body -->
            <!-- .modal-footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?=_('Close')?></button> <button type="button" class="btn btn-light" onclick=" window.open('https://www.stripe.com','_blank')" data-dismiss="modal"><?=_('More About Stripe')?></button>
            </div><!-- /.modal-footer -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<script src="/assets/vendor/bs-stepper/js/bs-stepper.min.js"></script> <!-- END PLUGINS JS -->
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<script src="/assets/javascript/pages/steps-store.js"></script>
<script>
    $(document).ready(function () {
        $('#telephone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            $(this).val(number);
        });

        $('#ContactTelephone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            $(this).val(number);
        });
        
        // select Zone (state) based on country
        $('select[name="CountryId"]').on('change', function() {
            $.ajax({
                url: '/ajax/zones/' + this.value,
                dataType: 'json',
                beforeSend: function() {
                    $('select[name="CountryId"]').prop('disabled', true);
                },
                complete: function() {
                    $('select[name="CountryId"]').prop('disabled', false);
                },
                success: function(json_obj) {
                    html = '';
                    for (var i in json_obj) {
                        html += '<option value="' + json_obj[i].Id + '"';

                        if (json_obj[i].Id == '<?=$store['ZoneId']?>') {
                            html += ' selected="selected"';
                        }
                        html += '>' + json_obj[i].Name + '</option>';
                    }
                    $('select[name="ZoneId"]').html(html);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });

        $('select[name="CountryId"]').trigger('change');
    });
</script>
<?php $this->stop() ?>

