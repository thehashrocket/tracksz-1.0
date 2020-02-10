<!-- .page-navs -->
<nav class="page-navs">
    <!-- .nav-scroller -->
    <div class="nav-scroller">
        <!-- .nav -->
        <div class="nav nav-center nav-tabs">
            <a class="nav-link<?php if($page == 'profile') echo ' active';?>" title="<?=_('Tracksz Member Profile')?>" href="/account/profile"><?=_('Profile')?></a> <a href="/account/payment" title="<?=_('Tracksz Member Payment')?>" class="nav-link<?php if($page == 'payment') echo ' active';?>"><?=_('Payment')?></a> <a href="/account/payment" title="<?=_('Tracksz Member Invoices')?>" class="nav-link<?php if($page == 'invoices') echo ' active';?>"><?=_('Invoices')?></a> <a class="nav-link<?php if($page == 'activities') echo ' active';?>" title="<?=_('Tracksz Member Activities')?>" href="user-activities.html"><?=_('Activities')?><span class="badge">16</span></a>
        </div><!-- /.nav -->
    </div><!-- /.nav-scroller -->
</nav><!-- /.page-navs -->