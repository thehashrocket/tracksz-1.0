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

                    if (strpos($page, 'order-edit') !== false) {
                        $page = 'order-edit';
                    }

                    $orders_menu = [
                        'order-browse', 'order-add', 'order-edit', 'order-batch-move', 'order-confirm_status', 'order-confirmation-file', 'order-export-order',
                        'order-shipping', 'order-order-settings', 'order-postage-settings', 'order-label-settings', 'order-add_update_postage_setting',
                        'order-order_update_settings', 'order-add_update_label_setting', 'order-filter_order', 'order-update-batchmove'
                    ];
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $orders_menu)) echo ' has-active'; ?>"><a href="/order/browse" class="menu-link menu-item" title="<?= _('Active Store Orders') ?>"><span class="menu-icon fa fa-dollar-sign"></span> <span class="menu-text"><?= _('Orders') ?></span></a>
                        <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'order-browse' || $page == 'order-filter_order'  || $page == 'order-add' || $page == 'order-edit') echo ' has-active'; ?>">
                                <a href="/order/browse" title="<?= _('View, Add, Edit, Delete Orders') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'order-batch-move' || $page == 'order-update-batchmove') echo ' has-active'; ?>">
                                <a href="/order/batch-move" title="<?= _('Orders Batch Move') ?>" class="menu-link"><?= _('Batch Move') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'order-confirmation-file' || $page == 'order-confirm_status') echo ' has-active'; ?>">
                                <a href="/order/confirmation-file" title="<?= _('Orders Confirmation File') ?>" class="menu-link"><?= _('Confirmation File') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'order-export-order') echo ' has-active'; ?>">
                                <a href="/order/export-order" title="<?= _('Orders Export') ?>" class="menu-link"><?= _('Export Order') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'order-shipping') echo ' has-active'; ?>">
                                <a href="/order/shipping" title="<?= _('Orders Shipping') ?>" class="menu-link"><?= _('Shipping') ?></a>
                            </li>
                            <!-- .menu-item -->
                            <?php
                            // determine if sub-menu has active page - set up array for in_array
                            $setting_menu = ['order-add_update_label_setting', 'order-order-settings', 'order-postage-settings', 'order-label-settings', 'order-add_update_postage_setting', 'order-order_update_settings'];
                            ?>
                            <li class="menu-item has-child<?php if (in_array($page, $setting_menu)) {
                                                                echo ' has-active';
                                                            }
                                                            ?>">
                                <a href="/order/order-settings" class="menu-link" title="<?= _('Active Store Order') ?>"><span class="menu-icon fa fa-cog" title="<?= _('Active Store Categories') ?>"></span> <span class="menu-text"><?= _('Settings') ?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if ($page == 'order-order-settings' || $page == 'order-order_update_settings') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/order/order-settings" title="<?= _('Order Settings') ?>" class="menu-link"><?= _('Order Settings') ?></a>
                                    </li>
                                    <li class="menu-item<?php if ($page == 'order-postage-settings'  || $page == 'order-add_update_postage_setting') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/order/postage-settings" title="<?= _('Postage Settings') ?>" class="menu-link"><?= _('Postage Settings') ?></a>
                                    </li>
                                    <li class="menu-item<?php if ($page == 'order-label-settings' || $page == 'order-add_update_label_setting') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/order/label-settings" title="<?= _('Label Settings') ?>" class="menu-link"><?= _('Label Settings') ?></a>
                                    </li>
                                </ul><!-- /child menu -->
                            </li><!-- /.menu-item -->
                            <!-- .menu-item -->
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
                    $inventory_menu = ['category-edit', 'product-add', 'product-edit', 'product-view', 'product-defaults', 'product-categories', 'inventory-add', 'product-place_market', 'product-upload', 'inventory-browse', 'inventory-defaults', 'inventory-categories', 'inventory-import', 'inventory-export', 'inventory-update', 'category-browse', 'category-add', 'inventory-condition-price', 'inventory-inventory-settings', 'inventory-advanced-settings', 'inventory-re-price', 'inventory-update_settings', 'product-change_marketplace','category-parent_category','category-add_parent_category','category-edit_parent_category','category-update_parent_category','category-update'];
                    if (strpos($page, 'product-edit') !== false) {
                        $page = 'product-edit';
                    }
                    if (strpos($page, 'category-edit_parent_category') !== false) {
                        $page = 'category-edit_parent_category';
                    }
                    if (strpos($page, 'category-edit-') !== false) {
                        $page = 'category-edit';
                    }
                    if (preg_match('/\category-edit_parent_category\b/', $page)) {
                         $page = 'category-edit_parent_category';
                    }
                                  
                    if (strpos($page, 'inventory-update_settings') !== false) {
                        $page = 'inventory-update_settings';
                    }                   
                   
                    ?>
                    <li class="menu-item has-child<?php if (in_array($page, $inventory_menu)) {
                                                        echo ' has-active';
                                                    } ?>"><a href="/inventory/browse" class="menu-link" title="<?= _('Active Store Inventory') ?>"><span class="menu-icon fas fa-dolly-flatbed" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Inventory') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'inventory-browse' || $page == 'product-add' || $page == 'product-edit' || $page == 'product-change_marketplace') {
                                                    echo ' has-active';
                                                } ?>">
                                <a href="/inventory/browse" title="<?= _('View, Add, Edit, Delete Inventory') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-condition-price') {
                                                    echo ' has-active';
                                                } ?>">
                                <a href="/inventory/condition-price" title="<?= _('View, Add, Edit, Delete Condition & Price') ?>" class="menu-link"><?= _('Condition & Price') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-import') {
                                                    echo ' has-active';
                                                } ?>"><a href="/inventory/import" title="<?= _('Inventory File Import') ?>" class="menu-link"><?= _('Import Items') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-export') {
                                                    echo ' has-active';
                                                } ?>"><a href="/inventory/export" title="<?= _('Inventory File Export') ?>" class="menu-link"><?= _('Export Items') ?></a>
                            </li>
                            <li class="menu-item<?php if ($page == 'inventory-re-price') {
                                                    echo ' has-active';
                                                } ?>"><a href="/inventory/re-price" title="<?= _('Inventory Re-Price') ?>" class="menu-link"><?= _('Re-Price') ?></a>
                            </li>
                            <!-- .menu-item -->
                            <?php
                            // determine if sub-menu has active page - set up array for in_array
                            $category_menu = ['category-browse', 'category-add', 'category-edit','category-parent_category','category-update','category-add_parent_category','category-edit_parent_category','category-update_parent_category'];
                            
                            ?>
                            <li class="menu-item has-child<?php if (in_array($page, $category_menu)) {
                                                                echo ' has-active';
                                                            }
                                                            ?>">
                                <a href="/category/parent_category" class="menu-link" title="<?= _('Active Store Cateogry') ?>"><span class="menu-icon fa fa-database" title="<?= _('Active Store Categories') ?>"></span> <span class="menu-text"><?= _('Category') ?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if ($page == 'category-parent_category'  || $page == 'category-edit_parent_category' || $page == 'category-add_parent_category' || $page == 'category-update_parent_category') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/category/parent_category" title="<?= _('View, Add, Edit, Delete Cateogry') ?>" class="menu-link"><?= _('Parent Cateogry') ?></a>
                                    </li>
                                    <li class="menu-item<?php if ($page == 'category-browse'  || $page == 'category-add' || $page == 'category-edit' || $page == 'category-update') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/category/browse" title="<?= _('View, Add, Edit, Delete Cateogry') ?>" class="menu-link"><?= _('Sub Category') ?></a>
                                    </li>

                                   

                                </ul><!-- /child menu -->
                            </li><!-- /.menu-item -->
                            <!-- .menu-item -->
                            <?php
                            // determine if sub-menu has active page - set up array for in_array
                            $setting_menu = ['inventory-inventory-settings', 'inventory-advanced-settings', 'inventory-update_settings'];
                            ?>
                            <li class="menu-item has-child<?php if (in_array($page, $setting_menu)) {
                                                                echo ' has-active';
                                                            }
                                                            ?>">
                                <a href="/inventory/inventory-settings" class="menu-link" title="<?= _('Active Store Cateogry') ?>"><span class="menu-icon fa fa-cog" title="<?= _('Active Store Categories') ?>"></span> <span class="menu-text"><?= _('Settings') ?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if ($page == 'inventory-inventory-settings' || $page == 'inventory-update_settings') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/inventory/inventory-settings" title="<?= _('Inventory Settings') ?>" class="menu-link"><?= _('Inventory Settings') ?></a>
                                    </li>
                                    <li class="menu-item<?php if ($page == 'inventory-advanced-settings') {
                                                            echo ' has-active';
                                                        }
                                                        ?>">
                                        <a href="/inventory/advanced-settings" title="<?= _('Advanced Settings') ?>" class="menu-link"><?= _('Advanced Settings') ?></a>
                                    </li>
                                </ul><!-- /child menu -->
                            </li><!-- /.menu-item -->
                            <!-- .menu-item -->
                            <?php
                            // determine if sub-menu has active page - set up array for in_array
                            $listing_service_menu = ['attribute-add', 'attribute-page', 'recurring-add', 'recurring-page', 'download-add', 'download-page', 'customergroup-add', 'customergroup-page', 'productdiscount-add', 'productdiscount-page', 'productspecial-add', 'productspecial-page'];
                            ?>
                            <li class="d-none menu-item has-child<?php if (in_array($page, $listing_service_menu)) {
                                                                        echo ' has-active';
                                                                    }
                                                                    ?>"><a href="#" class="menu-link" title="<?= _('Tracksz Listing Service') ?>"><span class="menu-icon fas  fa-shopping-basket" title="<?= _('Tracksz Listing Service') ?>"></span> <span class="menu-text"><?= _('Tracksz Listing') ?></span></a> <!-- child menu -->
                                <?php
                                // determine if sub-menu has active page - set up array for in_array
                                $attribute_menu = ['attribute-add', 'attribute-page', 'download-add', 'download-page'];
                                ?>
                                <ul class="menu">
                                    <li class="menu-item has-child<?php if (in_array($page, $attribute_menu)) {
                                                                        echo ' has-active';
                                                                    }
                                                                    ?>">
                                        <a href="#" class="menu-link" title="<?= _('Attributes List') ?>"><span class="menu-icon fas fa-paperclip" title="<?= _('Example Data') ?>"></span> <span class="menu-text"><?= _('Attributes') ?></span></a> <!-- child menu -->
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
                                        <a href="#" class="menu-link" title="<?= _('Recurring List') ?>"><span class="menu-icon fa fa-recycle" title="<?= _('Download Data') ?>"></span> <span class="menu-text"><?= _('Recurring') ?></span></a> <!-- child menu -->
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

                                    <?php
                                    // determine if sub-menu has active page - set up array for in_array
                                    $customergroup_menu = ['customergroup-add', 'customergroup-page'];
                                    ?>
                                    <li class="menu-item has-child<?php if (in_array($page, $customergroup_menu)) {
                                                                        echo ' has-active';
                                                                    }
                                                                    ?>">
                                        <a href="#" class="menu-link" title="<?= _('Customer Group List') ?>"><span class="menu-icon fa fa-user-friends" title="<?= _('Download Data') ?>"></span> <span class="menu-text"><?= _('Customer Group') ?></span></a> <!-- child menu -->
                                        <ul class="menu">
                                            <!-- Notice the use of substr because of pagination urls -->
                                            <li class="menu-item<?php if ($page == 'customergroup-page') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/customergroup/page" title="<?= _('View Customer Group Data') ?>" class="menu-link"><?= _('List') ?></a>
                                            </li>
                                            <li class="menu-item<?php if ($page == 'customergroup-add') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/customergroup/add" title="<?= _('Add Customer Group Data') ?>" class="menu-link"><?= _('Add') ?></a>
                                            </li>
                                        </ul><!-- /child menu -->
                                    </li><!-- /.menu-item -->
                                    <?php
                                    // determine if sub-menu has active page - set up array for in_array
                                    $productdiscount_menu = ['productdiscount-add', 'productdiscount-page'];
                                    ?>
                                    <li class="menu-item has-child<?php if (in_array($page, $productdiscount_menu)) {
                                                                        echo ' has-active';
                                                                    }
                                                                    ?>">
                                        <a href="#" class="menu-link" title="<?= _('Product Discount List') ?>"><span class="menu-icon fa fa-tags" title="<?= _('Download Data') ?>"></span> <span class="menu-text"><?= _('Product Discount') ?></span></a> <!-- child menu -->
                                        <ul class="menu">
                                            <!-- Notice the use of substr because of pagination urls -->
                                            <li class="menu-item<?php if ($page == 'productdiscount-page') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/productdiscount/page" title="<?= _('View Product Discount Data') ?>" class="menu-link"><?= _('List') ?></a>
                                            </li>
                                            <li class="menu-item<?php if ($page == 'productdiscount-add') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/productdiscount/add" title="<?= _('Add Product Discount Data') ?>" class="menu-link"><?= _('Add') ?></a>
                                            </li>
                                        </ul><!-- /child menu -->
                                    </li><!-- /.menu-item -->

                                    <?php
                                    // determine if sub-menu has active page - set up array for in_array
                                    $productspecial_menu = ['productspecial-add', 'productspecial-page'];
                                    ?>
                                    <li class="menu-item has-child<?php if (in_array($page, $productspecial_menu)) {
                                                                        echo ' has-active';
                                                                    }
                                                                    ?>">
                                        <a href="#" class="menu-link" title="<?= _('Product Special List') ?>"><span class="menu-icon fa fa-tags" title="<?= _('Special Product') ?>"></span> <span class="menu-text"><?= _('Product Special') ?></span></a> <!-- child menu -->
                                        <ul class="menu">
                                            <!-- Notice the use of substr because of pagination urls -->
                                            <li class="menu-item<?php if ($page == 'productspecial-page') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/productspecial/page" title="<?= _('View Product Special Data') ?>" class="menu-link"><?= _('List') ?></a>
                                            </li>
                                            <li class="menu-item<?php if ($page == 'productspecial-add') {
                                                                    echo ' has-active';
                                                                }
                                                                ?>">
                                                <a href="/productspecial/add" title="<?= _('Add Product Special Data') ?>" class="menu-link"><?= _('Add') ?></a>
                                            </li>
                                        </ul><!-- /child menu -->
                                    </li><!-- /.menu-item -->
                                </ul>
                        </ul><!-- /child menu -->
                    </li>
                    </li><!-- /.menu-item -->

                    <!-- .menu-item -->
                    <?php
                    if (strpos($page, 'marketplace-edit') !== false) {
                        $page = 'marketplace-edit';
                    }
                    if (strpos($page, 'marketplace-update') !== false) {
                        $page = 'marketplace-update';
                    }
                    if (strpos($page, 'marketplace-dashboard-step2') !== false) {
                        $page = 'marketplace-dashboard-step2';
                    }
                    if (strpos($page, 'marketplace-dashboard-step3') !== false) {
                        $page = 'marketplace-dashboard-step3';
                    }
                    // determine if sub-menu has active page - set up array for in_array
                    $marketplace_menu = ['marketplace-dashboard', 'marketplace-dashboard-step2', 'marketplace-dashboard-step3', 'marketplace-edit', 'marketplace-update', 'marketplace-list', 'marketplace-edit']; ?>
                    <li class="menu-item has-child<?php if (in_array($page, $marketplace_menu)) {
                                                        echo ' has-active';
                                                    }
                                                    ?>">
                        <a href="/marketplace/list" class="menu-link" title="<?= _('Martketplace Module') ?>"><span class="menu-icon fa fa-store" title="<?= _('Active Store Inventory') ?>"></span> <span class="menu-text"><?= _('Marketplace') ?></span></a> <!-- child menu -->
                        <ul class="menu">
                            <li class="menu-item<?php if ($page == 'marketplace-list' || $page == 'marketplace-dashboard' || $page == 'marketplace-edit' || $page == 'marketplace-update' || $page == 'marketplace-dashboard-step2' || $page == 'marketplace-dashboard-step3') {
                                                    echo ' has-active';
                                                }
                                                ?>">
                                <a href="/marketplace/list" title="<?= _('Marketplace List') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                        </ul><!-- /child menu -->
                    </li><!-- /.menu-item -->
                    <!-- .menu-item -->
                    <li class="menu-item has-child<?php if (substr($page, 0, 9) == 'account-s' || substr($page, 0, 14) == 'account-stripe') echo ' has-active'; ?>">
                        <a href="/account/stores" title="<?= _('Select Store to Work On') ?>" class="menu-link<?php if ($page == 'category-view') echo ' has-active'; ?>"><span class="menu-icon fa fa-shopping-cart"></span><span class="menu-text"><?= _('Member Stores') ?></span></a>
                        <ul class="menu">
                            <li class="menu-item<?php if (substr($page, 0, 13) == 'account-store') echo ' has-active'; ?>">
                                <a href="/account/stores" title="<?= _('View, Add, Edit, Delete Inventory') ?>" class="menu-link"><?= _('Browse') ?></a>
                            </li>
                            <li class="menu-item has-child<?php if (substr($page, 0, 12) == 'account-ship') echo ' has-active'; ?>">
                                <a href="/account/shipping-methods" class="menu-link" title="<?= _('Active Store Shipping') ?>"><small><span class="menu-icon fas fa-ship"></span></small> <span class="menu-text"><?= _('Shipping') ?></span></a> <!-- child menu -->
                                <ul class="menu">
                                    <li class="menu-item<?php if (substr($page, 0, 20) == 'account-shipping-met') echo ' has-active'; ?>">
                                        <a href="/account/shipping-methods" title="<?= _('Browse Shipping Methods') ?>" class="menu-link"><?= _('Shipping Methods') ?></a>
                                    </li>
                                    <li class="menu-item<?php if (substr($page, 0, 20) == 'account-shipping-zon') echo ' has-active'; ?>">
                                        <a href="/account/shipping-zones" title="<?= _('Browse Shipping Zones') ?>" class="menu-link"><?= _('Shipping Zones') ?></a>
                                    </li>
                                    <li class="menu-item<?php if (substr($page, 0, 20) == 'account-shipping-ass') echo ' has-active'; ?>">
                                        <a href="/account/shipping-assign" title="<?= _('Assign Shipping Zones') ?>" class="menu-link"><?= _('Assign Shipping Zones') ?></a>
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
