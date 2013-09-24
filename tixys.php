<?php
/**
 * Plugin Name: Tixys Widget
 * Plugin URI: http://www.tixys.com/
 * Description: This plugin embeds a Tixys search widget into a WP page.
 * Version: 0.1
 * Author: Alex Günsche
 * License: MIT
 */

function tixys_activate()
{
    $settings = (object)array(
        'site' => null,
        'path' => null
    );

    add_option('tixys_config', $settings);
}

function tixys_init()
{
    load_plugin_textdomain('tixys', false, 'wp-content/plugins/tixys');
}


function tixys_adminpage()
{
    add_options_page('Tixys configuration', 'Tixys', 'edit_pages', dirname(__FILE__).'/adminpage.php');
}

function tixys_process_settings()
{
    if (isset($_POST['tixys_update']) && $_POST['tixys_update'] === 'true')
    {
        check_admin_referer('tixys_update_options');

        if (isset($_POST['tixys_site']) &&
            is_numeric($_POST['tixys_site']) &&
            (int)$_POST['tixys_site'] > 1 &&
            isset($_POST['tixys_path']) &&
            preg_match('|^[a-z][a-z0-9]+$|', $_POST['tixys_path']) &&
            strlen($_POST['tixys_path']) >= 4)
        {
            $tixys_options = get_option('tixys_options');
            $tixys_options->site = (int)$_POST['tixys_site'];
            $tixys_options->path = $_POST['tixys_path'];

            update_option('tixys_options', $tixys_options);
            add_action('admin_notices', 'tixys_admin_notice_ok');
        }
        else
        {
            add_action('admin_notices', 'tixys_admin_notice_error');
        }
    }
}

function tixys_admin_notice_ok() { tixys_admin_notice(true); }
function tixys_admin_notice_error() { tixys_admin_notice(false); }

function tixys_admin_notice($ok)
{
    ?>
        <div class="updated">
            <p><?php
                $ok
                    ? _e('The settings have been updated.', 'tixys')
                    : _e('There was an error, settings could not be saved. Please make sure to enter correct values.', 'tixys');
            ?></p>
        </div>
    <?php
}

function tixys_generate_form($attr, $content=null, $code="")
{
    global $formId, $wp_scripts;

    $options = get_option('tixys_options');

    if ($options->site && $options->path)
    {
        $urlbase = plugin_dir_url(__FILE__);
        $formId = isset($formId) ? ++$formId : 1;

        extract(shortcode_atts(array(
            // defaults
            'target' => 'new',
            'datepicker' => false,
            'affiliate' => null,
            'from' => null,
            'to' => null,
        ), $attr), EXTR_SKIP);

        $datepicker = ($datepicker === 'true');
        $affiliate = (is_numeric($affiliate) && $affiliate > 0) ? (int)$affiliate : null;
        $from = (is_numeric($from) && $from > 0) ? (int)$from : null;
        $to = (is_numeric($to) && $to > 0) ? (int)$to : null;

        require_once dirname(__FILE__).'/backend.php';
        $backend = new tixys_backend($options);

        wp_enqueue_script('tixys-lib', "$urlbase/tx.lib.js");
        wp_enqueue_script('tixys-form', "$urlbase/tx.page.src.js");
        wp_enqueue_style('tixys-form', "$urlbase/tx.form.css");

        if ($datepicker)
        {
            wp_enqueue_script('jquery-ui-datepicker');
            $ui = $wp_scripts->query('jquery-ui-core');
            $cssurl = "//ajax.aspnetcdn.com/ajax/jquery.ui/{$ui->ver}/themes/smoothness/jquery-ui.css";
            wp_enqueue_style('tixys-jquery-ui-smoothness', $cssurl, false, $ui->ver);
        }

        ob_start();

        if ($formId === 1)
        {
            printf("
                <script type='text/javascript'>/* <![CDATA[ */
                    var Tx = { config : {
                        site        : %s,
                        locale      : '%s',
                        urlBase     : 'https://tixo.net/%s',
                        apiBase     : 'https://tixo.net/api/v1'
                    }};
                /* ]]> */</script>",
                $options->site,
                get_locale(),
                esc_html($options->path)
            );
        }

        require dirname(__FILE__).'/form.php';
        return ob_get_clean();
    }
}

function tixys_out($string)
{
    $lang = substr(get_locale(), 0, 2);
    $obj = tixys_multilangStringToObject($string);

    if (isset($obj->$lang))
        $newString = $obj->$lang;
    elseif (isset($obj->en))
        $newString = $obj->en;
    else
        $newString = $string;

    return esc_html($newString);
}


function tixys_multilangStringToObject($string)
{
    $obj = new stdClass;

    if (strpos($string, '[:') !== false && preg_match('|^\[:[a-z]{2}\]|', $string))
    {
        $stringarray = preg_split('|\[:([a-z]{2})\]|', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

        // throw away (empty) first element and renumber
        array_shift($stringarray);
        $stringarray = array_values($stringarray);

        if (is_array($stringarray) && count($stringarray) >= 2)
        {
            foreach ($stringarray as $k=>$v)
            {
                if (!($k%2) && $v && isset($stringarray[$k+1]))
                {
                    $obj->$v = $stringarray[$k+1];
                }
            }
        }
    }

    return $obj;
}

add_action('admin_init', 'tixys_process_settings');
add_action('admin_menu', 'tixys_adminpage');
add_shortcode('tixysform', 'tixys_generate_form');
add_action('init', 'tixys_init');

register_activation_hook(__FILE__, 'tixys_activate');
