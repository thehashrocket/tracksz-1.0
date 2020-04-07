<?php
$title_meta = 'Member Payment Methods for Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Member Payment Methods for Tracksz, a Multiple Market Inventory Management Service';
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
                                <a href="/account/profile" title="Tracksz Member's Profile"><?=('Profile')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Payment Methods')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <!-- title -->
                <h1 class="page-title"> <?=_('Payment Information')?> </h1>
                <p class="text-muted"> <?=_('This is where you\'ll manage all your payment details. You can add or delete payment methods as needed.  Later when editing your stores you may choose from one of these listed below for the monthly fee payments.')?>. </p><!-- /title -->
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header>
            
        <?php if (is_array($cards) &&  count($cards)> 0): ?>
            <!-- .card -->
            <div class="card card-fluid">
                <h6 class="card-header"><?=_('Payment Cards')?></h6><!-- .card-body -->
                <div class="card-body">
                    <!-- .table -->
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><?=_('Name On Card')?></th>
                            <th><?=_('Card')?></th>
                            <th><?=_('Expiration')?></th>
                            <th class="align-middle text-right"><?=_('Action')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($cards as $card): ?>
                        <tr>
                            <td><?=$card['name']?></td>
                            <td><?=$card['brand']?> x<?=$card['last4']?>
                                <?php if($card_rows[$card['id']]['ZipCheck'] == 'fail' ||
                                         $card_rows[$card['id']]['StreetCheck'] == 'fail'): ?>
                                        <i class="fa fa-exclamation-circle fa-fw" title="<?=_('There is a problem with the address on this card. It cannot be used until corrected.')?>" data-toggle="tooltip" data-placement="right"></i>
                                <?php endif; ?>
                            </td>
                            <td> <?=sprintf("%02d", $card['exp_month'])?> / <?=substr($card['exp_year'], 2, 2)?></td>
                            <td class="align-middle text-right">
                                <a href="#" class="btn btn-sm btn-icon btn-secondary" data-toggle="collapse" data-target="#details-<?=$card_rows[$card['id']]['Id']?>" title="<?=_('Edit this Payment Method')?>"><i class="collapse-indicator fa fa-pencil-alt" data-toggle="tooltip" data-placement="left" title="<?=_('Edit this Payment Method')?>"></i> <span class="sr-only"><?=_('Edit')?></span></a>
                                <?php if($card_rows[$card['id']]['IsBilling'] == 0): ?>
                                <a href="#" data-toggle="modal" data-target="#deletePaymentMethodModal" data-url="/account/payment/delete/<?=$card_rows[$card['id']]['Id']?>" class="btn btn-sm btn-icon btn-secondary" id="delete-payment-<?=$card_rows[$card['id']]['Id']?>" title="<?=_('Delete this payment method.')?>" onclick="deletePayment(this)"><i class="far fa-trash-alt"  data-toggle="tooltip" data-placement="top" title="<?=_('Delete this payment method.')?>"></i> <span class="sr-only"><?=_('Delete')?></span></a>
                                <?php else: ?>
                                    &nbsp;&nbsp;<i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Cannot delete. Card is used as store payment method.  Change payment method for store before deleting')?>"> <span class="sr-only"><?=('Can\'t Delete')?></span>&nbsp;</i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="row-details bg-light collapse" id="details-<?=$card_rows[$card['id']]['Id']?>">
                            <td colspan="4">
                                <form method="post" action="/account/payment/edit/<?=$card_rows[$card['id']]['Id']?>"
                                      id="account-editcard-<?=$card_rows[$card['id']]['Id']?>">
                                    <input type="hidden" id="AddressId<?=$card_rows[$card['id']]['Id']?>" name="AddressId" value="<?=$card_rows[$card['id']]['AddressId']?>">
                                <div class="block-header mb-3"><strong class="text-uppercase"><?=_('Edit Card details')?> - x<?=$card['last4']?></strong></div>
                                
                                <div class="form-row">
                                    <!-- form column -->
                                    <div class="col-md-12 mb-3">
                                        <label for="fullname<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('Full Name')?></label> <input type="text" title="<?=_('Enter Full Name on Card')?>" class="form-control" id="fullname<?=$card_rows[$card['id']]['Id']?>" name="FullName" placeholder="Toni Doe"
                                            <?php if (isset($card_rows[$card['id']]['FullName'])) echo ' value="' . $card_rows[$card['id']]['FullName']  .'"' ?>>
                                    </div><!-- /form column -->
                                </div><!-- /form row -->
                                <div class="form-row">
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="address<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('Address')?></label> <input type="text" title="<?=_('Enter Card\'s Billing Address First Line')?>" class="form-control" id="address<?=$card_rows[$card['id']]['Id']?>" name="Address1" placeholder="123 Any Lane"
                                            <?php if (isset($card_rows[$card['id']]['Address1'] )) echo ' value="' . $card_rows[$card['id']]['Address1']  .'"' ?>>
                                    </div><!-- /form column -->
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="address2-<?=$card_rows[$card['id']]['Id']?>"><?=_('Address 2')?></label> <input type="text" title="<?=_('Enter Card\'s Secton Line of Billing Address, optional.')?>" class="form-control" id="address2-<?=$card_rows[$card['id']]['Id']?>" name="Address2" placeholder="Suite, Apt, Attn"
                                            <?php if (isset($card_rows[$card['id']]['Address2'])) echo ' value="' . $card_rows[$card['id']]['Address2'] .'"' ?>>
                                    </div><!-- /form column -->
                                </div><!-- /form row -->
                                <div class="form-row">
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="city<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('City')?></label> <input type="text" title="<?=_('Enter Card\'s Billing City')?>" class="form-control" id="city<?=$card_rows[$card['id']]['Id']?>" name="City" placeholder="City Name"
                                            <?php if (isset($card_rows[$card['id']]['City'])) echo ' value="' . $card_rows[$card['id']]['City'] .'"' ?>>
                                    </div><!-- /form column -->
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="zipcode<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('Zip/Postal Code')?></label> <input type="text" title="<?=_('Enter Card\'s Billing Zip/Post Code')?>" class="form-control" id="zipcode<?=$card_rows[$card['id']]['Id']?>" name="PostCode" placeholder="12345"
                                            <?php if (isset($card_rows[$card['id']]['PostCode'])) echo ' value="' . $card_rows[$card['id']]['PostCode'] .'"' ?>>
                                    </div><!-- /form column -->
                                </div><!-- /form row -->
                                <div class="form-row">
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="CountryId<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('Country')?></label>
                                        <select name="CountryId" id="CountryId<?=$card_rows[$card['id']]['Id']?>" class="form-control"
                                            title="<?=_('Select Card\'s Billing Country')?>"
                                            onchange="setStates(this,'ZoneId<?=$card_rows[$card['id']]['Id']?>',<?=$card_rows[$card['id']]['ZoneId']?>)">
                                            <?php foreach ($countries as $country): ?>
                                                <?php if($country['Id'] == $card_rows[$card['id']]['CountryId']): ?>
                                                    <option value="<?=$country['Id']?>" selected="selected"><?=$country['Name']?></option>
                                                <?php else: ?>
                                                    <option value="<?=$country['Id']?>"><?=$country['Name']?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /form column -->
                                    <!-- form column -->
                                    <div class="col-md-6 mb-3">
                                        <label for="ZoneId<?=$card_rows[$card['id']]['Id']?>" class="required-field"><?=_('State/Region')?></label>
                                        <select name="ZoneId" id="ZoneId<?=$card_rows[$card['id']]['Id']?>" class="form-control" title="<?=_('Select Card\'s Billing State or Region')?>">
                                        </select>
                                    </div><!-- /form column -->
                                </div><!-- /form row -->
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cc_exp_month-<?=$card_rows[$card['id']]['Id']?>" class="form-label required-field"><?=_('Expiration Date (MM/YY)');?></label><br>
                                            <select name="exp_month" class="custom-select w-auto form-control" id="cc_exp_month-<?=$card_rows[$card['id']]['Id']?>">
                                                <?php for($i=1; $i<=12; $i++): ?>
                                                    <option value="<?=$i?>"
                                                        <?php if($i == $card['exp_month']): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    ><?=sprintf("%02d", $i)?></option>
                                                <?php endfor ?>
                                            </select>
                                            /
                                            <select name="exp_year" class="custom-select w-auto form-control"  id="cc_exp_year-<?=$card_rows[$card['id']]['Id']?>">
                                                <?php
                                                $start_year = $card['exp_year'];
                                                $current_year = date('Y');
                                                if ($start_year > $current_year) {
                                                    $start_year = $current_year;
                                                }
                                                for($i=$start_year; $i<=$current_year + 10; $i++): ?>
                                                    <option value="<?=$i?>"
                                                        <?php if($i == $card['exp_year']): ?>                                                                                                     selected
                                                        <?php endif; ?>
                                                    ><?=substr($i, 2, 2)?></option>
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                        <div id="cc_error-<?=$card_rows[$card['id']]['Id']?>" class="invalid-feedback" style="display: none"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- .form-actions -->
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary ml-auto" title="<?=_('Click to Edit this card.')?>"><?=_('Edit Card')?></button>
                                        </div><!-- /.form-actions -->
                                    </div>
                                </div>
                                </form>
                            </td>
                        </tr><!-- /tr -->
                        <?php endforeach; ?>
                        </tbody>
                    </table><!-- /.table -->
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
            <!-- .card -->
        <?php endif; ?>
            <div class="mb-2">
                <button type="button" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#details-payment" title="<?=_('Click to Add New payment Method')?>"><?=_('Add Payment Method')?></button>
                <div class="collapse" id="details-payment">
                    <br><p class="text-muted"> <?=_('Use the form below to add the billing address and card information for a new payment method.')?></p>
                    <p class="text-muted"><?=_('For added security, we store credit card information with our credit card processor.  They also provide the credit card form below so no credit card data is ever on our network.')?></p>
                    <!-- .card -->
                    <div id="base-style" class="card">
                        <!-- .card-body -->
                        <div class="card-body">
                    <!-- form -->
                    <form method="post" action="/account/payment" id="account-newcard">
                    <input type="hidden" name="Id" value="<?=$member['Id']?>">
                    <!-- form row -->
                    <div class="form-row">
                        <!-- form col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label required-field"><?=_('Card Number')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the new card number.')?>"></i></label>
                                <span id="card-number" class="form-control" title="<?=_('Enter the new credit card number.')?>">
                                            <!-- Stripe Card Element -->
                                </span>
                            </div>
                        </div><!-- /form col -->
                    </div><!-- /form row -->
                    <!-- form row -->
                    <div class="form-row">
                        <!-- form col -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label required-field"><?=_('Expiration Date')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the expiration date, MM/YY.')?>"></i></label>
                                <span id="card-exp" class="form-control" title="<?=_('Enter expiration date, MM/YY.')?>">
                                            <!-- Stripe Card Expiry Element -->
                                </span>
                            </div>
                        </div><!-- /form col -->
                        <!-- form col -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label required-field"><?=_('Cvv')?> <i class="menu-item far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the 3 or 4 digit code on the back of your card.')?>"></i></label>
                                <span id="card-cvc" class="form-control" title="<?=_('Enter the 3 or 4 digit code on the back of your card.')?>">
                                            <!-- Stripe CVC Element -->
                                </span>
                            </div>
                        </div><!-- /form col -->
                    </div> <!-- /form row -->
                    <div id="card-errors"> </div>
                <?php if (is_array($addresses) && count($addresses)> 0): ?>
                    <?php $has_address = true; ?>
                    <p><strong class="text-uppercase text-muted"><?=_('Credit Card Billing Address')?></strong></p>
                    <p class="text-muted"> <?=_('Choose the billing address from the addresses listed or Add a New Address if the correct address is not listed.');?></p>
                    <!-- .page-section -->
                    <div class="page-section">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th> <?=_('Select')?> </th>
                                    <th><?=_(' Name')?> </th>
                                    <th><?=_(' Address')?> </th>
                                    <th> <?=_('City')?> </th>
                                    <th> <?=_('Zip')?> </th>
                                    <th class="align-middle text-right"> <?=_('Delete')?> </th>
                                </tr>
                                </thead>
                                <tbody>
                            <?php foreach($addresses as $address): ?>
                                <tr>
                                    <td class="align-middle col-checker">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="NewAddressId" id="p3_<?=$address['Id']?>" value="<?=$address['Id']?>"> <label class="custom-control-label" for="p3_<?=$address['Id']?>"> </label>
                                        </div>
                                    </td>
                                    <td> <?=$address['FullName']?> </td>
                                    <td> <?=$address['Address1']?> </td>
                                    <td> <?=$address['City']?> </td>
                                    <td> <?=$address['PostCode']?> </td>
                                    <td class="align-middle text-right">
                                        <?php if (!key_exists($address['Id'],$address_used)): ?>
                                        <a href="#" data-toggle="modal" data-target="#deleteAddressModal" data-url="/account/address/delete/<?=$address['Id']?>" class="btn btn-sm btn-icon btn-secondary" id="delete-address-<?=$address['Id']?>" onclick="deleteAddress(this)"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="left" title="<?=_('You may delete this address. Not used with any payment methods.')?>"></i> <span class="sr-only"><?=_('Delete')?></span></a>
                                        <?php else: ?>
                                            <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Cannot delete. Address used by payment method.')?>"> <span class="sr-only"><?=('Can\'t Delete')?></span>&nbsp;</i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                                <tr>
                                    <td class="align-left" colspan="6">
                                        <button type="button" id="new-address-button" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#new_address" title="<?=_('Click to Add New Address')?>"><?=_('Add Payment Address')?></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table><!-- /.table -->
                        </div>
                    </div><!-- /.page-section -->
                <?php else: ?>
                    <?php $has_address = false; ?>
                    <p><strong class="text-uppercase text-muted"><?=_('Credit Card Billing Address')?></strong></p>
                <?php endif; ?>
                    <div id="new_address" class="collapse">
                        <div class="form-row">
                            <!-- form column -->
                            <div class="col-md-12 mb-3">
                                <label for="fullname" class="required-field"><?=_('Full Name')?></label> <input type="text"  data-toggle="tooltip" data-placement="left" title="<?=_('Enter Full Name on Card.')?>" class="form-control" id="fullname" name="FullName" placeholder="Toni Doe"
                                    <?php if (isset($member['FullName'])) echo ' value="' . $member['FullName'] .'"' ?>>
                            </div><!-- /form column -->
                        </div><!-- /form row -->
                        <div class="form-row">
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="address" class="required-field"><?=_('Address')?></label> <input type="text"  data-toggle="tooltip" data-placement="left" title="<?=_('Enter Card\'s First Line of Billing Address')?>" class="form-control" id="address" name="Address1" placeholder="123 Any Lane"
                                    <?php if (isset($member['Address1'])) echo ' value="' . $member['Address1'] .'"' ?>>
                            </div><!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="address2"><?=_('Address 2')?></label> <input type="text" title="<?=_('Enter Card\'s Second Line of Billing Address, optional.')?>" class="form-control" id="address2" name="Address2" placeholder="Suite, Apt, Attn"
                                    <?php if (isset($member['Address2'])) echo ' value="' . $member['Address2'] .'"' ?>>
                            </div><!-- /form column -->
                        </div><!-- /form row -->
                        <div class="form-row">
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="city" class="required-field"><?=_('City')?></label> <input type="text" title="<?=_('Enter Card\'s Billing City')?>" class="form-control" id="city" name="City" placeholder="City Name"
                                    <?php if (isset($member['City'])) echo ' value="' . $member['City'] .'"' ?>>
                            </div><!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="zipcode" class="required-field"><?=_('Zip/Postal Code')?></label> <input type="text" title="<?=_('Enter Card\'s Billing Zip/Post Code')?>" class="form-control" id="zipcode" name="PostCode" placeholder="12345"
                                    <?php if (isset($member['PostCode'])) echo ' value="' . $member['PostCode'] .'"' ?>>
                            </div><!-- /form column -->
                        </div><!-- /form row -->
                        <div class="form-row">
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="CountryId" class="required-field"><?=_('Country')?></label>
                                <select name="CountryId" id="CountryId" class="form-control" title="<?=_('Select Card\'s Billing Country')?>" onchange="setStates(this,'ZoneId',<?=$region['ZoneId']?>)">
                                    <option value=""><?=_('-- Please Select---')?></option>
                                    <?php foreach ($countries as $country): ?>
                                        <?php if($country['Id'] == $region['CountryId']): ?>
                                            <option value="<?=$country['Id']?>" selected="selected"><?=$country['Name']?></option>
                                        <?php else: ?>
                                            <option value="<?=$country['Id']?>"><?=$country['Name']?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div><!-- /form column -->
                            <!-- form column -->
                            <div class="col-md-6 mb-3">
                                <label for="ZoneId" class="required-field"><?=_('State/Region')?></label>
                                <select name="ZoneId" id="ZoneId" class="form-control" title="<?=_('Select Card\'s Billing State or Region')?>">
                                </select>
                            </div><!-- /form column -->
                        </div><!-- /form row -->
                    </div>
                    <hr>
                    <!-- .form-actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary ml-auto"><?=_('Add Card')?></button>
                    </div><!-- /.form-actions -->
                    </form>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                    <div class="mb-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="collapse" data-target="#details-payment" title="<?=_('Click to Cancel New Payment Method')?>"><?=_('Cancel Method')?></button>
                    </div>
                </div><!-- /.custom-control-hint -->
            </div><!-- /.custom-control -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div>

