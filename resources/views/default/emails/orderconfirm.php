<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
<!-- Message start -->
<table>
    <tr>
        <td class="content">

            <h2><?= _('Hello') ?>,</h2>

            <p><?= _('Your Order as been placed, You order id is:') ?><?= $OrderId ?></p>

            <p><?= _('By Carrier :') ?><?= $Carrier ?></p>

            <p><?= _('By Customer Name :') ?><?= $BillingName ?></p>

            <p><?= _('By Tracking :') ?><?= $Tracking ?></p>

            <p><?= _('Thank You!') ?></p>

            <p><em><?= \App\Library\Config::get('company_name') ?></em></p>

        </td>
    </tr>
</table>
<?php $this->stop() ?>
