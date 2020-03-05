<?php
$title_meta = _('Login to Tracksz, a Multiple Market Inventory Management Service');
$description_meta = _('Login to your Tracksz account. A Multiple Market Inventory Management Service');

?>
<?=$this->layout('layouts/noheaderfooter', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
    <main class="auth auth-floated">
        <div class="auth-form">
            <!-- form -->
            <form action="<?=\App\Library\Config::get('company_url')?>/login" id="login" method="POST">
                <div class="mb-4">
                    <div class="mb-3">
                        <img class="rounded" src="assets/apple-touch-icon.png" alt="" height="72" title="<?=_('The icon image for  Tracksz')?>">
                    </div>
                    <h1 class="h3"> <?=_('Login')?> </h1>
                </div>
                <p class="text-left mb-4"> <?=_('Don\'t have an account?')?> <a href="<?=\App\Library\Config::get('company_url');?>/register" title="<?=_('Register for Tracksz')?>"><?=_('Create One')?></a></p>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
                <!-- .form-group -->
                <div class="form-group mb-4">
                    <label class="d-block text-left" for="inputUser"><?=_('Username')?> </label> <input type="text" name="inputUser" id="inputUser" class="form-control form-control-lg" required="" autofocus="" title="<?=_('Enter a username for your Tracksz account')?>">
                </div><!-- /.form-group -->
                <!-- .form-group -->
                <div class="form-group mb-4">
                    <label class="d-block text-left" for="inputPassword"><?=_('Password')?> </label> <input type="password" name="inputPassword" id="inputPassword" class="form-control form-control-lg" required="" title="<?=_('Enter a password for your Tracksz account')?>">
                </div><!-- /.form-group -->
                <!-- .form-group -->
                <div class="form-group text-center">
                    <div class="custom-control custom-control-inline custom-checkbox">
                        <input name="remember" type="checkbox" class="custom-control-input" id="remember-me" title="<?=_('Keep logged into your Tracksz account')?>"> <label class="custom-control-label" for="remember-me" title="<?=_('Keep logged into your Tracksz account')?>"><?=_('Keep Me Logged In')?></label>
                    </div>
                </div><!-- /.form-group -->
                <!-- .form-group -->
                <div class="form-group mb-4">
                    <button class="btn btn-lg btn-primary btn-block" type="submit"><?=_('Login')?></button>
                </div><!-- /.form-group -->
                <!-- recovery links -->
                <p class="py-2">
                    <a href="/username" title="<?=_('Forgot Username Link for your Tracksz account')?>"><?=_('Forgot Username?')?></a> <span class="mx-2">·</span> <a href="/password" title="<?=_('Forgot Password Link for your Tracksz account')?>"><?=_('Forgot Password?')?></a>
                </p><!-- /recovery links -->
            </form>
            <!-- /.auth-form -->
    
            <?php $this->insert('partials/auth_copyright') ?>
        </div>
        <!-- .auth-announcement -->
        <div id="announcement" class="auth-announcement" style="background-image: url(/assets/images/illustration/trackszlogin.jpg);">
            <div class="announcement-body">
                <h5> <?=('Manage Your Inventory and Orders')?> </h5>
                <h2 class="announcement-title"><?=_('Login To Your Tracksz Account')?> </h2>
                <h6> <?=('Not Registered Yet?')?> </h6>
                <a href="<?=\App\Library\Config::get('company_url')?>/register" class="btn btn-warning mt-2"  title="<?=_('Register For Your Tracksz Account.')?>"><i class="fa fa-fw fa-user-cog"></i> <?=('Register for Tracksz')?></a>
            </div>
        </div><!-- /.auth-announcement -->
    </main><!-- /.auth -->
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="assets/vendor/jquery-validate/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('form').each(function() {   // <- selects every <form> on page
            $(this).validate({
                rules: {
                    inputUser: {
                        minlength: 6,
                        required: true
                    },
                    inputPassword: {
                        minlength: 8,
                        pwcheck: true,
                        required: true
                    }
                },
                messages: {
                    inputPassword: {
                        required: "Password required.",
                        pwcheck: "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters.",
                        minlength:  "Your password must be at least 8 characters long, contain at least one number and have a mixture of uppercase and lowercase letters."

                    },
                    inputUser: {
                        required: "Your username must be at least 6 characters long.",
                        minlength:  "Your username must be at least 6 characters long."
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