<?php
$title_meta = 'Tracksz.com MultiMarket Inventory and Order Management Service';
$description_meta = 'Tracksz.com allows user to manage inventory and orders in one location while keeping all Stores, Marketplaces and Listing sites completely in sync';
?>
<?=$this->layout('layouts/frontend', ['title' => $title_meta, 'description' => $description_meta])?>

<?=$this->start('page_content')?>
    <!-- .wrapper -->
    <div class="wrapper">
        <!-- .empty-state -->
        <div class="empty-state">
            <!-- .empty-state-container -->
            <div class="empty-state-container">
                <div class="state-figure">
                    <img class="img-fluid" src="assets/images/illustration/img-2.svg" alt="" style="max-width: 320px">
                </div>
                <h3 class="state-header"> Whoops. We've had a hiccup.  Please try again.</h3>
                <p class="state-description lead text-muted"> Please select a menu option from the menu above. </p>
            </div><!-- /.empty-state-container -->
        </div><!-- /.empty-state -->
    </div><!-- /.wrapper -->
<?=$this->stop()?>