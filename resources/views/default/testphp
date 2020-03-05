<?php
$title_meta = _('Reset your Tracksz password, a Multiple Market Inventory Management Service');
$description_meta = _('Reset your Tracksz password. A Multiple Market Inventory Management Service');

?>
<?=$this->layout('layouts/noheaderfooter', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
    <!-- .auth -->
    <main class="auth">
        <!-- form -->
        <form class="auth-form auth-form-reflow" id="reset-password" action="/reset" method="post">
            <input type="hidden" name="selector" value="<?=$selector?>">
            <input type="hidden" name="token" value="<?=$token?>">
            <div class="text-center mb-4">
                <div class="mb-4">
                    <img class="rounded" src="assets/apple-touch-icon.png" alt="" height="72">
                </div>
                <h1 class="h3"><?=_('Reset Your Password')?></h1>
            </div>
            <p class="mb-4"> <?=_('Enter your new password in the fields below.  They must be the same.')?></p>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <div class="form-group mb-4">
                <label class="required-field d-block text-left" for="new_password"><?=_('New Password')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Your password must be at least 8 characters long, contain at least one number and have both uppercase and lowercase letters. We recommend at least one special character =!-@._*#')?>"></i></label> <input type="password" title="<?=_('Enter a new password.  It must be at least 8 characters long, contain at least one number and have both uppercase and lowercase letters. We recommend at least one special character =!-@._*#')?>" class="form-control" id="new_password" name="new_password">
            </div><!-- /.form-group -->
            <div class="form-group mb-4">
                <label class="required-field d-block text-left" for="confirm_password"><?=_('Confirm Password')?> <i class="far fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?=_('Enter the New Password again.')?>"></i></label> <input type="password" title="<?=_('Enter Tracksz Member\'s New Password again.')?>" class="form-control" id="confirm_password" name="confirm_password">
            </div><!-- /.form-group -->
            <!-- actions -->
            <div class="d-block d-md-inline-block mb-2">
                <button class="btn btn-lg btn-block btn-primary" type="submit" title="<?=_('Submit Tracksz form to change your password')?>"><?=_('Reset Password')?></button>
            </div>
            <div class="d-block d-md-inline-block">
                <a href="/login" class="btn btn-block btn-light" title="<?=_('Go to Tracksz Login page.')?>"><?=_('Go to Login')?></a>
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
</script>
<?php $this->stop() ?>