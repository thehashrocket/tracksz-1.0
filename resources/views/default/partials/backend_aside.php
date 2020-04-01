<?php
    // get current page for active menu link
    $page = \Delight\Cookie\Session::get('current_page');
    $page = str_replace('/', '-', ltrim($page, '/'));
    
    // trim page for pages with pagination urls, for example
    // page = example-page-2-30  (2 = page number, 30 = rows per page)
    // clean to example-page
    if(substr($page,0,12) == 'example-page') {
        $page = substr($page,0,12);
    }
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
                    <li class="menu-item<?php if($page == 'account-panel') echo ' has-active';?>">
                        <a href="/account/panel" class="menu-link" title="<?=_('Tracksz Account Dashboard')?>"><span class="menu-icon fas fa-home"></span> <span class="menu-text"><?=('Dashboard')?></span></a>
                    </li><!-- /.menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $inventory_menu = ['inventory-browse','inventory-add', 'inventory-defaults','inventory-categories'];
                    ?>
                    <li class="menu-item has-child<?php if(in_array($page, $inventory_menu)) echo ' has-active';?>">
                        <a href="#" class="menu-link" title="<?=_('Active Store Inventory')?>"><span class="menu-icon fas fa-list" title="<?=_('Active Store Inventory')?>"></span> <span class="menu-text"><?=_('Inventory')?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if($page == 'inventory-browse') echo ' has-active';?>">
                                <a href="/inventory/browse" title="<?=_('View, Add, Edit, Delete Inventory')?>" class="menu-link"><?=_('Browse')?></a>
                            </li>
                            <li class="menu-item<?php if($page == 'inventory-defaults') echo ' has-active';?>">
                                <a href="/inventory/defaults" title="<?=_('Active Store Inventory Default Settings')?>" class="menu-link"><?=_('Defaults')?></a>
                            </li>

                            <?php
                                // determine if sub-menu has active page - set up array for in_array
                                $category_menu = ['category-view','category-defaults','category-categories','category-add'];
                            ?>
                            <li class="menu-item has-child<?php if(in_array($page, $category_menu)) echo ' has-active';?>">
                                <a href="#" class="menu-link" title="<?=_('Active Store Product')?>"><small><span class="menu-icon fas fa-list" title="<?=_('Active Store Inventory')?>"></span></small> <span class="menu-text"><?=_('Category')?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <!-- <li class="menu-item<?php if($page == 'category-view') echo ' has-active';?>">
                                <a href="/product/view" title="<?=_('View, Add, Edit, Delete product')?>" class="menu-link"><?=_('View')?></a>
                            </li> -->
                                    <li class="menu-item<?php if($page == 'category-add') echo ' has-active';?>">
                                        <a href="/category/add" title="<?=_('View, Add, Edit, Delete product')?>" class="menu-link"><?=_('Add')?></a>
                                    </li>
                                    <li class="menu-item<?php if($page == 'category-view') echo ' has-active';?>">
                                        <a href="/category/view" title="<?=_('View, Add, Edit, Delete product')?>" class="menu-link"><?=_('View')?></a>
                                    </li>
                                    <!-- <li class="menu-item<?php if($page == 'category-defaults') echo ' has-active';?>">
                                <a href="/category/defaults" title="<?=_('Active Store Inventory Default Settings')?>" class="menu-link"><?=_('Defaults')?></a>
                            </li> -->
                                </ul><!-- /child menu -->
                            </li><!-- /.menu-item -->
                            
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    
                     <!-- .menu-item -->
                    <?php
                        // determine if sub-menu has active page - set up array for in_array
                        $marketplace_menu = ['marketplace-browse', 'marketplace-add', 'marketplace-add-step2'];
                    ?>
                    <li class="menu-item<?php if(in_array($page, $marketplace_menu)) echo ' has-active';?>">
                        <a href="/marketplace/browse" class="menu-link" title="<?=_('Martketplace Module')?>"><span class="menu-icon fa fa-store" title="<?=_('Active Store Inventory')?>"></span> <span class="menu-text"><?=_('Marketplaces')?></span></a> <!-- child menu -->
                    </li><!-- /.menu-item -->
    
    
                    <?php
                        // determine if sub-menu has active page - set up array for in_array
                        $store_menu = ['account-stores','account-shipping-methods', 'account-shipping-methods-create'];
                        $shipping_menu = ['account-shipping-methods', 'account-shipping-methods-add']
                    ?>
                    <!-- .menu-item -->
                    <li class="menu-item has-child<?php if(in_array($page, $store_menu) || substr($page,0,14) == 'account-stripe') echo ' has-active';?>">
                        <a href="/account/stores" title="<?=_('Select Store to Work On')?>" class="menu-link<?php if($page == 'category-view') echo ' has-active';?>"><span class="menu-icon fa fa-shopping-cart" title="<?=_('Tracksz Member Account')?>"></span><span class="menu-text"><?=_('Member Stores')?></span></a>
                        <ul class="menu">
                            <li class="menu-item<?php if($page == 'account-stores') echo ' has-active';?>">
                                <a href="/account/stores" title="<?=_('View, Add, Edit, Delete Inventory')?>" class="menu-link"><?=_('Browse')?></a>
                            </li>
                            <li class="menu-item has-child<?php if(in_array($page, $shipping_menu)) echo ' has-active';?>">
                                <a href="#" class="menu-link" title="<?=_('Active Store Shipping')?>"><small><span class="menu-icon fas fa-ship"></span></small> <span class="menu-text"><?=_('Shipping')?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if(substr($page,0,4) == 'account-shipping-methods') echo ' has-active';?>">
                                        <a href="/account/shipping-methods" title="<?=_('Browse Shipping Methods')?>" class="menu-link"><?=_('Shipping Methods')?></a>
                                    </li>
                                    <li class="menu-item<?php if(substr($page,0,21) == 'account-shipping-zone') echo ' has-active';?>">
                                        <a href="/category/view" title="<?=_('View, Add, Edit, Delete product')?>" class="menu-link"><?=_('View')?></a>
                                    </li>
                                    <!-- <li class="menu-item<?php if($page == 'category-defaults') echo ' has-active';?>">
                                <a href="/category/defaults" title="<?=_('Active Store Inventory Default Settings')?>" class="menu-link"><?=_('Defaults')?></a>
                            </li> -->
                                </ul><!-- /child menu -->
                            </li>
                        </ul>
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    
                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $member_menu = ['account-profile','account-payment','account-invoices','account-activites'];
                    ?>
                    <li class="menu-item has-child<?php if(in_array($page, $member_menu)) echo ' has-active';?>">
                        <a href="/account/profile" class="menu-link" title="<?=_('Tracksz Member Account')?>"><span class="menu-icon oi oi-person" title="<?=_('Tracksz Member Account')?>"></span> <span class="menu-text"><?=_('Member Account')?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if($page == 'account-profile') echo ' has-active';?>">
                                <a href="/account/profile" title="<?=_('Tracksz Member Profile')?>" class="menu-link"><?=_('Profile')?></a>
                            </li>
                            <li class="menu-item<?php if($page == 'account-payment') echo ' has-active';?>">
                                <a href="/account/payment" title="<?=_('Tracksz Member Payment')?>" class="menu-link"><?=_('Payment')?></a>
                            </li>
                            <li class="menu-item<?php if($page == 'account-invoices') echo ' has-active';?>">
                                <a href="user-billing-settings.html" title="<?=_('Tracksz Member Invoices')?>" class="menu-link"><?=_('Invoices')?></a>
                            </li>
                            <li class="menu-item<?php if($page == 'account-activities') echo ' has-active';?>">
                                <a href="user-activities.html" title="<?=_('Tracksz Member Activities')?>" class="menu-link"><?=_('Activities')?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    
                    <!-- .menu-header -->
                    <li class="menu-header">EXAMPLES </li><!-- /.menu-header -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $example_menu = ['example-page', 'example-find'];
                    ?>
                    <li class="menu-item has-child<?php if(in_array($page, $example_menu)) echo ' has-active';?>">
                        <a href="#" class="menu-link" title="<?=_('Example Data List')?>"><span class="menu-icon fa fa-bath" title="<?=_('Example Data')?>"></span> <span class="menu-text"><?=_('Example')?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <!-- Notice the use of substr because of pagination urls -->
                            <li class="menu-item<?php if($page == 'example-page') echo ' has-active';?>">
                                <a href="/example/page" title="<?=_('View Example Data')?>" class="menu-link"><?=_('List')?></a>
                            </li>
                            <li class="menu-item<?php if($page == 'example-find') echo ' has-active';?>">
                                <a href="/example/find" title="<?=_('Find Example Data')?>" class="menu-link"><?=_('Find Example')?></a>
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
