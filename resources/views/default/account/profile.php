<?php
$title_meta = 'Member Profile for Tracksz, a Multiple Market Inventory Management Service';
$description_meta = 'Member Profile for Tracksz, a Multiple Market Inventory Management Service';
?>
<?=$this->layout('layouts/backend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
<!-- .wrapper -->
<div class="wrapper">
    <!-- .page -->
    <div class="page">

        <?php $this->insert('partials/account_header', ['page' => 'profile']) ?>
        <!-- .page-inner -->
        <div class="page-inner">
            <!-- .page-section -->
            <div class="page-section">
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
                <div class="media mb-3">
                    <!-- avatar -->
                    <div class="user-avatar user-avatar-xl fileinput-button">
                        <div class="fileinput-button-label"> <?=_('Change Avatar')?></div><img src="/assets/images/member/<?=$member['Avatar']?>" alt=""> <input id="fileupload-avatar" type="file" name="avatar">
                    </div><!-- /avatar -->
                    <!-- .media-body -->
                    <div class="media-body pl-3">
                        <h3 class="card-title"> <?=_('Member Avatar')?></h3>
                        <h6 class="card-subtitle text-muted"><?=_('Click the current avatar to change.')?></h6>
                        <p class="card-text">
                            <small>JPG, GIF or PNG 400x400, &lt; 2 MB.</small>
                        </p><!-- The avatar upload progress bar -->
                        <div id="progress-avatar" class="progress progress-xs fade">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        </div><!-- /avatar upload progress bar -->
                    </div><!-- /.media-body -->
                </div><!-- /.media -->
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=_('Member Profile')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <!-- form -->
                        <form method="post" action="/account/profile" id="profile">
                            <input type="hidden" name="Id" value="<?=$member['Id']?>">
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-12 mb-3">
                                    <label for="fullname" class="required-field"><?=_('Full Name')?></label> <input type="text" title="<?=_('Enter Tracksz Member Full Name')?>" class="form-control" id="fullname" name="FullName" placeholder="Toni Doe"
                                    <?php if (isset($member['FullName'])) echo ' value="' . $member['FullName'] .'"' ?>>
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-12 mb-3">
                                    <label for="phone" class="required-field"><?=_('Telephone')?> <small class="text-muted"><?=_('(Only numbers)')?></small></label> <input type="text" title="<?=_('Your Telehone Number. Only Enter Numbers.')?>" class="form-control" id="phone" name="Telephone" placeholder="1234567890" data-toggle="tooltip" data-placement="left"
                                    <?php if (isset($member['Telephone'])) echo ' value="' . $member['Telephone'] .'"' ?>>
                                    <small>Used for Texts if you choose to receive Texts.</small>
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <h6 class="card-header"><?=_('Notification Settings')?></h6>
                            <div class="col-md-8 list-group list-group-flush">
                                <!-- .list-group-item -->
                                <div class="list-group-item d-flex justify-content-between align-items-center"><?=_('Receive emails from Tracksz (updates, promotions, password reset, etc)?')?>
                                    <!-- .switcher -->
                                    <label class="switcher-control switcher-control-success"  data-toggle="tooltip" data-placement="left" title="<?=_('Slide bar on or off to receive Tracksz informational emails.')?>"><input type="checkbox" name="Newsletter" class="switcher-input" <?php if (isset($member['Newsletter']) && $member['Newsletter']=='on') echo ' checked'?>> <span class="switcher-indicator"></span></label> <!-- /.switcher -->
                                </div>
                                <!-- .list-group-item -->
                                <div class="list-group-item d-flex justify-content-between align-items-center"><?=_('Receive texts to your phone from Tracksz (updates, promotions, password reset, etc)?')?>
                                    <!-- .switcher -->
                                    <label class="switcher-control switcher-control-success"  data-toggle="tooltip" data-placement="left" title="<?=_('Slide bar on or off to receive Tracksz informational Texts to your phone.')?>"><input type="checkbox" name="Texts" class="switcher-input" <?php if (isset($member['Texts']) && $member['Texts']=='on') echo ' checked'?>> <span class="switcher-indicator"></span></label> <!-- /.switcher -->
                                </div><!-- /.list-group-item -->
                            </div>
                            <hr>
                            <!-- .form-actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary ml-auto">Update Profile</button>
                            </div><!-- /.form-actions -->
                        </form><!-- /form -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=_('Change Email Address')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <!-- form -->
                        <form method="post" action="/account/email" id="change_email">
                            <input type="hidden" name="Id" value="<?=$member['Id']?>">
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="required-field"><?=_('New Email Address')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter Your New Email Address.  It must be valid and unique to our system.')?>"></i></label> <input type="text" class="form-control"  title="<?=_('Enter Your New Email Address.  It must be valid and unique to our system.')?>" id="email" name="email" placeholder="tonidoe@example.com"
                                    <?php if (isset($current_email)) echo ' value="' . $current_email .'"' ?>>
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-12 mb-3">
                                    <label for="password" class="required-field"><?=_('Current Password')?></label> <input type="password" title="<?=_('Enter Your Current Password')?>" class="form-control" id="password" name="password_current" autocomplete="off">
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <hr>
                            <!-- .form-actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary ml-auto">Change Email Address</button>
                            </div><!-- /.form-actions -->
                        </form><!-- /form -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <!-- .card -->
                <div class="card card-fluid">
                    <h6 class="card-header"><?=_('Change Password')?></h6><!-- .card-body -->
                    <div class="card-body">
                        <!-- form -->
                        <form method="post" action="/account/password" id="change_password">
                            <input type="hidden" name="Id" value="<?=$member['Id']?>">
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-6 mb-3">
                                    <label for="current_password" class="required-field"><?=_('Current Password')?></label> <input type="password" title="<?=_('Enter Your Current Password')?>" class="form-control" id="current_password" name="current_password">
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <!-- form row -->
                            <div class="form-row">
                                <!-- form column -->
                                <div class="col-md-6 mb-3">
                                    <label for="new_password" class="required-field"><?=_('New Password')?></label> <input type="password" data-toggle="tooltip" data-placement="left" title="<?=_('Enter a new password.  It must be at least 8 characters long, contain at least one number and have both uppercase and lowercase letters. We recommend at least one special character =!-@._*#')?>" class="form-control" id="new_password" name="new_password">
                                </div><!-- /form column -->
                                <!-- form column -->
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="required-field"><?=_('Confirm Password')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the New Password again.')?>"></i></label> <input type="password" title="<?=_('Enter the New Password again.')?>" class="form-control" id="confirm_password" name="confirm_password" >
                                </div><!-- /form column -->
                            </div><!-- /form row -->
                            <hr>
                            <!-- .form-actions -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary ml-auto">Change Password</button>
                            </div><!-- /.form-actions -->
                        </form><!-- /form -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
    <script src="/assets/vendor/pace/pace.min.js"></script>
    <script src="/assets/vendor/stacked-menu/stacked-menu.min.js"></script>
    <script src="/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="/assets/vendor/blueimp-load-image/load-image.all.min.js"></script>
    <script src="/assets/vendor/blueimp-canvas-to-blob/canvas-to-blob.min.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/jquery.fileupload.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/jquery.fileupload-process.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/jquery.fileupload-image.js"></script>
    <script src="/assets/vendor/blueimp-file-upload/js/jquery.fileupload-validate.js"></script>
<?=$this->stop()?>

<?php $this->start('page_js') ?>
    <script src="/assets/javascript/pages/user-settings.js"></script>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
    <script src="/assets/vendor/jquery-validate/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('form').each(function() {   // <- selects every <form> on page
            $(this).validate({
                rules: {
                    FullName: {
                        required: true
                    },
                    Telephone: {
                        minlength: 10,
                        maxlength: 31,
                        number: true,
                        required: true
                    },
                    password_current: {
                        minlength: 8,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    current_password: {
                        minlength: 8,
                        pwcheck: true,
                        required: true
                    },
                    new_password: {
                        minlength: 8,
                        pwcheck: true,
                        required: true
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#new_password"
                    },
                },
                messages: {
                    Telephone: {
                        minlength: "Please enter a minimum of 10 numbers.",
                        maxlength: "Do not enter more than 31 numbers."
                    },
                    email: {
                        required: "Email is required.",
                        email: "Please enter a valid email."
                    },
                    password_current: {
                        required: "Please enter your old password.",
                        pwcheck: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        minlength:  "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters."
                    },
                    current_password: {
                        required: "Please enter your old password.",
                        pwcheck: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        minlength:  "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters."
                    },
                    new_password: {
                        required: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        pwcheck: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        minlength:  "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters."
                    },
                    confirm_password: {
                        required: "Enter the same password as you entered for New Password.",
                        equalTo: "Enter the same password as you entered for New Password."
                    }
                },

                highlight: function (element) {
                    $(element).closest('.form-control').addClass('is-invalid');
                },
                unhighlight: function(element) {
                    jQuery(element).closest('.form-control').removeClass('is-invalid');
                }
            });

            $.validator.addMethod("pwcheck", function(value) {
                return /^[A-Za-z0-9\d=!\-@._*#]*$/.test(value) // consists of only these
                    && /[a-z]/.test(value) // has a lowercase letter
                    && /[A-Z]/.test(value) // has a uppercase letter
                    && /\d/.test(value) // has a digit
            });
        });

        $('#phone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            $(this).val(number);
        });
    });
</script>
<?php $this->stop() ?>


