<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- End Required meta tags -->
    <!-- Begin SEO tag -->
    <title><?= $this->e($title) ?></title>
    <meta name="description" content="<?= $this->e($description) ?>">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <!-- End SEO tag -->
    <!-- FAVICONS -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/assets/images/favicons/manifest.json" crossorigin="use-credentials">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/images/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- End FAVICONS -->
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet">
    <!-- End GOOGLE FONT -->
    <!-- BEGIN PLUGINS STYLES -->
    <link rel="stylesheet" href="/assets/vendor/open-iconic/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" href="/assets/vendor/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/vendor/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/stylesheets/dropzone.css">
    <!-- END PLUGINS STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="/assets/stylesheets/theme.min.css" data-skin="default">
    <link rel="stylesheet" href="/assets/stylesheets/custom.css">

    <!-- BEGIN PAGE LEVEL STYLES -->
    <!-- styles for specific page goes here -->
    <!-- END PAGE LEVEL STYLES -->

    <?= $this->section('header_extras') ?>
    <script>
        var isCompact = JSON.parse(localStorage.getItem('hasCompactMenu'));
        // add flag class to html immediately
        if (isCompact == true) document.querySelector('html').classList.add('preparing-compact-menu');
    </script><!-- END THEME STYLES -->
</head>

<body>
    <!-- .app -->
    <div class="app">
        <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
    <![endif]-->
        <!-- .app-header -->
        <?php $this->insert('partials/header_backend') ?>
        <?php $this->insert('partials/backend_aside') ?>

        <!-- .app-main -->
        <main class="app-main">

            <?= $this->section('page_content') ?>

            <?php $this->insert('partials/footer_backend') ?>

        </main>
    </div><!-- /.app -->

    <!-- BEGIN BASE JS -->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/popper.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!-- END BASE JS -->
    <script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/vendor/flatpickr/flatpickr.min.js"></script>
    <script src="/assets/vendor/flatpickr/plugins/monthSelect/index.js"></script>
    <script src="/assets/javascript/dropzone.js"></script>
    <!-- BEGIN PLUGINS JS -->
    <?= $this->section('plugin_js') ?>
    <!-- END PLUGINS JS -->

    <!-- BEGIN PAGE LEVEL JS -->
    <?= $this->section('page_js') ?>
    <!-- END PAGE LEVEL JS -->

    <!-- BEGIN THEME JS -->
    <script src="/assets/javascript/theme.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <!-- END THEME JS -->

    <!-- BEGIN PAGE LEVEL JS -->
    <?= $this->section('footer_extras') ?>
    <!-- END PAGE LEVEL JS -->


</body>
</html>
