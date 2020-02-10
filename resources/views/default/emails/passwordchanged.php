<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?>,</h2>
                
                <p><?=_('We received a request to change your Tracksz\' login password.  This change is complete. Please use the new password when you login to your Tracksz\' account.')?></p>
                
                <p><?=_('If you requested this change, then no further action is required. If you "did not" request this change, please contact Tracksz\'s support.')?></p>
                
                <p><?=_('Thank You!')?></p>
                
                <p><em><?=_('Tracksz')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>