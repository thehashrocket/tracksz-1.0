<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?>,</h2>
                
                <p><?=_('You have requested to receive your Tracksz username.  It is listed below.')?></p>
                
                <p><?=_('Username:')?> <?=$username?></p>
                
                <p><?=_('Please securely store this Username where you can find it later.  We recommend you delete this email from your inbox and deleted folder for security reasons.')?></p>
                
                <p><?=_('Thank You!')?></p>
                
                <p><em><?=_('Tracksz')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>