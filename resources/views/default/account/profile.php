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

        <!-- .page-inner -->
        <div class="page-inner">
            <header class="page-title-bar">
                <div class="d-flex flex-column flex-md-row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/account/panel" title="Tracksz Account Dashboard"><i class="breadcrumb-icon fa fa-angle-left mr-2"></i><?=('Dashboard')?></a>
                            </li>
                            <li class="breadcrumb-item active"><?=('Profile')?></li>
                        </ol>
                    </nav>
                    <!-- Insert Active Store Header -->
                    <?php $this->insert('partials/active_store'); ?>
                </div>
                <?php if(isset($alert) && $alert):?>
                    <div class="row text-center">
                        <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
                    </div>
                <?php endif ?>
            </header>
            <!-- .page-section -->
            <div class="page-section">
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
            </div><!-- /.page-section -->
        </div><!-- /.page-inner -->
    </div><!-- /.page -->
</div><!-- /.wrapper -->
<?=$this->stop()?>

<?php $this->start('plugin_js') ?>
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


