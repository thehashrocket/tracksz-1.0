<?php
    $title_meta = 'Tracksz.com MultiMarket Inventory and Order Management Service';
    $description_meta = 'Tracksz.com allows user to manage inventory and orders in one location while keeping all Stores, Marketplaces and Listing sites completely in sync';
?>
<?=$this->layout('layouts/frontend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
<!-- hero -->
<section class="mt-6 bg-blue page-cover">
    <?php if(isset($alert) && $alert):?>
        <div class="row text-center">
            <div class="col-sm-12 alert alert-<?=$alert_type?> text-center"><?=$alert?></div>
        </div>
    <?php endif ?>

    <a id="home"></a>
    <!-- .container -->
    <div class=" py-6 container container-fluid-xl">
        <!-- .row -->
        <div class="row align-items-center">
            <div class= "col-md-12 text-center">
                <h1 class="display-4 enable-responsive-font-size mb-4 text-white"><strong>Managing Inventory<br>& Orders Everywhere?</strong></h1>
                <h3 class="display-6 enable-responsive-font-size mb-4 text-white">Stop Wasting Time & Duplicating Effort!</h3>
                <p class="lead text-muted mb-2 text-white">Tracksz is a MultiMarket Inventory & Order Management Service for one stop management. <br><strong>Reclaim your time now!</strong></p>
                <a href="#interested" class="btn btn-lg btn-primary d-block d-sm-inline-block" data-aos="zoom-in" data-aos-delay="200">Find Out More<i class="fa fa-angle-right ml-2"></i></a>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /hero -->
<!-- sections title -->
<section class="py-6">
    <!-- .container -->
    <div class="container">
        <!-- .row -->
        <div class="row">
            <!-- .col -->
            <div class="col-12 text-center">
                <h2>Manage Everything in One Place</h2>
                <p class="lead text-muted">Manage your inventory and orders in one location while keeping all your Stores, Marketplaces and Listing sites completely in sync everywhere. </p>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /sections title -->
<!-- feature -->
<section class="bg-blue">
    <!-- .container -->
    <div class="container container-fluid-xl bg-div">
        <!-- .row -->
        <div class="row py-6 align-items-center">
            <!-- .col-md-6 -->
            <div class="offset-lg-5 col-md-6 text-center text-sm-left">
                <h3 class="mb-4 text-white">Tracksz in Active Development Now!</h3>
                <h4 class="mb-4 text-white">Help Us Plot A Course</h4>
                <p class="lead text-muted text-white"><a href="https://www.chrislands.com" target="_blank" class="text-white" title="ChrisLands.com, an eCommerce Service Provider for Booksellers and more">ChrisLands.com</a>, a 20+ year veteran in Internet Development, is actively developing Tracksz now.</p>
                <p class="lead text-muted text-white">You can help plot a course by providing input into the direction of development.  What features do you need?  What marketplaces do you want to support?</p>
                <p class="lead text-muted text-white">Get exactly what you need with just a little input!</p>
                <p class="lead text-white"><strong>Tentative Launch Dates</strong></p>
                <ul class="text-white">
                    <li>Pre-Launch: May 1, 2020</li>
                    <li>Soft Launch: May 22, 2020</li>
                    <li>Full Launch: June 15, 2020</li>
                </ul>
                <a id="features"></a>
                <a href="#interested" class="btn btn-lg btn-primary d-block d-sm-inline-block" data-aos="zoom-in" data-aos-delay="200">Interested?<i class="fa fa-angle-right ml-2"></i></a>
            </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- feature -->
<section class="position-relative py-6 bg-light">
    <!-- .sticker -->
    <div class="sticker">
        <div class="sticker-item sticker-top-right sticker-soften">
            <img src="assets/images/decoration/cubes.svg" alt="" data-aos="zoom-in">
        </div>
        <div class="sticker-item sticker-bottom-left sticker-soften scale-150">
            <img src="assets/images/decoration/cubes.svg" alt="" data-aos="zoom-in">
        </div>
    </div><!-- /.sticker -->
    <!-- .container -->
    <div class="container position-relative">
        <h2 class="text-center text-sm-left"> Planned Features </h2>
        <p class="lead text-muted text-center text-sm-left mb-5">&nbsp;</p>
        <!-- .card-deck -->
        <div class="card-deck-lg">
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="0">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/rocket.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0"> Inventory Uploads & Management </h5>
                            <p class="text-muted font-size-lg"> Upload, add, edit, and delete inventory then send out your changes to all your marketplaces and listing sites. All from one place! </p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="100">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/document.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0"> Order Retrieval </h5>
                            <p class="text-muted font-size-lg"> Tracksz queries all your listing sites and marketplaces to find any orders you received and retrieves that order for you.  </p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.card-deck -->
        <!-- .card-deck -->
        <div class="card-deck-lg">
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/easy-config.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0">Support for Diverse Product Lines </h5>
                            <p class="text-muted font-size-lg"> Support for all product types including Books, Clothes, Games, Furniture, Antiques, and many more. If you have a product you list in multiple locations, we support it!</p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="300">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/scale.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0"> Order Notification </h5>
                            <p class="text-muted font-size-lg">When Tracksz receives an order for you it will notify you and your customer.  Additional notifications available as the order status is updated. </p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.card-deck -->
        <!-- .card-deck -->
        <div class="card-deck-lg">
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="400">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/savings.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0"> Repricing Strategies </h5>
                            <p class="text-muted font-size-lg"> Various methods to accomplish inventory repricing including price settings you establish for each of your Marketplaces.</p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="500">
                <!-- .card-body -->
                <div class="card-body p-4">
                    <div class="d-sm-flex align-items-start text-center text-sm-left">
                        <img src="assets/images/illustration/setting.svg" class="mr-sm-4 mb-3 mb-sm-0" alt="" width="72">
                        <div class="flex-fill">
                            <h5 class="mt-0">Postage Integration </h5>
                            <p class="text-muted font-size-lg"> Use a postage partner or establish your own shipping.  Your choice.  </p>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.card-deck -->
        <!-- .section-block -->
        <div class="section-block">
            <h3 class="section-title"> and more.... </h3>
        </div><!-- /.section-block -->
        <!-- grid row -->
        <div class="row">
            <!-- grid column -->
            <div class="col-lg-4">
                <!-- .card -->
                <div class="card card-fluid">
                    <!-- .list-group -->
                    <div class="list-group list-group-flush list-group-divider">
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-success"><span class="fa fa-upload"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Image Uploads</a>
                                </h4>
                                <p class="list-group-item-text"> Save your images in one place. </p>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-danger"><span class="fa fa-list-ul"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Inventory Categories</a>
                                </h4>
                                <p class="list-group-item-text">Categorize each item. </p>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-purple"><span class="fa fa-arrows-alt"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Inventory Synchronization</a>
                                </h4>
                                <p class="list-group-item-text">With Multple Marketplaces</p>
                            </div>
                        </div><!-- /.list-group-item -->
                    </div><!-- /.list-group -->
                </div><!-- /.card -->
            </div> <!-- ./.grid-col -->
            <!-- grid column -->
            <div class="col-lg-4">
                <!-- .card -->
                <div class="card card-fluid">
                    <!-- .list-group -->
                    <div class="list-group list-group-flush list-group-divider">
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-info"><span class="fa fa-server"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">API Data Retrieval</a>
                                </h4>
                                <p class="list-group-item-text"> Get Your Data Your Way </p>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-cyan"><span class="fa fa-user"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">One Account</a>
                                </h4>
                                <p class="list-group-item-text"> Sign Up Once </p>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-muted"><span class="fa fa-shopping-cart"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Multiple Stores</a>
                                </h4>
                                <p class="list-group-item-text">Support All Your Retail Ventures</p>
                            </div>
                        </div><!-- /.list-group-item -->
                    </div><!-- /.list-group -->
                </div><!-- /.card -->
            </div> <!-- ./.grid-col -->
            <!-- grid column -->
            <div class="col-lg-4">
                <!-- .card -->
                <div class="card card-fluid">
                    <!-- .list-group -->
                    <div class="list-group list-group-flush list-group-divider">
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-gray"><span class="fa fa-users"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Employee Management</a>
                                </h4>
                                <p class="list-group-item-text">Let Someone Help</p>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-indigo"><span class="fa fa-chart-area"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Reports & Statistics</a>
                                </h4>
                                <p class="list-group-item-text">Check the Stats</p>
                            </div>
                            <div class="list-group-item-figure">
                                <button class="btn btn-sm btn-icon btn-light"><i class="oi oi-data-transfer-download"></i></button>
                            </div>
                        </div><!-- /.list-group-item -->
                        <!-- .list-group-item -->
                        <div class="list-group-item">
                            <div class="list-group-item-figure">
                                <a href="#" class="tile tile-circle bg-info"><span class="fa fa-file-image"></span></a>
                            </div>
                            <div class="list-group-item-body">
                                <h4 class="list-group-item-title">
                                    <a href="#">Much More...</a>
                                </h4>
                                <p class="list-group-item-text"> Just too much to list here..</p>
                            </div>
                            <div class="list-group-item-figure">
                                <button class="btn btn-sm btn-icon btn-light"><i class="oi oi-data-transfer-download"></i></button>
                            </div>
                        </div><!-- /.list-group-item -->
                    </div><!-- /.list-group -->
                </div><!-- /.card -->
            </div> <!-- ./.grid-col -->
        </div>
        <!-- form row -->
        <div class="form-row mb-3">
            <!-- form column -->
            <div class="col-md-12">
                <!-- .form-actions -->
                <div class="mb-4 text-center">
                    <a href="#interested" class="btn btn-lg btn-primary d-block d-sm-inline-block" data-aos="zoom-in" data-aos-delay="200">Interested Now<i class="fa fa-angle-right ml-2"></i></a>
                </div><!-- /.form-actions -->
            </div>
        </div>
    </div><!-- /.container -->
    <a id="markets"></a>
</section><!-- /feature -->
<!-- testimonials -->
<section class="position-relative py-6 bg-muted">
    <!-- .sticker -->
    <div class="sticker">
        <div class="sticker-item sticker-top-left sticker-soften">
            <img src="/assets/images/decoration/bubble2.svg" alt="" data-aos="zoom-in" data-aos-delay="200">
        </div>
    </div><!-- /.sticker -->
    <!-- .container -->
    <div class="container position-relative mb-3">
        <h2 class="text-center"> Supported Marketplaces</h2>
        <p class="lead text-center text-muted mb-5"> Here is a list of the <strong>initial</strong> sites we intend to support.  This may change as we receive your suggestions. </p><!-- .row -->
        <div class="row mb-6">
            <!-- .col -->
            <div class="col-12">
                <!-- .row -->
                <div class="row justify-content-center text-center">
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/abebooks.png" alt="Abebooks.com" title="Logo for Abebooks.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/alibris.png" alt="Alibris.com" title="Logo for Alibris.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/amazon.png" alt="Amazon.com" title="Logo for Amazon.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/barnesnnoble.png" alt="Barnes and Noble" title="Logo for Barnes & Noble, bn.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/biblio.png" alt="Biblio.com" title="Logo for Biblio.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <a href="https://www.chrislands.com" target="_blank" title="ChrisLands eCommerce service, providing an easy and affordable solution to operating your own online store."><img src="/assets/images/logo/chrislands.png" alt="ChrisLands.com" title="ChrisLands eCommerce service, providing an easy and affordable solution to operating your own online store"></a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/ebay.png" alt="eBay.com" title="Logo for eBay.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/ecampus.png" alt="eCampus.com" title="Logo for eCampus.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/tracksz.png" alt="Tracksz.com" title="Logo for Tracksz.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-2" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/valorebooks.png" alt="Valorebooks.com" title="Logo for ValoreBooks.com">
                    </div>
                </div><!-- /.row -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    <!-- .sticker -->
    <div class="sticker">
        <div class="sticker-item sticker-bottom-right sticker-soften">
            <img src="/assets/images/decoration/bubble3.svg" alt="" data-aos="zoom-in" data-aos-delay="200">
        </div>
    </div><!-- /.sticker -->
    <!-- .container -->
    <div class="container position-relative mb-2">
        <h2 class="text-center"> Marketplaces Under Consideration</h2>
        <p class="lead text-center text-muted mb-5"> We are investigating the interest in support, the ability to support, and the timing of support for the following marketplaces. </p><!-- .row -->
        <div class="row mb-2">
            <!-- .col -->
            <div class="col-12 py-3">
                <!-- .row -->
                <div class="row justify-content-center text-center">
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <a href="https://www.ankols.com" target="_blank" title="Ankols, a new kind of listing, creating the place to buy and sell new and used books, electronics, furniture, clothing, fashion, beauty, pet supplies, movies, games, antiques, crafts, home interior and more."><img src="/assets/images/logo/ankols.png" alt="Ankols.com" title="Ankols, a new kind of listing, creating the place to buy and sell new and used books, electronics, furniture, clothing, fashion, beauty, pet supplies, movies, games, antiques, crafts, home interior and so more."></a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/bigcommerce.png" alt="BigCommerce.com" title="Logo for BigCommerce.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <a href="https://www.ankols.com" target="_blank" title="Search hundreds of independent stores at ChrisLands Search, millions of books and other items to find."><img src="/assets/images/logo/chrislandssearch.png" alt="ChrisLandsSearch.com" title="Search hundreds of independent stores at ChrisLands Search, millions of books and other items to find."></a>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/etsy.png" alt="Etsy.org" title="Logo for Etsy.org">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/ioba.png" alt="Ioba.org" title="Logo for Ioba.org">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/jet.png" alt="Jet.com" title="Logo for Jet.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/overstock.png" alt="Overstock.com" title="Logo for Overstock.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/shopify.png" alt="Shopify.com" title="Logo for Shopify.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/volusion.png" alt="Volusion.com" title="Logo for Volusion.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/wayfair.png" alt="Wayfair.com" title="Logo for Wayfair.com">
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-5" data-aos="fade-up" data-aos-delay="100">
                        <img src="/assets/images/logo/walmart.png" alt="Walmart.com" title="Logo for Walmart.com">
                    </div>
                </div><!-- /.row -->
            </div><!-- /.col -->
            <h6 class="text-center text-sm-left"> Please Note: </h6>
            <p class="text-muted text-center text-sm-left mb-1">1. Support of a marketplace is dependent upon the ability to programmatically upload inventory and retrieve orders. It also depends upon the marketplace not having any conditions in their terms of use preventing Tracksz type of integration.</p>
            <p class="text-muted text-center text-sm-left mb-1">2. Company names and logos are trademarks™ or registered® trademarks of or copyrighted by their respective holders. Use of them does not imply any affiliation with or endorsement by them.</p>
        </div><!-- /.row -->
    </div><!-- /.container -->
    <a id="pricing"></a>
</section><!-- /testimonials -->
<!-- feature -->
<section class="position-relative py-6 bg-light">
    <!-- .sticker -->
    <div class="sticker">
        <div class="sticker-item sticker-bottom-left sticker-soften translate-x-50">
            <img src="assets/images/decoration/bubble1.svg" alt="" data-aos="fade-left">
        </div>
    </div><!-- /.sticker -->
    <!-- .container -->
    <div class="container position-relative">
        <h2 class="text-center"> Simple pricing </h2>
        <p class="lead text-muted text-center mb-5">Tracksz automatically adjust your monthly fee level, listed below, so you receive the lowest possible monthly fees. </p><!-- .row -->
        <div class="row align-items-center">
            <!-- .col -->
            <div class="col-12 col-md-4 py-md-4 pr-md-0">
                <div class="card font-size-lg card-inverse shadow-lg bg-primary" data-aos="fade-up">
                    <h5 class="card-header text-center p-4 px-lg-5"> Standard </h5>
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-center mb-0">Minimum.</p>
                        <h5 class="display-3 text-center">
                            <sup><small>$</small></sup>40<small><small>/mo</small></small>
                        </h5>
                        <p class="text-center text-muted-light mb-3">Full system access including the following features.</p>
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Up to 15,000 Inventory Items</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Unlimited Marketplaces </li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Sync Data Across All Marketplaces </li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Inventory & Order Management </li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> More.. </li>
                        </ul>
                        <hr class="alert-success">
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 1.3% of orders up to $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 0.7% of orders above $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> $5 each additional 10,000 item allowance over 15,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span>Optional: Tracksz Listing Service at 11% commission on each sale</li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.col -->
            <!-- .col -->
            <div class="col-12 col-md-4 py-md-4 pr-md-0">
                <div class="card font-size-lg shadow-lg" data-aos="fade-up"  data-aos-delay="200">
                    <h5 class="card-header text-center text-success p-4 px-lg-5"> Extended </h5>
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-center text-muted mb-0">Minimum.</p>
                        <h5 class="display-3 text-center">
                            <sup><small>$</small></sup>100<small><small>/mo</small></small>
                        </h5>
                        <p class="text-center text-muted mb-3">Includes everything in Standard with the following changes.</p>
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Up to 40,000 Inventory Items</li>
                        </ul>
                        <hr class="alert-success">
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 1.15% of orders up to $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 0.6% of orders above $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> $4 each additional 10,000 item allowance over 40,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span>Optional: Tracksz Listing Service at 9.5% commission on each sale</li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.col -->
            <!-- .col -->
            <div class="col-12 col-md-4 py-md-4 pr-md-0">
                <div class="card font-size-lg shadow-lg" data-aos="fade-left"  data-aos-delay="300">
                    <h5 class="card-header text-center text-success p-4 px-lg-5"> Professional </h5>
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-center text-muted mb-0">Minimum.</p>
                        <h3 class="display-3 text-center">
                            <sup><small>$</small></sup>180<small><small>/mo</small></small>
                        </h3>
                        <p class="text-center text-muted mb-3">Full system access as Standard & Extended with these changes.</p>
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> Up to 90,000 Inventory Items</li>
                        </ul>
                        <hr class="alert-success">
                        <ul class="list-icons">
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 1.0% of orders up to $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> 0.5% of orders above $10,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span> $3 each additional 10,000 item allowance over 90,000</li>
                            <li class="mb-2 pl-1">
                                <span class="list-icon"><img class="mr-2" src="/assets/images/decoration/check.svg" alt="" width="16"></span>Optional: Tracksz Listing Service at 8% commission on each sale</li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.col -->
            <p><small>* Monthly fees are determined by the monthly inventory count average plus the commission on order totals or the minimum monthly fee, whichever is greater.<br>
            * Fees are adjusted up or down fee levels based on the lowest fee calculation.<br>
            * Fees are paid in the arrears, for services rendered.  The current month's activity determines the fees for the next month.<br>
                * Fees for the Tracksz Listing Service include credit card processing fees.</small></p>
            <div class="col-12 text-center mb-2">
                <h3><strong><a style="color: orangered" href="https://www.chrislands.com" target="_blank" title="ChrisLands.com, an eCommerce Service Provider for Booksellers and more">ChrisLands.com</a></strong> stores receive a 25% monthly discount!</h3>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
    <a id="interested"></a>
</section><!-- /feature -->
<!-- feature -->
<section class="position-relative pb-6 bg-light">
    <!-- .sticker -->
    <div class="sticker">
        <div class="sticker-item sticker-top-right sticker-soften">
            <img src="assets/images/decoration/cubes.svg" alt="" data-aos="zoom-in">
        </div>
        <div class="sticker-item sticker-bottom-left sticker-soften scale-150">
            <img src="assets/images/decoration/cubes.svg" alt="" data-aos="zoom-in">
        </div>
    </div><!-- /.sticker -->
    <!-- .container -->
    <div class="container position-relative">
        <h2 class="text-center text-sm-left"> Find Out More! </h2>
        <p class="lead text-muted text-center text-sm-left mb-5">Please fill out this form and click <strong>I'm Interested</strong> at the bottom to be notified of our progress.</p><!-- .card-deck -->
        <div class="card-deck-lg">
            <!-- .card -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="0">
                <!-- .card-body -->
                <div class="card-body">
                    <!-- .form -->
                    <form id="interested-form" action="/interested" method="post" data-parsley-validate="">
                        <!-- .fieldset -->
                        <fieldset>
                            <legend>Store Information</legend>
                            <!-- form row -->
                            <div class="form-row mb-3">
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="fullname" class="required-field"><?=_('Your Name')?></label> <input type="text" title="<?=_('Enter Your Name.')?>" class="form-control form-control-lg" id="fullname" name="FullName" placeholder="My Fullname" data-parsley-required-message="Please insert your full name." required maxlength="100" <?php if (isset($interested['FullName'])) echo ' value="' . $interested['FullName'] .'"' ?>>
                                    </div><!-- /.form-group -->
                                </div>
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label class="required-field" for="Email"><?=_('Your Email')?></label> <input name="Email" type="email" class="form-control form-control-lg" id="Email" title="<?=_('Enter your valid email address.')?>" placeholder="myemail@address.com"  data-parsley-required-message="Please insert a valid email." required data-parsley-type="email" maxlength="128"<?php if (isset($interested['Email'])) echo ' value="' . $interested['Email'] .'"' ?>>
                                    </div><!-- /.form-group -->
                                </div>
                                <!-- form column -->
                                <div class="col-md-4">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="telephone" class="required-field"><?=_('Phone Number')?> <small>(Only Enter Numbers)</small></label> <input type="text" title="<?=_('Your Phone number. Only Enter Numbers.')?>" class="form-control form-control-lg" id="telephone" name="Telephone" placeholder="1234567890"  data-parsley-required-message="Please insert telephone number." required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="31"<?php if (isset($interested['Telephone'])) echo ' value="' . $interested['Telephone'] .'"' ?>>
                                    </div><!-- /.form-group -->
                                </div>
                            </div>
                            <!-- form row -->
                            <div class="form-row mb-3">
                                <!-- form column -->
                                <div class="col-md-12">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="contact-markets" class="form-label-outside">Markets You List Inventory:</label>
                                        <textarea id="contact-markets" name="Markets" rows="3" class="form-control" placeholder="For example: ChrisLands, Amazon, Abebooks, Alibris, Biblio...."></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- form row -->
                            <div class="form-row mb-3">
                                <!-- form column -->
                                <div class="col-md-12">
                                    <!-- .form-group -->
                                    <div class="form-group">
                                        <label for="contact-features" class="form-label-outside">Suggested Features:</label>
                                        <textarea id="contact-features" name="Features" rows="3" class="form-control" placeholder="I would like to see........"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- form row -->
                            <div class="form-row mb-3">
                                <!-- form column -->
                                <div class="col-md-12">
                                    <!-- .form-actions -->
                                    <div class="mb-4 text-center">
                                        <button class="btn btn-primary" type="submit" title="Submit I'm Interested in Tracksz Form">I'm Interested </button>
                                    </div><!-- /.form-actions -->
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?=$this->stop()?>

<?php $this->start('footer_extras') ?>
<script src="/assets/vendor/parsleyjs/parsley.min.js"></script>
<script>
    $(document).ready(function () {
        $('#telephone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            $(this).val(number);
        });
    });
</script>
<?php $this->stop() ?>


