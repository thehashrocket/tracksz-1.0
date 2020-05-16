<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
<!-- Message start -->
<table>
    <tr>
        <td class="content">

            <h2><?= _('Hello') ?>,</h2>

            <p><?= _('As per your request, Inventory are updated Successfully. For more information') ?></p>

            <p><?= _('Please check the attachment Log file of Inventory Update Status.') ?></p>

            <p><?= _('Thank You!') ?></p>

            <p><em><?= \App\Library\Config::get('company_name') ?></em></p>

        </td>
    </tr>
</table>
<?php $this->stop() ?>