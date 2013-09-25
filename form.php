<?php
    if (!function_exists('tixys_generate_form')) // prevent direct calls
        die('You should not be here. Go away.');

?>

<form data-txform='<?php echo $formId ?>' class='tixys-search' method='get' action=''<?php if ($target === 'new') echo " target='_blank'" ?>>
    <script type="text/javascript">/* <![CDATA[ */
        var txForms = txForms || {};
        txForms[<?php echo $formId ?>] = {
            from        : <?php echo $from ? $from : 'null' ?>,
            to          : <?php echo $to ? $to : 'null' ?>,
            datepicker  : <?php echo $datepicker ? 'true' : 'false' ?>
        };
    /* ]]> */</script>

    <table>
        <?php if (!$from) : ?>
        <tr>
            <th><label for='tixys-select-station-from-<?php echo $formId ?>'><?php _e('From:', 'tixys') ?></label></th>
            <td><select class='tixys-select-station-from' id='tixys-select-station-from-<?php echo $formId ?>' name='from'>

                <option value='0'><?php _e("– Please select –", 'tixys') ?></option>
                <?php $list = ($to) ? $backend->get_result('to', $to) : $backend->get_result('start'); foreach ($list as $station) : ?>
                    <option value='<?php echo $station->id; ?>'><?php echo tixys_out($station->name); ?></option>
                <?php endforeach ?>

            </select></td>
            <td class='tixys-indicator'><img class='tixys-from' src='<?php echo $urlbase ?>/spinner.gif'></td>
        </tr>
        <?php endif ?>

        <?php if (!$to) : ?>
        <tr>
            <th><label for='tixys-select-station-to-<?php echo $formId ?>'><?php _e('To:', 'tixys') ?></label></th>
            <td><select class='tixys-select-station-to' id='tixys-select-station-to-<?php echo $formId ?>' name='to'<?php if (!$from) echo " disabled='disabled'" ?>>

                <?php if ($from) : ?>
                <option value='0'><?php _e("– Please select –", 'tixys') ?></option>
                    <?php $list = $backend->get_result('from', $from); foreach ($list as $station) : ?>
                        <option value='<?php echo $station->id; ?>'><?php echo tixys_out($station->name); ?></option>
                    <?php endforeach ?>
                <?php endif ?>

            </select></td>
            <td class='tixys-indicator'><img class='tixys-to' src='<?php echo $urlbase ?>/spinner.gif'></td>
        </tr>
        <?php endif ?>

        <?php if ($datepicker) : ?>
            <tr>
                <th><label for='tixys-select-day-<?php echo $formId ?>'><?php _e('Day:', 'tixys') ?></label></th>
                <td colspan='2'>
                    <input type='text' class='tixys-select-day' id='tixys-select-day-<?php echo $formId ?>' />
                    <input type='hidden' name='day' value='' />
                </td>
            </tr>
        <?php endif ?>

        <tr>
            <th></th>
            <td colspan='2'>
                <?php if ($affiliate) : ?>
                <input type='hidden' name='aff' value='<?php echo $affiliate ?>' />
                <?php endif ?>

                <?php if ($from) : ?>
                <input type='hidden' name='from' value='<?php echo $from ?>' />
                <?php endif ?>
                <?php if ($to) : ?>
                <input type='hidden' name='to' value='<?php echo $to ?>' />
                <?php endif ?>

                <input type='hidden' name='st' value='1' />
                <input type='submit' class='btn dark' value='<?php _e('Search rides and book a ticket »', 'tixys') ?>' />
            </td>
        </tr>
        <tr>
            <th></th>
            <td colspan='2' class='tixys-home'><?php _e("Please note: You will be forwarded to our <a href='http://www.tixys.com/' target='_blank'>booking site</a>.", 'tixys') ?></td>
        </tr>
    </table>
</form>
