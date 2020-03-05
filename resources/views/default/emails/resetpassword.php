<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?>,</h2>
                
                <p><?=_('You have requested to reset your Tracksz password. To finish resetting your password, click the button below.')?></p>
                
                <p><?=_('The button <b>expires</b> in one hour. Please reset your password within that time.')?></p>
                
                <table>
                    <tr>
                        <td align="center">
                            <p>
                                <a href="<?= $url ?>" class="button"><?=_('Reset Password')?></a>
                            </p>
                        </td>
                    </tr>
                </table>
    
                <p><?=_('If you did not make this request, ignore this email. No changes have been made.')?></p>
                
                <p><?=_('Thank You!')?></p>
                
                <p><em><?=_('Tracksz')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>