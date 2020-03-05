<!-- site header -->
<?php $base_url = \App\Library\Config::get('company_url'); ?>
<!-- .navbar -->
<nav class="navbar navbar-expand-lg py-3 fixed-top" data-aos="fade-in">
    <!-- .container -->
    <div class="container">
        <!-- .hamburger -->
        <button class="hamburger hamburger-squeeze hamburger-light d-flex d-lg-none" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> <!-- /.hamburger -->
        <!-- .navbar-brand -->
        <a class="navbar-brand ml-auto mr-0" href="<?=$base_url?>/" title="Home page link for Tracksz.com Mutlimarket Inventory and Order Management Service"><img src="/assets/images/logo3.png" title="Logo for Tracksz.com Mutlimarket Inventory and Order Management Service" alt="Logo for Tracksz.com Mutlimarket Inventory and Order Management Service"></a><!-- /.navbar-brand -->
        <?php if(\Delight\Cookie\Session::get('member_id')): ?>
            <a class="nav-link ml-auto order-lg-2" href="<?=$base_url?>/account" title="Login to Tracksz Multimarket Management System">Account</a
        <?php else: ?>
            <a class="nav-link ml-auto order-lg-2" href="<?=$base_url?>/login" title="Login to Tracksz Multimarket Management System">Login</a>
            <a class="nav-link ml-auto order-lg-2" href="<?=$base_url?>/register" title="Register for Tracksz Multimarket Management System">Register</a>
        <?php endif; ?>
    
        <!-- .navbar-collapse -->
        <div class="navbar-collapse collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mr-lg-3" data-toggle="collapse" data-target=".navbar-collapse.show">
                    <a class="nav-link py-2" href="<?=$base_url?>/#home" title="Link to Tracksz Home Page">Home</a>
                </li>
                <li class="nav-item mr-lg-3" data-toggle="collapse" data-target=".navbar-collapse.show">
                    <a class="nav-link py-2" href="<?=$base_url?>#features" title="Link to Tracksz Supported Features">Features</a>
                </li>
                <li class="nav-item mr-lg-3" data-toggle="collapse" data-target=".navbar-collapse.show">
                    <a class="nav-link py-2" href="<?=$base_url?>#markets" title="Link to Tracksz Supported Marketplaces">Marketplaces</a>
                </li>
                <li class="nav-item mr-lg-3" data-toggle="collapse" data-target=".navbar-collapse.show">
                    <a class="nav-link py-2" href="<?=$base_url?>#pricing" title="Link to Tracksz Pricing">Pricing</a>
                </li>
                <li class="nav-item mr-lg-3" data-toggle="collapse" data-target=".navbar-collapse.show">
                    <a class="nav-link py-2" href="<?=$base_url?>#interested" title="Link to Tracksz I'm Interested Form">Interested?</a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar -->
<!-- /site header -->