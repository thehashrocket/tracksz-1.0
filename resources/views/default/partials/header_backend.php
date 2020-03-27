<!-- .app-header -->
<header class="app-header app-header-dark">
    <script>
        var BASE_URL = '<?php echo \App\Library\Config::get('company_url'); ?>';
    </script>
    <!-- .top-bar -->
    <div class="top-bar">
        <!-- .top-bar-brand -->
        <div class="top-bar-brand">
            <!-- toggle aside menu -->
            <button class="hamburger hamburger-squeeze mr-2" type="button" data-toggle="aside-menu" aria-label="toggle aside menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> <!-- /toggle aside menu -->
            <a href="index.html"><img src="/assets/images/logo4.png" title="Logo for Tracksz.com Mutlimarket Inventory and Order Management Service"></a>
        </div><!-- /.top-bar-brand -->
        <!-- .top-bar-list -->
        <div class="top-bar-list">
            <!-- .top-bar-item -->
            <div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
                <!-- toggle menu -->
                <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="toggle menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> <!-- /toggle menu -->
            </div><!-- /.top-bar-item -->
            <!-- .top-bar-item -->
            <div class="top-bar-item top-bar-item-right px-0">
                <!-- .nav -->
                <ul class="header-nav nav">
                    <!-- .nav-item -->
                    <li class="nav-item dropdown header-nav-dropdown">
                        <a class="nav-link has-badge" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="badge badge-pill badge-warning">1</span> <span class="oi oi-pulse"></span></a> <!-- .dropdown-menu -->
                        <div class="dropdown-menu dropdown-menu-rich dropdown-menu-right">
                            <div class="dropdown-arrow"></div>
                            <h6 class="dropdown-header stop-propagation">
                                <span>Activities <span class="badge">(2)</span></span>
                            </h6><!-- .dropdown-scroll -->
                            <div class="dropdown-scroll perfect-scrollbar">
                                <!-- .dropdown-item -->
                                <a href="#" class="dropdown-item unread">
                                    <div class="user-avatar">
                                        <img src="/assets/images/avatars/uifaces15.jpg" alt="">
                                    </div>
                                    <div class="dropdown-item-body">
                                        <p class="text"> Jeffrey Wells created a schedule </p><span class="date">Just now</span>
                                    </div>
                                </a> <!-- /.dropdown-item -->
                            </div><!-- /.dropdown-scroll -->
                            <a href="user-activities.html" class="dropdown-footer">All activities <i class="fas fa-fw fa-long-arrow-alt-right"></i></a>
                        </div><!-- /.dropdown-menu -->
                    </li><!-- /.nav-item -->
                    <!-- .nav-item -->
                    <li class="nav-item dropdown header-nav-dropdown">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="oi oi-envelope-open"></span></a> <!-- .dropdown-menu -->
                        <div class="dropdown-menu dropdown-menu-rich dropdown-menu-right">
                            <div class="dropdown-arrow"></div>
                            <h6 class="dropdown-header stop-propagation">
                                <span>Messages</span> <a href="#">Mark all as read</a>
                            </h6><!-- .dropdown-scroll -->
                            <div class="dropdown-scroll perfect-scrollbar">
                                <!-- .dropdown-item -->
                                <a href="#" class="dropdown-item unread">
                                    <div class="user-avatar">
                                        <img src="/assets/images/avatars/team1.jpg" alt="">
                                    </div>
                                    <div class="dropdown-item-body">
                                        <p class="subject"> Stilearning </p>
                                        <p class="text text-truncate"> Invitation: Joe's Dinner @ Fri Aug 22 </p><span class="date">2 hours ago</span>
                                    </div>
                                </a> <!-- /.dropdown-item -->

                            </div><!-- /.dropdown-scroll -->
                            <a href="page-messages.html" class="dropdown-footer">All messages <i class="fas fa-fw fa-long-arrow-alt-right"></i></a>
                        </div><!-- /.dropdown-menu -->
                    </li><!-- /.nav-item -->
                    <!-- .nav-item -->
                    <li class="nav-item dropdown header-nav-dropdown">
                        <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="oi oi-grid-three-up"></span></a> <!-- .dropdown-menu -->
                        <div class="dropdown-menu dropdown-menu-rich dropdown-menu-right">
                            <div class="dropdown-arrow"></div><!-- .dropdown-sheets -->
                            <div class="dropdown-sheets">
                                <!-- .dropdown-sheet-item -->
                                <div class="dropdown-sheet-item">
                                    <a href="#" class="tile-wrapper"><span class="tile tile-lg bg-indigo"><i class="oi oi-people"></i></span> <span class="tile-peek">Teams</span></a>
                                </div><!-- /.dropdown-sheet-item -->
                                <!-- .dropdown-sheet-item -->
                                <div class="dropdown-sheet-item">
                                    <a href="#" class="tile-wrapper"><span class="tile tile-lg bg-teal"><i class="oi oi-fork"></i></span> <span class="tile-peek">Projects</span></a>
                                </div><!-- /.dropdown-sheet-item -->
                                <!-- .dropdown-sheet-item -->
                                <div class="dropdown-sheet-item">
                                    <a href="#" class="tile-wrapper"><span class="tile tile-lg bg-pink"><i class="fa fa-tasks"></i></span> <span class="tile-peek">Tasks</span></a>
                                </div><!-- /.dropdown-sheet-item -->
                                <!-- .dropdown-sheet-item -->
                                <div class="dropdown-sheet-item">
                                    <a href="#" class="tile-wrapper"><span class="tile tile-lg bg-yellow"><i class="oi oi-fire"></i></span> <span class="tile-peek">Feeds</span></a>
                                </div><!-- /.dropdown-sheet-item -->
                                <!-- .dropdown-sheet-item -->
                                <div class="dropdown-sheet-item">
                                    <a href="#" class="tile-wrapper"><span class="tile tile-lg bg-cyan"><i class="oi oi-document"></i></span> <span class="tile-peek">Files</span></a>
                                </div><!-- /.dropdown-sheet-item -->
                            </div><!-- .dropdown-sheets -->
                        </div><!-- .dropdown-menu -->
                    </li><!-- /.nav-item -->
                </ul><!-- /.nav -->
                <!-- .btn-account -->
                <div class="dropdown d-none d-sm-flex">
                    <button class="btn-account" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="user-avatar user-avatar-md"><img src="/assets/images/member/<?=\Delight\Cookie\Session::get('member_avatar')?>" alt=""></span> <span class="account-summary pr-md-4 d-none d-md-block"><span class="account-name"><?php if(\Delight\Cookie\Session::has('member_name')) echo  \Delight\Cookie\Session::get('member_name');?></span> <span class="account-description"><?=_('Admin Panel')?></span></span></button> <!-- .dropdown-menu -->
                    <div class="dropdown-menu">
                        <div class="dropdown-arrow ml-3"></div>
                        <h6 class="dropdown-header d-none d-sm-block d-md-none"> Beni Arisandi </h6><a class="dropdown-item" href="/account/profile"><span class="dropdown-icon oi oi-person"></span> Profile</a> <a class="dropdown-item" href="/logout"><span class="dropdown-icon oi oi-account-logout"></span> Logout</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Help Center</a> <a class="dropdown-item" href="#">Ask Forum</a>
                    </div><!-- /.dropdown-menu -->
                </div><!-- /.btn-account -->
            </div><!-- /.top-bar-item -->
        </div><!-- /.top-bar-list -->
    </div><!-- /.top-bar -->
</header><!-- /.app-header -->
