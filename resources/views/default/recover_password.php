<?php
$title_meta = _('Reset your Tracksz password, a Multiple Market Inventory Management Service');
$description_meta = _('Reset your Tracksz password. A Multiple Market Inventory Management Service');

?>
<?=$this->layout('layouts/noheaderfooter', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
    <!-- .auth -->
    <main class="auth">
        <!-- form -->
        <form class="auth-form auth-form-reflow" id="reset-password" action="/password" method="post">
            <div class="text-center mb-4">
                <div class="mb-4">
                    <img class="rounded" src="assets/apple-touch-icon.png" alt="" height="72">
                </div>
                <h1 class="h3"><?=_('Reset Your Password')?></h1>
            </div>
            <p class="mb-4"> <?=_('Enter your Tracksz Username below and we will email you a link to reset your password.  The link expires in one hour.')?></p>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <div class="form-group mb-4">
                <label class="d-block text-left" for="username">Username</label> <input type="text" id="username" class="form-control form-control-lg" required="" autofocus="" name="username" title="<?=_('Enter your Tracksz Username to receive a password reset email.')?>" <?php if (isset($usernamel)) echo ' value="'.$username.'"'?>>
                <p class="text-muted">
                    <small><?=_('We\'ll send a password reset link to your email.')?></small>
                </p>
            </div><!-- /.form-group -->
            <!-- actions -->
            <div class="d-block d-md-inline-block mb-2">
                <button class="btn btn-lg btn-block btn-primary" type="submit" title="<?=_('Submit Tracksz reset password form.')?>"><?=_('Reset Password')?></button>
            </div>
            <div class="d-block d-md-inline-block">
                <a href="/login" class="btn btn-block btn-light" title="<?=_('Return to Tracksz Login page.')?>"><?=_('Return to Login')?></a>
            </div>
        </form><!-- /.auth-form -->
        <?php $this->insert('partials/auth_copyright') ?>
    </main><!-- /.auth -->
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
    <script src="assets/vendor/jquery-validate/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#reset-password').validate({
                rules: {
                    username: {
                        minlength: 6,
                        required: true
                    },
                },
                messages: {
                    username: {
                        required: "Your username must be at least 6 characters long.",
                        minlength:  "Your username must be at least 6 characters long."
                    },
                },
                highlight: function (element) {
                    $(element).closest('.form-control').addClass('is-invalid');
                },
                unhighlight: function(element) {
                    jQuery(element).closest('.form-control').removeClass('is-invalid');
                }
            });
        });
    </script>
<?php $this->stop() ?>