<?php
    if (!current_user_can('edit_pages')) // prevent direct includes
        die('You should not be here. Go away.');

    $tixys_options = get_option('tixys_options');
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div><h2><?php _e('Tixys settings', 'tixys') ?></h2>

    <form action="<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>" method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="tixys_site"><?php _e('Tixys site ID', 'tixys') ?></label></th>
                <td>
                    <input name="tixys_site" type="text" id="tixys_site" value="<?php echo $tixys_options->site ? (int)$tixys_options->site : '' ?>" class="small-text" />
                    <p class="description"><?php _e("Your shop's Tixys site. If you don't know, refer to the Tixys support.", 'tixys') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="tixys_path"><?php _e('Tixys path', 'tixys') ?></label></th>
                <td>
                    https://www.tixys.com/<input name="tixys_path" type="text" id="tixys_path" value="<?php echo $tixys_options->path ? esc_html($tixys_options->path) : '' ?>" />
                    <p class="description"><?php _e("Your shop's Tixys path. If you don't know, refer to the Tixys support.", 'tixys') ?></p>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input name="tixys_update" type="hidden" value="true" />
            <?php wp_nonce_field('tixys_update_options'); ?>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Submit Changes') ?>"  />
        </p>
    </form>
</div>
