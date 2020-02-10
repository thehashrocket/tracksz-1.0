<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?> <?=$fullname?>,</h2>
                
                <p><?=_('Thank you for your interest in our upcoming MultiMarket Inventory & Order Management Service.')?></p>
                
                <p><?=_('We are working hard to get this done as quickly as possible.  If you have any ideas or suggestions that you think of later, please don\'t hesitate to email.')?></p>
                
                <p><?=_('As we get closer to completion, we will send out more announcements regarding our progress.')?></p>
                
                <p><?=_('Thank You!')?></p>
                
                <p><em><?=\App\Library\Config::get('company_name')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>