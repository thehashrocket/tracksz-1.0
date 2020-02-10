<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?>,</h2>
                
                <p><?=_('You have submitted a request to change your email.  To complete this change, please click the button below to validate your email address.')?></p>
                
                <p><?=_('The button <b>expires</b> in one hour.  Please validate your email within that time.')?></p>
                
                <table>
                    <tr>
                        <td align="center">
                            <p>
                                <a href="<?= $url ?>" class="button"><?=_('Validate Email Address')?></a>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <p><?=_('Thank You!')?></p>
                
                <p><em><?=_('Tracksz')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>