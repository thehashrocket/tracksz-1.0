<?php
$title_meta = _('Recover your Tracksz username, a Multiple Market Inventory Management Service');
$description_meta = _('Recover your Tracksz username. A Multiple Market Inventory Management Service');

?>
<?=$this->layout('layouts/noheaderfooter', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
    <!-- .auth -->
    <main class="auth">
        <!-- form -->
        <form class="auth-form auth-form-reflow" action="/username" method="post" id="recover-username">
            <div class="text-center mb-4">
                <div class="mb-4">
                    <img class="rounded" src="assets/apple-touch-icon.png" alt="" height="72">
                </div>
                <h1 class="h3"><?=_('Recover Your Username')?> </h1>
            </div>
            <p class="mb-4"><?=_('Enter the email address you registered with and we will email you your Username.')?></p>
            <?php if(isset($alert) && $alert):?>
                <div class="row text-center">
                    <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                </div>
            <?php endif ?>
            <!-- .form-group -->
            <div class="form-group mb-4">
                <label class="d-block text-left" for="inputEmail"><?=_('Email Address')?></label> <input name="email" type="text" id="inputEmail" class="form-control form-control-lg" required="" autofocus="" title="<?=_('Enter the email address you registered with and we will email you your Username.')?>" <?php if (isset($email)) echo ' value="'.$email.'"'?>>
            </div><!-- /.form-group -->
            <!-- actions -->
            <div class="d-block d-md-inline-block mb-2">
                <button class="btn btn-lg btn-block btn-primary" type="submit" title="<?=_('Submit Tracksz recover username form.')?>"><?=_('Send Username')?></button>
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
            $('#recover-username').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    email: {
                        required: "Email is required.",
                        email: "Please enter a valid email."
                    },
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