<!-- .modal -->
<form id="deletePaymentMethodForm" name="deletePaymentMethodForm" method="post">
    <div class="modal fade" id="deletePaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentMethodModalLabel" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog" role="document">
            <!-- .modal-content -->
            <div class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h6 id="deletePaymentMethodModalLabel" class="modal-title inline-editable">
                        <?=('Delete Payment Method')?>
                    </h6>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <p class="mb-4 text-muted"><?=_('Are you sure you want to delete this payment card?')?></p>
                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=_('Delete')?></button> <button type="button" class="btn btn-light" data-dismiss="modal"><?=_('Cancel')?></button>
                </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form><!-- /.modal -->
<!-- .modal -->
<form id="deleteAddressForm" name="deleteAddressForm" method="post">
    <div class="modal fade" id="deleteAddressModal" tabindex="-1" role="dialog" aria-labelledby="deleteAddressModalLabel" aria-hidden="true">
        <!-- .modal-dialog -->
        <div class="modal-dialog" role="document">
            <!-- .modal-content -->
            <div class="modal-content">
                <!-- .modal-header -->
                <div class="modal-header">
                    <h6 id="deleteAddressModalLabel" class="modal-title inline-editable">
                        <?=('Delete This Address')?>
                    </h6>
                </div><!-- /.modal-header -->
                <!-- .modal-body -->
                <div class="modal-body">
                    <p class="mb-4 text-muted"><?=_('Are you sure you want to delete this address?')?></p>
                </div><!-- /.modal-body -->
                <!-- .modal-footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?=_('Delete')?></button> <button type="button" class="btn btn-light" data-dismiss="modal"><?=_('Cancel')?></button>
                </div><!-- /.modal-footer -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</form><!-- /.modal -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/jquery-validate/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {

        // Create a Stripe client
        var stripe = Stripe('<?=getenv('STRIPE_PUB_KEY')?>');

        // Create an instance of Elements
        var elements = stripe.elements();

        // Try to match bootstrap 4 styling
        var style = {
            base: {
                '::placeholder': {
                    color: '#aab7c4'
                }
            }
        };

        // Card number
        var card = elements.create('cardNumber', {
            'placeholder': '1234 5678 9012 3456',
            'style': style
        });
        card.mount('#card-number');

        // CVC
        var cvc = elements.create('cardCvc', {
            'placeholder': '123',
            'style': style
        });
        cvc.mount('#card-cvc');

        // Card expiry
        var exp = elements.create('cardExpiry', {
            'placeholder': 'MM / YY',
            'style': style
        });
        exp.mount('#card-exp');

        $('form').each(function() {   // <- selects every <form> on page
            $(this).validate({
                rules: {
                    FullName: {
                        required: true,
                    },
                    Address1: {
                        required: true,
                    },
                    PostCode:{
                        required: true,
                    },
                    City:{
                        required: true,
                    },
                    exp_month: {
                        ExpireCheck: true
                    },
                    exp_year: {
                        ExpireCheck: true
                    }
                },
                messages: {
                    NameOnCard: {required: "Full name as it appears on the credit card."},
                    Address1: {required: "The first billing address line with your credit card company."},
                    PostCode: {required: "The billing zip/postal code with your credit card company."},
                    City: {required: "The billing City associated with this credit card."}
                },
                highlight: function (element) {
                    $(element).closest('.form-control').addClass('is-invalid');
                },
                unhighlight: function (element) {
                    jQuery(element).closest('.form-control').removeClass('is-invalid');
                }
            });
            $.validator.addMethod('ExpireCheck', function(value, element, params) {
                var tmp = element.id.split('-');
                var id = tmp[1];
                var minMonth = new Date().getMonth() + 1;
                var minYear = new Date().getFullYear();
                var month = parseInt($('#cc_exp_month-'+id).val(), 10);
                var year = parseInt($('#cc_exp_year-'+id).val(), 10);
                if (year < minYear) {
                    $('#cc_error-'+id).html('Expiration date not valid.');
                    $('#cc_error-'+id).show();
                    $('#cc_exp_year-'+id).addClass('is-invalid');
                    return false;
                } else if (year > minYear || (year === minYear && month >= minMonth)) {
                    $('#cc_error-'+id).hide();
                    $('#cc_exp_month-'+id).removeClass('is-invalid');
                    $('#cc_exp_year-'+id).removeClass('is-invalid');
                    return true;
                }
            },  function(value,element){
                var tmp = element.id.split('-');
                var id = tmp[1];
                $('#cc_error-'+id).html('Expiration date not valid.');
                $('#cc_error-'+id).show();
                $('#cc_exp_month-'+id).addClass('is-invalid');
                return false;
            });
        });
        
        // Handle form submission.
        var form = document.getElementById('account-newcard');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    $('html, body').animate({
                        scrollTop: $(errorElement).offset().top-400
                    }, 1000);
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('account-newcard');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        };
        
        $('select[name="CountryId"]').trigger('change');
        
        $('#new-address-button').click(function() {
            $('input[name=NewAddressId]').prop('checked',false);
        });
        
        $('input[name=NewAddressId]').click(function () {
            $('#new_address').removeClass('show');
        });
        
        <?php if(!$has_address): ?>
            $('#new_address').addClass('show');
        <?php endif; ?>
    });

    function deletePayment(el) {
        var url = $('#'+el.id).data("url");
        $('#deletePaymentMethodForm').attr('action', url);
    };

    function deleteAddress(el) {
        var url = $('#'+el.id).data("url");
        $('#deleteAddressForm').attr('action', url);
    };
    
    // select Zone (state) based on country
    function setStates(el, divId, zoneId) {
        $.ajax({
            url: '/ajax/zones/' + el.value,
            dataType: 'json',

            success: function (json_obj) {
                html = '';
                for (var i in json_obj) {
                    html += '<option value="' + json_obj[i].Id + '"';

                    if (json_obj[i].Id == zoneId) {
                        html += ' selected="selected"';
                    }
                    html += '>' + json_obj[i].Name + '</option>';
                }
                $('#'+divId).html(html);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    };
</script>
<?php $this->stop() ?>
