<?php
$title_meta = _('Register for Tracksz, a Multiple Market Inventory Management Service');
$description_meta = _('Register for Tracksz, a Multiple Market Inventory Management Service');
?>
<?=$this->layout('layouts/noheaderfooter', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
<main class="auth auth-floated">
    <div class="auth-form">
        <!-- form -->
        <form action="<?=\App\Library\Config::get('company_url')?>/register" id="register" method="POST">
            <div class="mb-4">
                <div class="mb-3">
                    <img class="rounded" src="assets/apple-touch-icon.png" alt="" height="72">
                </div>
                <h1 class="h3"> <?=_('Register')?> </h1>
            </div>
            <p class="text-left mb-4"> <?=_('Already have an account?')?> <a href="<?=\App\Library\Config::get('company_url');?>/login" title="<?=_('Login to your Tracksz account')?>"><?=_('Login')?></a></p>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <!-- .form-group -->
            <div class="form-group mb-4">
                <label class="required-field d-block text-left" for="userEmail">Email <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter a valid email address. Must be unique to Trackzs.')?>"></i></label> <input name="userEmail" type="email" class="form-control form-control-lg" id="userEmail" title="<?=_('Enter a valid email for your Tracksz account')?>">
            </div><!-- /.form-group -->
            <!-- .form-group -->
            <div class="form-group mb-4">
                <label class="required-field d-block text-left" for="userName">Username <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter a unqique username.  Must be a minimu of 6 characters in length')?>"></i></label> <input name="userName" type="text" class="form-control form-control-lg" id="userName" title="<?=_('Enter a username for your Tracksz account')?>">
            </div><!-- /.form-group -->
            <!-- .form-group -->
            <div class="form-group mb-4">
                <label class="required-field d-block text-left" for="userPassword"><?=_('Password')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Your password must be at least 8 characters long, contain at least one number and have both uppercase and lowercase letters. We recommend at least one special character =!-@._*#')?>"></i></label>
                <input name="userPassword" type="password" id="userPassword" class="form-control form-control-lg form-strength-meter mb-1" title="<?=_('Enter a password. It must be at least 8 characters long, contain at least one number and have both uppercase and lowercase letters. We recommend at least one special character =!-@._*#')?>"  data-indicator="#psm-indicator" data-indicator-feedback="#psm-feedback">
                <div class="progress progress-sm mb-1">
                    <div id="psm-indicator" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div id="psm-feedback" class="text-muted font-italic"></div>
            </div><!-- /.form-group -->
            <!-- .form-group -->
            <div class="form-group text-center">
                <div class="custom-control custom-control-inline custom-checkbox">
                    <input name="user_agrees" type="checkbox" class="custom-control-input form-control" id="user_agrees" required> <label class="custom-control-label" for="user_agrees" title="<?=_('Agree to Tracksz Terms of Service and Privacy Policy.')?>"><?=_('I agree to Terms of Service and Privacy Policy')?><i class="required-field"></i></label>
                </div>
            </div><!-- /.form-group -->
            <!-- .form-group -->
            <div class="form-group">
                <button class="btn btn-lg btn-primary btn-block" type="submit" title="<?=_('Submit Registration for your Tracksz account.')?>"><?=_('Register')?></button>
            </div><!-- /.form-group -->
        </form><!-- /.auth-form -->
        <?php $this->insert('partials/auth_copyright') ?>
    </div>
    <!-- .auth-announcement -->
    <div id="announcement" class="auth-announcement" style="background-image: url(assets/images/illustration/trackszregister.jpg);">
        <div class="announcement-body">
            <h5> <?=('Simplify Your Inventory and Order Management')?> </h5>
            <h2 class="announcement-title"><?=_('Register with Tracksz')?> </h2>
            <h6> <?=('Already Have an Account?')?> </h6>
            <a href="<?=\App\Library\Config::get('company_url')?>/login" class="btn btn-warning mt-2"  title="<?=_('Login to your Tracksz Account.')?>"><i class="fa fa-fw fa-user-check"></i> <?=('Login to Tracksz')?></a>
        </div>
    </div><!-- /.auth-announcement -->
</main><!-- /.auth -->
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="assets/vendor/zxcvbn/zxcvbn.js"></script>
<script src="assets/vendor/jquery-validate/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('form').each(function() {   // <- selects every <form> on page
            $(this).validate({
                rules: {
                    userEmail: {
                        required: true,
                        email: true
                    },
                    userName: {
                        minlength: 6,
                        required: true
                    },
                    userPassword: {
                        minlength: 8,
                        pwcheck: true,
                        required: true
                    },
                    user_agrees: {
                        required: true
                    }
                },
                messages: {
                    userPassword: {
                        required: "Password required.",
                        pwcheck: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        minlength:  "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters."

                    },
                    userEmail: {
                        required: "Email is required.",
                        email: "Please enter a valid email."
                    },
                    userName: {
                        required: "Your username must be at least 6 characters long.",
                        minlength:  "Your username must be at least 6 characters long."
                    },
                    user_agrees: {
                        required: ""
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
    });
</script>
<?php $this->stop() ?>