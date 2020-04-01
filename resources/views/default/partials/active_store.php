<?php
    use Delight\Cookie\Cookie;
?>
<!-- .page-title-bar -->
<div class="ml-auto">
    <strong>Active Store:</strong>&nbsp;&nbsp;
    <?php if(Cookie::exists('tracksz_active_store') &&
        Cookie::get('tracksz_active_store') > 0): ?>
        <a href="/account/stores" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="<?=_('Click to Change Active Store')?>"><i class="fas fa-shopping-cart ml-1"></i> <?=urldecode(Cookie::get('tracksz_active_name'))?></a>
    <?php else: ?>
        <a href="/account/stores" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="<?=_('Click to Select Active Store')?>"><?=_('Select Active Store')?></a>
    <?php endif; ?>
</div>