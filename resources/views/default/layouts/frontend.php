<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?=$this->e($title)?></title>
    <meta name="description" content="<?=$this->e($description)?>">
    
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="Tracksz ">
    <meta property="og:type" content="website" />
    <meta property="og:description" content="Tracksz allows user to manage inventory and orders in one location while keeping all Stores, Marketplaces and Listing sites completely in sync" />
    <meta property="og:title" content="Tracksz MultiMarket Inventory and Order Management Service" />
    <meta property="og:url" content="https://www.tracksz.com" />

    <script type="application/ld+json">
        {
            "name": "Tracksz, a Multiple Market Inventory Management Service",
            "description": "Multiple Market Inventory Management and Marketplace listing Service",
            "author":
            {
                "@type": "Company",
                "name": "ChrisLand Inc"
            },
            "@type": "WebSite",
            "url": "https://www.tracksz.com",
            "headline": "Tracksz Multiple Market Inventory and Order Management Service",
            "@context": "http://schema.org"
        }
    </script>
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
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/images/favicons/android-icon-192x192.png">
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
    <link rel="stylesheet" href="/assets/vendor/aos/aos.css">
    <!-- END PLUGINS STYLES -->
    
    <!-- BEGIN THEME STYLES -->
    <link rel="stylesheet" href="/assets/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" href="/assets/stylesheets/theme.min.css">
    <link rel="stylesheet" href="/assets/stylesheets/custom.css">
    
    <?=$this->section('header_extras')?>
</head>

<body>
<!-- .app -->
<main class="app app-site">
    <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
    <![endif]-->

<?php $this->insert('partials/header_frontend') ?>

<?=$this->section('page_content')?>

<?php $this->insert('partials/footer_frontend') ?>

</main><!-- /.app -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- BEGIN BASE JS -->
<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/popper.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- END BASE JS -->

<!-- BEGIN PLUGINS JS -->
<script src="assets/vendor/aos/aos.js"></script>
<!-- END PLUGINS JS -->

<!-- BEGIN THEME JS -->
<script src="assets/javascript/theme.min.js"></script>
<script>
    $(function () {
        $(document).scroll(function () {
            var $nav = $(".fixed-top");
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });
    });
</script>
<!-- END THEME JS -->

<!-- BEGIN PAGE LEVEL JS -->
<!-- your js for specific page goes here -->
<!-- END PAGE LEVEL JS -->

<?=$this->section('footer_extras')?>
</body>
</html>
