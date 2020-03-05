<?php $this->layout('layouts/emails') ?>

<?php $this->start('body') ?>
    <!-- Message start -->
    <table>
        <tr>
            <td class="content">
                
                <h2><?=_('Greetings')?>,</h2>
                
                <p><?=_('Thank you for registering at Tracksz .  To finish your registration, please click the button below to validate your email address.')?></p>
                
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
                
                <p><em><?=\App\Library\Config::get('company_name')?></em></p>
            
            </td>
        </tr>
    </table>
<?php $this->stop() ?>