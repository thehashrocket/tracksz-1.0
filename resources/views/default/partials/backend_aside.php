<?php
// get current page for active menu link
$page = \Delight\Cookie\Session::get('current_page');
$page = str_replace('/', '-', ltrim($page, '/'));

// trim page for pages with pagination urls, for example
// page = example-page-2-30  (2 = page number, 30 = rows per page)
// clean to example-page
if (substr($page, 0, 12) == 'example-page') {
    $page = substr($page, 0, 12);
}
?>
<!-- .app-aside -->
<aside class="app-aside app-aside-expand-md app-aside-light">
    <!-- .aside-content -->
    <div class="aside-content">
        <!-- .aside-menu -->
        <div class="aside-menu overflow-hidden">
            <!-- .stacked-menu -->
            <nav id="stacked-menu" class="stacked-menu stacked-menu-has-collapsible">
                <!-- .menu -->
                <ul class="menu">
                    <!-- .menu-item -->
                    <li class="menu-item<?php if ($page == 'account-panel') echo ' has-active'; ?>">
                        <a href="/account/panel" class="menu-link" title="<?= _('Tracksz Account Dashboard') ?>"><span class="menu-icon fas fa-home"></span> <span class="menu-text"><?= ('Dashboard') ?></span></a>
                    </li><!-- /.menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $orders_menu = ['order-browse'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $orders_menu)) echo ' has-active'; ?>">
                        <a href="/order/browse" class="menu-link" title="<?= _('Active Store Orders') ?>"><span class="menu-icon fa fa-dollar-sign"></span> <span class="menu-text"><?= _('Orders') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'order-browse') echo ' has-active'; ?>">
                                <a href="/order/browse" title="<?= _('View, Add, Edit, Delete Orders') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $reports_menu = ['report-sales'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $reports_menu)) echo ' has-active'; ?>">
                        <a href="/report/sales" class="menu-link" title="<?= _('Active Store Reports') ?>"><span class="menu-icon fa fa-chart-area"></span> <span class="menu-text"><?= _('Reports') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'report-sales') echo ' has-active'; ?>">
                                <a href="/report/sales" title="<?= _('View, Add, Edit, Delete Orders') ?>" class="menu-link"><?= _('Sales') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $inventory_menu = ['inventory-view', 'inventory-defaults', 'inventory-categories', 'inventory-upload', 'inventory-update'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $inventory_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="/inventory/upload" class="menu-link" title="<?= _('Active Store Inventory') ?>"><span class="menu-icon fas fa-list" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Inventory') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'inventory-upload') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/inventory/upload" title="<?= _('Inventory File Upload') ?>" class="menu-link"><?= _('Upload') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-update') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/inventory/update" title="<?= _('Inventory Update') ?>" class="menu-link"><?= _('Update') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-view') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/inventory/view" title="<?= _('View, Add, Edit, Delete Inventory') ?>" class="menu-link"><?= _('View') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->

                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $marketplace_menu = ['marketplace-dashboard', 'marketplace-list']; ?>
                    <li class="menu-item has-child<?php if (in_array($page, $marketplace_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="/marketplace/dashboard" class="menu-link" title="<?= _('Martketplace Module') ?>"><span class="menu-icon fa fa-store" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Marketplace') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'marketplace-dashboard') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/marketplace/dashboard" title="<?= _('Add Marketplace') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'marketplace-list') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/marketplace/list" title="<?= _('Marketplace List') ?>" class="menu-link"><?= _('List') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $product_menu = ['product-view', 'product-defaults', 'product-categories', 'product-add', 'product-place_market', 'product-upload'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $product_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="/product/add" class="menu-link" title="<?= _('Active Store Product') ?>"><span class="menu-icon oi oi-list-rich" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Product') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'product-add' || $page == 'product-place_market') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/product/add" title="<?= _('View, Add, Edit, Delete Product') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'product-upload') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/product/upload" title="<?= _('FTP Product Upload') ?>" class="menu-link"><?= _('Upload') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'product-view') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/product/view" title="<?= _('View, Edit, Delete Product') ?>" class="menu-link"><?= _('View') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->


                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $category_menu = ['category-view', 'category-add'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $category_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="/category/add" class="menu-link" title="<?= _('Active Store Cateogry') ?>"><span class="menu-icon fas fa-list" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Category') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'category-add') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/category/add" title="<?= _('View, Add, Edit, Delete Cateogry') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'category-view') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/category/view" title="<?= _('View, Add, Edit, Delete Cateogry') ?>" class="menu-link"><?= _('View') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->


                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $store_menu = [
                        'account-stores', 'account-store', 'account-shipping-methods', 'account-shipping-methods-add',
                        'account-shipping-zones', 'account-shipping-zones-add'
                    ];
                    $shipping_menu = [
                        'account-shipping-methods', 'account-shipping-methods-add',
                        'account-shipping-zones', 'account-shipping-zones-add'
                    ]
                    ?>
                    <!-- .menu-item -->
                    <li class="menu-item has-child<?php if (in_array($page, $store_menu) || substr($page, 0, 14) == 'account-stripe') echo ' has-active'; ?>">
                        <a href="/account/stores" title="<?= _('Select Store to Work On') ?>" class="menu-link<?php if ($page == 'category-view') echo ' has-active'; ?>"><span class="menu-icon fa fa-shopping-cart"></span><span class="menu-text"><?= _('Member Stores') ?></span></a>
                        <ul class="menu">
                            <li class="menu-item<?php if (substr($page, 0, 13) == 'account-store') echo ' has-active'; ?>">
                                <a href="/account/stores" title="<?= _('View, Add, Edit, Delete Inventory') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                            <li class="menu-item has-child<?php if (in_array($page, $shipping_menu)) echo ' has-active'; ?>">
                                <a href="/account/shipping-methods" class="menu-link" title="<?= _('Active Store Shipping') ?>"><small><span class="menu-icon fas fa-ship"></span></small> <span class="menu-text"><?= _('Shipping') ?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if (substr($page, 0, 20) == 'account-shipping-met') echo ' has-active'; ?>">
                                        <a href="/account/shipping-methods" title="<?= _('Browse Shipping Methods') ?>" class="menu-link"><?= _('Shipping Methods') ?></a>
                                    </li>
                                    <li class="menu-item<?php if (substr($page, 0, 20) == 'account-shipping-zon') echo ' has-active'; ?>">
                                        <a href="/account/shipping-zones" title="<?= _('Browse Shipping Zones') ?>" class="menu-link"><?= _('Shipping Zones') ?></a>
                                    </li>
                                </ul><!-- /child menu -->
                            </li>
                        </ul>
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <!-- .menu-item -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $member_menu = ['account-profile', 'account-profile-edit', 'account-payment', 'account-invoices', 'account-activites'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $member_menu)) echo ' has-active'; ?>">
                        <a href="/account/profile" class="menu-link" title="<?= _('Tracksz Member Account') ?>"><span class="menu-icon oi oi-person"></span><span class="menu-text"><?= _('Member Account') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'account-profile') echo ' has-active'; ?>">
                                <a href="/account/profile" title="<?= _('Tracksz Member Profile') ?>" class="menu-link"><?= _('Profile') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'account-profile-edit') echo ' has-active'; ?>">
                                <a href="/account/profile/edit" title="<?= _('Edit Tracksz Member Profile') ?>" class="menu-link"><?= _('Edit Profile') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'account-payment') echo ' has-active'; ?>">
                                <a href="/account/payment" title="<?= _('Tracksz Member Payment Methods') ?>" class="menu-link"><?= _('Payment Methods') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'account-invoices') echo ' has-active'; ?>">
                                <a href="user-billing-settings.html" title="<?= _('Tracksz Member Invoices') ?>" class="menu-link"><?= _('Invoices') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'account-activities') echo ' has-active'; ?>">
                                <a href="user-activities.html" title="<?= _('Tracksz Member Activities') ?>" class="menu-link"><?= _('Activities') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-header -->
                    <li class="menu-header">Catalog </li><!-- /.menu-header -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $attribute_menu = ['attribute-add', 'attribute-page'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $attribute_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="#" class="menu-link" title="<?= _('Attributes List') ?>"><span class="menu-icon fas fa-list" title="<?= _('Example Data') ?>"></span> <span class="menu-text"><?= _('Attributes') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <!-- Notice the use of substr because of pagination urls -->
                            <li class="menu-item<?php if ($page == 'attribute-page') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/attribute/page" title="<?= _('View Attribute Data') ?>" class="menu-link"><?= _('List') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'attribute-add') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/attribute/add" title="<?= _('Add Attribute Data') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->

                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $download_menu = ['download-add', 'download-page'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $download_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="#" class="menu-link" title="<?= _('Downloads List') ?>"><span class="menu-icon fa fa-download" title="<?= _('Download Data') ?>"></span> <span class="menu-text"><?= _('Downloads') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <!-- Notice the use of substr because of pagination urls -->
                            <li class="menu-item<?php if ($page == 'download-page') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/download/page" title="<?= _('View Download Data') ?>" class="menu-link"><?= _('List') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'download-add') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/download/add" title="<?= _('Add Download Data') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->

                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $recurring_menu = ['recurring-add', 'recurring-page'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $recurring_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="#" class="menu-link" title="<?= _('Recurring List') ?>"><span class="menu-icon fa fa-download" title="<?= _('Download Data') ?>"></span> <span class="menu-text"><?= _('Recurring') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <!-- Notice the use of substr because of pagination urls -->
                            <li class="menu-item<?php if ($page == 'recurring-page') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/recurring/page" title="<?= _('View Recurring Data') ?>" class="menu-link"><?= _('List') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'recurring-add') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/recurring/add" title="<?= _('Add Recurring Data') ?>" class="menu-link"><?= _('Add') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-header -->
                    <li class="menu-header">EXAMPLES </li><!-- /.menu-header -->
                    <?php
                    // determine if sub-menu has active page - set up array for in_array
                    $example_menu = ['example-page', 'example-find'];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $example_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="#" class="menu-link" title="<?= _('Example Data List') ?>"><span class="menu-icon fas fa-list" title="<?= _('Example Data') ?>"></span> <span class="menu-text"><?= _('Example') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <!-- Notice the use of substr because of pagination urls -->
                            <li class="menu-item<?php if ($page == 'example-page') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/example/page" title="<?= _('View Example Data') ?>" class="menu-link"><?= _('List') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'example-find') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/example/find" title="<?= _('Find Example Data') ?>" class="menu-link"><?= _('Find Example') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                </ul><!-- /.menu -->
            </nav><!-- /.stacked-menu -->
        </div><!-- /.aside-menu -->
        <!-- Skin changer -->
        <footer class="aside-footer border-top p-2">
            <?php if (
                \Delight\Cookie\Cookie::exists('tracksz_active_store') &&
                \Delight\Cookie\Cookie::get('tracksz_active_store') > 0
            ) : ?>
                <a class="btn btn-light btn-block text-primary" href="/account/stores" data-toggle="tooltip" data-placement="bottom" title="<?= _('Current Active Store: ') ?><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?><?= _('. Click to Change Active Store') ?>"><i class="fas fa-shopping-cart ml-1"></i> <span class="d-compact-menu-none"><?= urldecode(\Delight\Cookie\Cookie::get('tracksz_active_name')) ?></span></a>
            <?php else : ?>
                <a class="btn btn-light btn-block text-primary" href="/account/stores" data-toggle="tooltip" data-placement="bottom" title="<?= _('No Active Store Selected. Click to Selet Active Store') ?>"><i class="fas fa-shopping-cart ml-1"></i> <span class="d-compact-menu-none"><?= _('No Acive Store') ?></span></a>
            <?php endif; ?>
        </footer><!-- /Skin changer -->
    </div><!-- /.aside-content -->
</aside><!-- /.app-aside -->
