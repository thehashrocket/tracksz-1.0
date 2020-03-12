<?php
    // get current page for active menu link
    $page = \Delight\Cookie\Session::get('current_page');
    $page = str_replace('/', '-', ltrim($page, '/'));
?>
<!-- .app-aside -->
<aside class="app-aside app-aside-expand-md app-aside-light">
    <!-- .aside-content -->
    <div class="aside-content">
        <!-- .aside-menu -->
        <div class="aside-menu overflow-hidden">
            <!-- .stacked-menu -->
            <nav id="stacked-menu" class="stacked-menu">
                <!-- .menu -->
                <ul class="menu">
                    <!-- .menu-item -->
                    <li class="menu-item <?php if($page == 'account') echo 'has-active';?>">
                        <a href="/account/panel" class="menu-link" title="<?=_('Tracksz Account Dashboard')?>"><span class="menu-icon fas fa-home"></span> <span class="menu-text"><?=('Dashboard')?></span></a>
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <?php
                        function startsWith($haystack, $needle)
                        {
                            $needleLen = strlen($needle);
                            return substr($haystack, 0, $needleLen) === $needle;
                        }
                        // determine if sub-menu has active page - set up array for in_array
                        $member_menu = ['account-profile','account-payment','account-invoices','account-activites'];
                        $store_menu = ['account-stores', 'account-shipping-methods', 'account-shipping-zones']
                    ?>
                    <li class="menu-item has-child <?php if(in_array($page, $member_menu)) echo 'has-active';?>">
                        <a href="/account/profile" class="menu-link" title="<?=_('Tracksz Member Account')?>"><span class="menu-icon oi oi-person" title="<?=_('Tracksz Member Account')?>"></span> <span class="menu-text"><?=_('Member Account')?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item <?php if($page == 'account-profile') echo 'has-active';?>">
                                <a href="/account/profile" title="<?=_('Tracksz Member Profile')?>" class="menu-link"><?=_('Profile')?></a>
                            </li>
                            <li class="menu-item <?php if($page == 'account-payment') echo 'has-active';?>">
                                <a href="/account/payment" title="<?=_('Tracksz Member Payment')?>" class="menu-link"><?=_('Payment')?></a>
                            </li>
                            <li class="menu-item <?php if($page == 'account-invoices') echo 'has-active';?>">
                                <a href="user-billing-settings.html" title="<?=_('Tracksz Member Invoices')?>" class="menu-link"><?=_('Invoices')?></a>
                            </li>
                            <li class="menu-item <?php if($page == 'account-activities') echo 'has-active';?>">
                                <a href="user-activities.html" title="<?=_('Tracksz Member Activities')?>" class="menu-link"><?=_('Activities')?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <li class="menu-item has-child <?php if(startsWith($page, 'account-store')) echo 'has-active';?>" style="">
                        <a href="/account/stores" class="menu-link" title="<?=_('View Stores')?>"><span class="menu-icon oi oi-cart" title="<?=_('View Stores')?>"></span> <span class="menu-text"><?=_('Stores')?></span></a>
                        <ul class="menu">
                            <li class="menu-item <?php if(startsWith($page, 'account-store')) echo 'has-active';?>">
                                <a href="/account/stores" title="View Stores" class="menu-link" tabindex="-1"><?=_('View Stores')?></a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item has-child <?php if(startsWith($page, 'account-shipping') || startsWith($page, 'account-shipping')) echo 'has-active';?>" style="">
                        <a href="/account/shipping-methods" class="menu-link" title="<?=_('Shipping')?>"><span class="menu-icon oi oi-globe" title="<?=_('Shipping')?>"></span> <span class="menu-text"><?=_('Shipping')?></span></a>
                        <ul class="menu">
                            <li class="menu-item <?php if(startsWith($page, 'account-shipping-methods')) echo 'has-active';?>">
                                <a href="/account/shipping-methods" title="<?=_('View Shipping Methods')?>" class="menu-link" tabindex="-1"><?=_('Shipping Methods')?></a>
                            </li>
                            <li class="menu-item <?php if(startsWith($page, 'account-shipping-zones') && !startsWith($page, 'account-shipping-zones-region')) echo 'has-active';?>">
                                <a href="/account/shipping-zones" title="<?=_('View Shipping Zones')?>" class="menu-link" tabindex="-1"><?=_('Shipping Zones')?></a>
                            </li>
                            <li class="menu-item <?php if(startsWith($page, 'account-shipping-zones-region')) echo 'has-active';?>">
                                <a href="/account/shipping-zones/region" title="<?=_('Assign Shipping Zones')?>" class="menu-link" tabindex="-1"><?=_('Assign Shipping Zones')?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $inventory_menu = ['inventory-view','inventory-defaults','inventory-categories'];
                    ?>
                    <li class="menu-item has-child <?php if(in_array($page, $inventory_menu)) echo 'has-active';?>">
                        <a href="#" class="menu-link" title="<?=_('Active Store Inventory')?>"><span class="menu-icon fas fa-list" title="<?=_('Active Store Inventory')?>"></span> <span class="menu-text"><?=_('Inventory')?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item <?php if($page == 'inventory-view') echo 'has-active';?>">
                                <a href="/inventory/view" title="<?=_('View, Add, Edit, Delete Inventory')?>" class="menu-link"><?=_('View')?></a>
                            </li>
                            <li class="menu-item <?php if($page == 'inventory-defaults') echo 'has-active';?>">
                                <a href="/inventory/defaults" title="<?=_('Active Store Inventory Default Settings')?>" class="menu-link"><?=_('Defaults')?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                </ul><!-- /.menu -->
            </nav><!-- /.stacked-menu -->
        </div><!-- /.aside-menu -->
        <!-- Skin changer -->
        <footer class="aside-footer border-top p-2">
            <?php if(\Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                     \Delight\Cookie\Cookie::get('tracksz_active_store') > 0): ?>
                <a class="btn btn-light btn-block text-primary" href="/account/stores" data-toggle="tooltip" data-placement="bottom" title="<?=_('Current Active Store: ')?><?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?><?=_('. Click to Change Active Store')?>"><i class="fas fa-shopping-cart ml-1"></i> <span class="d-compact-menu-none"><?=urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name'))?></span></a>
            <?php else: ?>
                <a class="btn btn-light btn-block text-primary" href="/account/stores" data-toggle="tooltip" data-placement="bottom" title="<?=_('No Active Store Selected. Click to Selet Active Store')?>"><i class="fas fa-shopping-cart ml-1"></i> <span class="d-compact-menu-none"><?=_('No Acive Store')?></span></a>
            <?php endif; ?>
        </footer><!-- /Skin changer -->
    </div><!-- /.aside-content -->
</aside><!-- /.app-aside -->