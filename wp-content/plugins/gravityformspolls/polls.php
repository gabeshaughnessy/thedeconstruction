<?php
/*
Plugin Name: Gravity Forms Polls Add-On
Description: Polls Add-on for Gravity Forms
Version: 1.5
Author: Rocketgenius
Author URI: http://www.rocketgenius.com

------------------------------------------------------------------------
Copyright 2012-2013 Rocketgenius Inc.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/


// example usage of gpoll_widget_override filter to allow widgets to override form settings
/*
add_filter('gpoll_widget_override' , 'gpoll_widget_override');

function gpoll_widget_override($form_ids){
     array_push($form_ids, 7, 9);
     return $form_ids;
}
*/


// The wp_cron job will take over the calculation of the results if 5 seconds is not long enough at the time of form submission/update.
// The default schedule is set to hourly to avoid heavy loads on the server but you can change this frequency by using the gform_polls_cron_schedule.
// The format follows the format required by the WordPress cron_schedules filter. http://codex.wordpress.org/Function_Reference/wp_get_schedules
// Important: the Polls Add-On must be deactivated and reactivated in order to reschedule the task.
/*
add_filter( 'gform_polls_cron_schedule', 'cron_add_twice_hourly' );
function cron_add_twice_hourly() {
    // Adds once weekly to the existing schedules.
    $schedule['twicehourly'] = array(
        'interval' => 1800, // number of seconds in the interval
        'display' => __( 'Twice Hourly' )
    );
    return $schedule;
}
*/

require_once(GFPolls::get_base_path() . "/pollwidget.php");

add_action('init', array('GFPolls', 'init'));

register_activation_hook(__FILE__, array("GFPolls", "add_permissions"));
register_deactivation_hook(__FILE__, array("GFPolls", "remove_wp_cron_task"));

//need to be added before init
add_action("gform_after_submission", array('GFPolls', 'after_submission'), 10, 2);
add_filter('gform_export_field_value', array('GFPolls', 'display_entries_field_value'), 10, 3);
add_action('gform_polls_cron', array('GFPolls', 'wp_cron_task'));

//------------------------------------------
class GFPolls {

    private static $path = "gravityformspolls/polls.php";
    private static $url = "http://www.gravityforms.com";
    private static $slug = "gravityformspolls";
    private static $version = "1.5";
    private static $min_gravityforms_version = "1.7.4.2";

    private static $_form_meta_by_id = array();

    static $gpoll_add_scripts;

    //Plugin starting point. Will load appropriate files
    public static function init() {

        if (basename($_SERVER['PHP_SELF']) == "plugins.php") {
            //loading translations
            load_plugin_textdomain('gravityformspolls', FALSE, '/gravityformspolls/languages');

            add_action('after_plugin_row_' . self::$path, array('GFPolls', 'plugin_row'));
        }

        if (!self::is_gravityforms_supported()) {
            return;
        }

        //registering scripts
        self::register_scripts();

        //loading translations
        load_plugin_textdomain('gravityformspolls', FALSE, '/gravityformspolls/languages');

        if (is_admin()) {
            //form editor
            add_filter('gform_add_field_buttons', array('GFPolls', 'add_poll_field'));
            add_action('gform_field_standard_settings', array('GFPolls', 'poll_field_settings'), 10, 2);
            add_filter('gform_tooltips', array('GFPolls', 'add_poll_field_tooltips'));
            add_action("gform_after_save_form", array('GFPolls', 'after_save_form'), 10, 2);
            add_filter("gform_field_type_title", array('GFPolls', 'assign_title'), 10, 2);

            if (version_compare(GFCommon::$version, "1.6.999", '<'))
                add_action('gform_properties_settings', array('GFPolls', 'form_settings'), 10, 2);

            //settings page
            add_action('gform_form_settings_menu', array('GFPolls', 'add_form_settings_menu'), 10, 2);
            add_action("gform_form_settings_page_polls", array('GFPolls', 'add_form_settings_page'));

            //scripts
            add_action('admin_enqueue_scripts', array('GFPolls', 'enqueue_admin_scripts'));

            add_filter('gform_noconflict_scripts', array('GFPolls', 'register_noconflict_scripts'));
            add_filter('gform_noconflict_styles', array('GFPolls', 'register_noconflict_styles'));

            //display poll results on entry detail & entry list
            add_filter('gform_entry_field_value', array('GFPolls', 'display_poll_on_entry_detail'), 10, 4);
            add_filter('gform_entries_field_value', array('GFPolls', 'display_entries_field_value'), 10, 3);

            //merge tags
            add_filter('gform_custom_merge_tags', array('GFPolls', 'custom_merge_tags'), 10, 4);

            //Automatic upgrade functionality
            add_filter("transient_update_plugins", array('GFPolls', 'check_update'));
            add_filter("site_transient_update_plugins", array('GFPolls', 'check_update'));
            add_action('install_plugins_pre_plugin-information', array('GFPolls', 'display_changelog'));

            //update the cache
            add_action("gform_after_update_entry", array('GFPolls', 'entry_updated'), 10, 2);
            add_action("gform_update_status", array('GFPolls', 'update_entry_status'), 10, 2);

            //add top toolbar menu item
            add_filter("gform_toolbar_menu", array('GFPolls', 'add_toolbar_menu_item'), 10, 2);
            //add custom form action
            add_filter("gform_form_actions", array('GFPolls', 'add_form_action'), 10, 2);

            if (self::has_members_plugin())
                add_filter('members_get_capabilities', array("GFPolls", "members_get_capabilities"));


            //add the gf_poll_results view
            add_action("gform_view", array('GFPolls', 'add_view'), 10, 2);

            if (RG_CURRENT_PAGE == "admin-ajax.php") {
                require_once(self::get_base_path() . "/results.php");
                add_action('wp_ajax_gpoll_get_poll_results', array('GFPollsResults', 'ajax_get_results'));
            }

            //results page ajax
            if (RG_CURRENT_PAGE == "admin-ajax.php") {
                if (rgpost("view") == "gf_poll_results") {
                    require_once(self::get_base_path() . "/results.php");
                    add_action('wp_ajax_gresults_get_results_gf_poll_results', array('GFResults', 'ajax_get_results'));
                    add_action('wp_ajax_gresults_get_more_results_gf_poll_results', array('GFResults', 'ajax_get_more_results'));
                }

            }

        } else {
            // ManageWP premium update filters
            add_filter('mwp_premium_update_notification', array('GFPolls', 'premium_update_push'));
            add_filter('mwp_premium_perform_update', array('GFPolls', 'premium_update'));

            //enqueue styles for the preview page
            add_filter("gform_preview_styles", array('GFPolls', 'enqueue_preview_style'), 10, 2);
            add_action('gform_enqueue_scripts', array('GFPolls', 'enqueue_scripts'), 10, 2);
            add_action('wp_footer', array('GFPolls', 'print_scripts'));

            //maybe display results on confirmation
            add_filter("gform_confirmation", array('GFPolls', 'display_confirmation'), 10, 4);

            //shortcodes
            add_filter('gform_shortcode_polls', array('GFPolls', 'poll_shortcode'), 10, 3);
            add_shortcode('gfpolls_total', array('GFPolls', 'poll_total_shortcode'));

            //merge tags
            add_filter('gform_merge_tag_filter', array('GFPolls', 'merge_tag_filter'), 10, 5);
            add_filter('gform_replace_merge_tags', array('GFPolls', 'render_merge_tag'), 10, 7);

            add_filter('gform_entry_field_value', array('GFPolls', 'display_poll_on_entry_print'), 10, 4);

            //shuffle choices if configured
            add_filter("gform_field_content", array('GFPolls', 'render_poll_field_content'), 10, 5);

            //update the cache
            add_action('gform_entry_created', array('GFPolls', 'entry_created'), 10, 2);

            add_action("gform_validation", array('GFPolls', 'form_validation'));

            add_filter("gform_pre_render", array('GFPolls', 'pre_render'));

        }

        //add a special class to poll fields so we can identify them later
        add_action('gform_field_css_class', array('GFPolls', 'add_custom_class'), 10, 3);

        if (in_array(RG_CURRENT_PAGE, array("admin.php", "admin-ajax.php"))) {
            //ajax
            add_action('wp_ajax_gpoll_ajax', array('GFPolls', 'gpoll_ajax'));
            add_action('wp_ajax_nopriv_gpoll_ajax', array('GFPolls', 'gpoll_ajax'));
        }


        if (GFPolls::get_custom_cron_schedule())
            add_filter('cron_schedules', array('GFPolls', 'cron_add_custom_schedule'));

        // add wp_cron job if it's not already scheduled
        if (!wp_next_scheduled('gform_polls_cron')) {
            wp_schedule_event(time(), self::get_cron_recurrence(), 'gform_polls_cron');
        }

    }

    /*--------- Ajax functions -------*/

    public static function gpoll_ajax() {
        $output = array();

        $form_id = rgpost('formId');
        $form    = RGFormsModel::get_form_meta($form_id);

        $preview_results = rgpost('previewResults');
        $preview_results = $preview_results == "1" ? true : false;

        $has_voted = isset ($_COOKIE['gpoll_form_' . $form_id]);
        $override  = false;
        if (rgpost("override") == 1) {
            $show_results_link = rgpost('showResultsLink') == "1" ? true : false;

            $display_results = rgpost('displayResults') == "1" ? true : false;
            $confirmation    = rgpost('confirmation') == "1" ? true : false;
            $percentages     = rgpost('percentages') == "1" ? true : false;
            $counts          = rgpost('counts') == "1" ? true : false;
            $cookie_duration = urldecode(rgpost('cookieDuration'));
            $style           = rgpost('style');
            $checksum        = rgpost('checksum');
            if ($checksum == self::generate_checksum($display_results, $show_results_link, $cookie_duration, $confirmation, $percentages, $counts, $style))
                $override = true;

        }

        if (false === $override) {
            $show_results_link   = self::get_form_setting($form, 'gpollShowResultsLink');
            $display_results     = self::get_form_setting($form, 'gpollDisplayResults');
            $confirmation        = true;
            $percentages         = self::get_form_setting($form, 'gpollShowPercentages');
            $counts              = self::get_form_setting($form, 'gpollShowCounts');
            $style               = self::get_form_setting($form, 'gpollStyle');
            $block_repeat_voters = self::get_form_setting($form, 'gpollBlockRepeatVoters');

            if ($block_repeat_voters) {
                $cookie_duration = self::get_form_setting($form, 'gpollCookie');
            } else {
                $cookie_duration = "";
            }
        }


        $can_vote          = (!$has_voted) || (empty($cookie_duration) && $has_voted);
        $output["canVote"] = $can_vote;

        if ($preview_results || (false === $can_vote)) {

            if ("" === $show_results_link)
                $show_results_link = true;

            if (($preview_results && $show_results_link) || $display_results) {
                $results             = self::gpoll_get_results($form_id, "0", $style, $percentages, $counts);
                $results_summary     = $results["summary"];
                $output["resultsUI"] = $results_summary;
            } else {
                if ($confirmation) {
                    require_once(GFCommon::get_base_path() . "/form_display.php");
                    $output["resultsUI"] = GFFormDisplay::handle_confirmation($form, null);
                } else {
                    $output["resultsUI"] = "";
                }

            }

        } else {
            $output["resultsUI"] = "";
        }

        echo json_encode($output);
        die();

    }

    /*--------- Front-end UI functions -------*/

    public static function get_form_setting($form, $setting_name) {
        $setting_value = rgar($form, $setting_name);
        if ("" === $setting_value) {
            //default values
            switch ($setting_name) {
                case "gpollDisplayResults" :
                case "gpollShowResultsLink" :
                case "gpollShowPercentages" :
                case "gpollShowCounts" :
                case "gpollShowCounts" :
                    $setting_value = true;
                    break;
                case "gpollBlockRepeatVoters" :
                    $setting_value = false;
                    break;
                case "gpollStyle" :
                    $setting_value = "green";
                    break;
                case "gpollCookie" :
                    $setting_value = "1 month";
                    break;
            }

        }
        //fix for imported forms where booleans get changed into "1" and "0"
        if ($setting_value == "1")
            $setting_value = true;
        if ($setting_value == "0")
            $setting_value = false;

        return $setting_value;
    }

    public static function pre_render($form) {

        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (false === empty ($poll_fields)) {
            $form_css          = "gpoll_enabled";
            $show_results_link = self::get_form_setting($form, "gpollShowResultsLink");

            if ($show_results_link)
                $form_css .= " gpoll_show_results_link";

            $block_repeat_voters = self::get_form_setting($form, "gpollBlockRepeatVoters");

            if ($block_repeat_voters && rgget("gf_page") != "preview")
                $form_css .= " gpoll_block_repeat_voters";

            $form["cssClass"] = empty($form["cssClass"]) ? $form_css . " gpoll" : $form_css . " " . $form["cssClass"];
        }

        return $form;
    }

    public static function form_validation($validation_result) {
        $form        = $validation_result["form"];
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return $validation_result;
        $form_setting_block_repeat = self::get_form_setting($form, "gpollBlockRepeatVoters");
        $field_values              = wp_parse_args(rgpost("gform_field_values"));
        if ($form_setting_block_repeat || (isset($field_values["gpoll_enabled"]) && $field_values["gpoll_enabled"] == "1" && isset($field_values["gpoll_cookie"]) && false === empty($field_values["gpoll_cookie"]))) {
            $form_id = rgar($form, "id");
            if (isset ($_COOKIE['gpoll_form_' . $form_id])) {
                // set the form validation to false
                $validation_result["is_valid"] = false;
                foreach ($form["fields"] as &$field) {
                    if ("poll" == rgar($field, "type")) {
                        $field["failed_validation"]  = true;
                        $field["validation_message"] = __("Repeat voting is not allowed", "gravityformspolls");
                    }
                }
                $validation_result["form"] = $form;
            }
        }

        return $validation_result;
    }

    //if the user has selected enableRandomizeChoices then shuffle the choices before displaying them
    public static function render_poll_field_content($content, $field, $value, $lead_id, $form_id) {

        if ($lead_id === 0 && $field["type"] == "poll") {

            if (rgar($field, 'enableRandomizeChoices')) {

                //pass the HTML for the choices through DOMdocument to make sure we get the complete node
                $dom     = new DOMDocument();
                $content = '<?xml version="1.0" encoding="UTF-8"?>' . $content;
                //allow malformed HTML inside the choice label
                $previous_value = libxml_use_internal_errors(TRUE);
                $dom->loadHTML($content);
                libxml_clear_errors();
                libxml_use_internal_errors($previous_value);
                $content = $dom->saveXML($dom->documentElement);

                //pick out the elements: LI for radio & checkbox, OPTION for select
                $element_name = $field['inputType'] == 'select' ? 'select' : 'ul';
                $nodes        = $dom->getElementsByTagName($element_name)->item(0)->childNodes;

                //cycle through the LI elements and swap them around randomly
                $temp_str1 = "gpoll_shuffle_placeholder1";
                $temp_str2 = "gpoll_shuffle_placeholder2";
                for ($i = $nodes->length - 1; $i >= 0; $i--) {
                    $n = rand(0, $i);
                    if ($i <> $n) {
                        $i_str   = $dom->saveXML($nodes->item($i));
                        $n_str   = $dom->saveXML($nodes->item($n));
                        $content = str_replace($i_str, $temp_str1, $content);
                        $content = str_replace($n_str, $temp_str2, $content);
                        $content = str_replace($temp_str2, $i_str, $content);
                        $content = str_replace($temp_str1, $n_str, $content);
                    }
                }
                //snip off the tags that DOMdocument adds
                $content = str_replace("<html><body>", "", $content);
                $content = str_replace("</body></html>", "", $content);
            }

            if ($field['inputType'] == 'select') {
                $selected   = selected(true, empty($value), false);
                $new_option = sprintf('<option %s value="">%s</option></select>', $selected, __("Select one", "gravityformspolls"));
                $content    = str_replace("</select>", $new_option, $content);
            }

        }

        return $content;
    }

    //end function maybe_shuffle_choices

    public static function after_submission($entry, $form) {
        if (rgget("gf_page") == "preview")
            return;

        $set_cookie   = false;
        $cookie       = "";
        $field_values = array();
        if (isset($_POST["gform_field_values"]))
            $field_values = wp_parse_args($_POST["gform_field_values"]);

        $override = false;

        if (rgar($field_values, "gpoll_enabled") == "1") {
            $show_results_link = rgar($field_values, "gpoll_show_results_link");
            $show_results_link = $show_results_link == "1" ? true : false;
            $style             = rgar($field_values, "gpoll_style");
            $percentages       = rgar($field_values, "gpoll_percentages");
            $percentages       = $percentages == "1" ? true : false;
            $counts            = rgar($field_values, "gpoll_counts");
            $counts            = $counts == "1" ? true : false;
            $cookie            = $field_values["gpoll_cookie"];

            $display_results = rgar($field_values, "gpoll_display_results");
            $display_results = $display_results == "1" ? true : false;

            $display_confirmation = rgar($field_values, "gpoll_confirmation");
            $display_confirmation = $display_confirmation == "1" ? true : false;

            $checksum = rgar($field_values, 'gpoll_checksum');
            if ($checksum == self::generate_checksum($display_results, $show_results_link, $cookie, $display_confirmation, $percentages, $counts, $style)) {
                $set_cookie = true;
                $override   = true;
            }
        }

        if (false === $override) {
            if (self::get_form_setting($form, "gpollBlockRepeatVoters")) {
                $set_cookie = true;
                $cookie     = self::get_form_setting($form, "gpollCookie");
            }
        }

        if ($set_cookie) {
            $form_id    = $form["id"];
            $lead_id    = $entry["id"];
            $server_tz  = date_default_timezone_get();
            $browser_tz = rgar($_COOKIE, "gpoll-timezone"); // in hours
            date_default_timezone_set($browser_tz);
            $cookie_expiration = strtotime($cookie);
            date_default_timezone_set($server_tz);
            setcookie("gpoll_form_" . $form_id, $lead_id, $cookie_expiration, COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    /*-------- Scripts ----------*/

    public static function register_noconflict_scripts($scripts) {

        //registering script with Gravity Forms so that it gets enqueued when running on no-conflict mode
        $scripts[] = "gpoll_js";
        $scripts[] = "jquery-ui-resizable";
        $scripts[] = "gpoll_form_editor_js";
        $scripts[] = "gforms_json";
        $scripts[] = "gpoll_form_settings_js";
        $scripts[] = "gpoll_results_js";
        $scripts[] = "google_charts";

        return $scripts;
    }

    public static function register_noconflict_styles($styles) {

        //registering styles with Gravity Forms so that it gets enqueued when running on no-conflict mode
        $styles[] = "gpoll_css";
        $styles[] = "gpoll_form_editor_css";
        $styles[] = "gpoll_form_settings_css";
        $styles[] = "gpoll_results_css";

        return $styles;
    }

    //adds scripts to entry detail
    public static function enqueue_admin_scripts($hook) {
        if ('widgets.php' == $hook) {
            wp_enqueue_style('gpoll_widget_css', plugins_url('css/gpoll_widget.css', __FILE__));

            return;
        }

        $id      = rgget("id");
        $view    = rgget("view");
        $subview = rgget("subview");
        if (rgget("page") == "gf_edit_forms" && $view == "gf_poll_results") {
            wp_enqueue_script('jquery-ui-resizable', false, array('jquery'), false, false);
            if (version_compare(GFCommon::$version, "1.6.999", '>')) {
                wp_enqueue_script('jquery-ui-datepicker', false, array('jquery'), false, false);
                wp_enqueue_style('jquery-ui-styles', GFCommon::get_base_url() . '/css/datepicker.css');
            } else {
                wp_enqueue_script('gform-datepicker', GFCommon::get_base_url() . '/js/jquery-ui/ui.datepicker.js');
            }
            wp_enqueue_script('google_charts');
            wp_enqueue_script('gpoll_results_js');
            wp_enqueue_style('gpoll_results_css');
            self::localize_results_scripts();
        } elseif (rgget("page") == "gf_edit_forms" && $view == "settings" && $subview == "polls") {
            wp_enqueue_script("gforms_json", GFCommon::get_base_url() . "/js/jquery.json-1.3.js", array("jquery"), GFCommon::$version, true);
            wp_enqueue_script('gpoll_form_settings_js');
            wp_enqueue_style('gpoll_form_settings_css');
        } elseif ((rgget("page") == "gf_edit_forms" && !empty($id) && empty($view)) || rgget("page") == "gf_new_form") {
            wp_enqueue_style('gpoll_form_editor_css');
            wp_enqueue_style('gpoll_css');
            wp_enqueue_script('gpoll_form_editor_js');
            self::localize_form_editor_scripts();
        } elseif (rgget("page") == "gf_entries" && rgget("view") == "entry") {
            wp_enqueue_style('gpoll_css');
            wp_enqueue_script('gpoll_js');
            self::localize_scripts();
        }

    }


    public static function print_scripts() {

        if (self::$gpoll_add_scripts !== true) return;
        self::localize_scripts();
        wp_print_scripts('jquery');
        wp_print_scripts('gpoll_js');

        wp_print_styles('gpoll_css');

    }

    public static function register_scripts() {
        wp_register_script('google_charts', 'https://www.google.com/jsapi');
        wp_register_script('gpoll_js', plugins_url('js/gpoll.js', __FILE__), array('jquery'), self::$version);
        wp_register_script('gpoll_results_js', plugins_url('js/gpoll_results.js', __FILE__), array('jquery'), self::$version);
        wp_register_script('gpoll_form_editor_js', plugins_url('js/gpoll_form_editor.js', __FILE__), array('jquery'), self::$version);
        wp_register_script('gpoll_form_settings_js', plugins_url('js/gpoll_form_settings.js', __FILE__), array('jquery'), self::$version);
        wp_register_style('gpoll_css', plugins_url('css/gpoll.css', __FILE__), null, self::$version);
        wp_register_style('gpoll_form_editor_css', plugins_url('css/gpoll_form_editor.css', __FILE__), null, self::$version);
        wp_register_style('gpoll_form_settings_css', plugins_url('css/gpoll_form_settings.css', __FILE__), null, self::$version);
        wp_register_style('gpoll_results_css', plugins_url('css/gpoll_results.css', __FILE__), null, self::$version);

    }

    public static function enqueue_preview_style($styles, $form) {
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (false === empty ($poll_fields))
            $styles[] = "gpoll_css";

        return $styles;
    }


    // add gf_poll scripts to the page if the form is a poll
    public static function enqueue_scripts($form, $is_ajax) {

        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (false === empty ($poll_fields)) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('gpoll_js');
            wp_enqueue_style('gpoll_css');
            self::localize_scripts();
        }


    } // end function enqueue_scripts

    public static function localize_scripts() {

        // Get current page protocol
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        // Output admin-ajax.php URL with same protocol as current page
        $params = array(
            'ajaxurl'   => admin_url('admin-ajax.php', $protocol),
            'imagesUrl' => self::get_base_url() . "/images"
        );
        wp_localize_script('gpoll_js', 'gpollVars', $params);

        //localisable strings for the js file
        $strings = array(
            'viewResults'   => __("View results", "gravityformspolls"),
            'backToThePoll' => __("Back to the poll", "gravityformspolls")

        );
        wp_localize_script('gpoll_js', 'gpoll_strings', $strings);

    }

    public static function localize_results_scripts() {

        $filter_fields    = rgget("f");
        $filter_operators = rgget("o");
        $filter_values    = rgget("v");

        // Get current page protocol
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        // Output admin-ajax.php URL with same protocol as current page

        $vars = array(
            'ajaxurl'         => admin_url('admin-ajax.php', $protocol),
            'imagesUrl'       => self::get_base_url() . "/images",
            'filterFields'    => $filter_fields,
            'filterOperators' => $filter_operators,
            'filterValues'    => $filter_values
        );


        wp_localize_script('gpoll_results_js', 'gresultsVars', $vars);

        $strings = array(
            'noFilters'         => __("No filters", "gravityformspolls"),
            'addFieldFilter'    => __("Add a field filter", "gravityformspolls"),
            'removeFieldFilter' => __("Remove a field filter", "gravityformspolls"),
            'ajaxError'         => __("Error retrieving results. Please contact support.", "gravityformspolls")
        );


        wp_localize_script('gpoll_results_js', 'gresultsStrings', $strings);

    }

    public static function localize_form_editor_scripts() {

        //localize strings
        $strings = array(
            'firstChoice'  => __("First Choice", "gravityformspolls"),
            'secondChoice' => __("Second Choice", "gravityformspolls"),
            'thirdChoice'  => __("Third Choice", "gravityformspolls")
        );
        wp_localize_script('gpoll_form_editor_js', 'gpollStrings', $strings);

    }

    /*-------- Admin functions ----------*/

    public static function cron_add_custom_schedule($schedules) {
        // Adds once weekly to the existing schedules.
        $custom_schedules = self::get_custom_cron_schedule();
        $schedules        = array_merge($schedules, $custom_schedules);

        return $schedules;
    }

    public static function get_cron_recurrence() {

        $custom_schedule = self::get_custom_cron_schedule();

        if (empty($custom_schedule)) {
            $recurrence = "hourly";
        } else {
            $recurrence = current(array_keys($custom_schedule));
        }

        return $recurrence;
    }


    public static function get_custom_cron_schedule() {
        $schedule = apply_filters('gform_polls_cron_schedule', array());

        return $schedule;
    }

    public static function remove_wp_cron_task() {
        wp_clear_scheduled_hook('gform_polls_cron');
    }

    // called only by the wp_cron task
    public static function wp_cron_task() {
        $forms = GFFormsModel::get_forms(true);
        foreach ($forms as $form) {
            $form_id     = $form->id;
            $form_meta   = self::get_form_meta($form_id);
            $poll_fields = GFCommon::get_fields_by_type($form_meta, array('poll'));
            if (empty ($poll_fields))
                continue;

            $data_tmp = GFCache::get('gpoll_data_tmp_' . $form_id);
            if (false === $data_tmp) {
                $data = GFCache::get('gpoll_data_' . $form_id);
                if (false == $data || rgar($data, "incomplete") || false === isset($data["execution_time"]) || rgar($data, "expired")) {
                    $data = self::gpoll_get_data($form_id);
                    self::maybe_continue_cache_rebuild($data, $form_id);
                }

            } else {
                $data = self::gpoll_get_data($form_id, $data_tmp);
                self::maybe_continue_cache_rebuild($data, $form_id);
            }

        }
    }

    // called only by the wp_cron job
    public static function maybe_continue_cache_rebuild($data, $form_id) {
        if (rgar($data, "incomplete")) {
            GFCache::set('gpoll_data_tmp_' . $form_id, $data, true);
        } else {
            GFCache::set('gpoll_data_' . $form_id, $data, true);
            GFCache::delete('gpoll_data_tmp_' . $form_id);
        }
    }

    // Called on entry created, entry updated, entry status changed and form saved.
    // Not called by the wp_cron job.
    public static function maybe_update_cache($form_id) {
        $key  = 'gpoll_data_' . $form_id;
        $data = GFCache::get($key);
        if (false === $data) {
            // nothing in the cache so start building it
            self::update_cache($form_id);
        } else {
            if (rgar($data, "execution_time") < 5) {
                // update the cache now if the last execution was under 5 seconds
                self::update_cache($form_id);
            } else {
                // mark the cache expired so the wp_cron job will begin recalculation
                $data["expired"] = true;
                GFCache::set($key, $data, true);
            }
        }
    }

    public static function add_form_settings_menu($tabs, $form_id) {
        $form        = self::get_form_meta($form_id);
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (false === empty($poll_fields))
            $tabs[] = array("name" => 'polls', "label" => __("Polls", "gravityformspolls"));

        return $tabs;
    }

    public static function add_form_settings_page() {
        require_once(self::get_base_path() . "/form_settings.php");
        GFPollsFormSettings::form_settings_page();
    }

    public static function display_entries_field_value($value, $form_id, $field_id) {
        global $_form_metas;

        $new_value = $value;

        if (!isset($_form_metas[$form_id]))
            $_form_metas[$form_id] = RGFormsModel::get_form_meta($form_id);

        $form_meta = $_form_metas[$form_id];

        $form_meta_field = RGFormsModel::get_field($form_meta, $field_id);
        if (rgar($form_meta_field,"type") == "poll") {
            if ($form_meta_field["inputType"] == "radio" || $form_meta_field["inputType"] == "select") {
                $new_value = GFCommon::selection_display($value, $form_meta_field, $currency = "", $use_text = true);
            } elseif ($form_meta_field["inputType"] == "checkbox") {
                $ary        = explode(", ", $value);
                $new_values = array();
                foreach ($ary as $response) {
                    $new_values[] = GFCommon::selection_display($response, $form_meta_field, $currency = "", $use_text = true);
                }
                $new_value = implode(', ', $new_values);
            }
        }

        return $new_value;
    }

    public static function entry_created($entry, $form) {
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return;

        //update cache
        $form_id = $form["id"];
        self::maybe_update_cache($form_id);

    }

    public static function after_save_form($form, $is_new) {

        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return;
        //update cache
        $form_id = $form["id"];

        self::maybe_update_cache($form_id);
    }

    public static function assign_title($title, $field_type) {
        if ($field_type == "poll")
            return __("Poll", "gravityformspolls");

        return $title;
    }

    public static function add_view($view, $form_id) {
        if ($view == "gf_poll_results") {

            require_once(self::get_base_path() . "/results.php");
            GFResults::results_page($form_id, array('poll'), __("Poll Results", "gravityformspolls"), "gf_edit_forms", $view);
        }
    }

    public static function has_members_plugin() {
        return function_exists('members_get_capabilities');
    }

    public static function members_get_capabilities($caps) {
        return array_merge($caps, array("gravityforms_poll_results"));
    }

    public static function get_form_meta($form_id) {
        $form_metas = self::$_form_meta_by_id;

        if (empty($form_metas)) {
            $form_ids = array();
            $forms    = RGFormsModel::get_forms();
            foreach ($forms as $form) {
                $form_ids[] = $form->id;
            }
            //backwards compatiblity with <1.7
            if (method_exists('GFFormsModel', 'get_form_meta_by_id'))
                $form_metas = GFFormsModel::get_form_meta_by_id($form_ids);
            else
                $form_metas = GFFormsModel::get_forms_by_id($form_ids);

            self::$_form_meta_by_id = $form_metas;
        }
        foreach ($form_metas as $form_meta) {
            if ($form_meta["id"] == $form_id)
                return $form_meta;
        }

    }

    public static function add_form_action($actions, $form_id) {

        if (isset($actions["settings"]))
            return self::filter_menu_items($actions, $form_id, true);

        //backwards compatiblity with <1.7
        $new_actions = $actions;
        $form_meta   = self::get_form_meta($form_id);
        $poll_fields = GFCommon::get_fields_by_type($form_meta, array('poll'));
        if (false === empty($poll_fields)) {
            $results_link = '<a href="' . admin_url("admin.php?page=gf_edit_forms&view=gf_poll_results&id={$form_id}") . '">' . __("Results", "gravityformspolls") . "</a>";

            $offset      = 3;
            $new_actions = array_slice($actions, 0, $offset, true) +
                array('results' => $results_link) +
                array_slice($actions, $offset, NULL, true);
        }

        return $new_actions;
    }

    public static function add_toolbar_menu_item($menu_items, $form_id) {
        return self::filter_menu_items($menu_items, $form_id, false);
    }

    public static function filter_menu_items($menu_items, $form_id, $compact) {
        $form_meta   = self::get_form_meta($form_id);
        $poll_fields = GFCommon::get_fields_by_type($form_meta, array('poll'));
        if (false === empty($poll_fields)) {
            $form_id    = $form_meta["id"];
            $link_class = "";
            if (rgget("page") == "gf_new_form")
                $link_class = "gf_toolbar_disabled";
            else if (rgget("page") == "gf_edit_forms" && rgget("view") == "gf_poll_results")
                $link_class = "gf_toolbar_active";

            $sub_menu_items   = array();
            $sub_menu_items[] = array(
                'label'        => __("Poll Results", "gravityformspolls"),
                'title'        => __("View poll results generated by this form", "gravityformspolls"),
                'link_class'   => $link_class,
                'url'          => admin_url("admin.php?page=gf_edit_forms&view=gf_poll_results&id={$form_id}"),
                'capabilities' => array("gravityforms_poll_results")
            );

            // test submenu item
            /*
            $sub_menu_items[] = array(
                'label'         => __("Test menu item", "gravityformspolls"),
                'title'         => __("This is the title", "gravityformspolls"),
                'url'           => "http://google.com",
                'capabilities' => array("gravityforms_poll_results")
            );
            */


            if (isset($menu_items["results"])) {
                $existing_link_class = $menu_items["results"]["link_class"];
                $link_class == empty($existing_link_class) ? $link_class : $existing_link_class;
                $existing_capabilities                   = $menu_items["results"]["capabilities"];
                $merged_capabilities                     = array_merge($existing_capabilities, array("gravityforms_poll_results"));
                $existing_sub_menu_items                 = $menu_items["results"]["sub_menu_items"];
                $merged_sub_menu_items                   = array_merge($existing_sub_menu_items, $sub_menu_items);
                $menu_items["results"]["link_class"]     = $link_class;
                $menu_items["results"]["capabilities"]   = $merged_capabilities;
                $menu_items["results"]["sub_menu_items"] = $merged_sub_menu_items;

            } else {
                $menu_items["results"] = array(
                    'label'          => __("Results", "gravityformspolls"),
                    'title'          => __("View results generated by this form", "gravityformspolls"),
                    'url'            => "",
                    'onclick'        => "return false;",
                    'menu_class'     => 'gf_form_toolbar_results',
                    'link_class'     => $link_class,
                    'capabilities'   => array("gravityforms_poll_results"),
                    'sub_menu_items' => $sub_menu_items,
                    'priority'       => 750
                );
            }

        }

        return $menu_items;
    }


    public static function update_entry_status($lead_id) {
        //update cache
        $lead        = RGFormsModel::get_lead($lead_id);
        $form_id     = $lead["form_id"];
        $form        = GFFormsModel::get_form_meta($form_id);
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return;
        self::maybe_update_cache($form_id);

    }

    public static function entry_updated($form, $lead_id) {
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return;

        //update cache
        $form_id = $form["id"];
        self::maybe_update_cache($form_id);
    }

    public static function update_cache($form_id) {

        $gpoll_data = self::gpoll_get_data($form_id);
        GFCache::set('gpoll_data_' . $form_id, $gpoll_data, true);

        return $gpoll_data;
    }

    public function merge_tag_filter($value, $merge_tag, $options, $field, $raw_value) {

        if ($merge_tag == "all_fields" && $field["type"] == "poll" && is_array($field["choices"])) {
            if ($field["inputType"] == "checkbox") {
                //parse checkbox string (from $value variable) and replace values with text
                foreach ($raw_value as $key => $val) {
                    $text  = RGFormsModel::get_choice_text($field, $val);
                    $value = str_replace($val, $text, $value);
                }
            } else {
                //replacing value with text
                $value = RGFormsModel::get_choice_text($field, $value);
            }
        }

        return $value;
    }

    public static function render_merge_tag($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {

        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));
        if (empty ($poll_fields))
            return $text;

        $enqueue_scripts = false;
        $form_id         = $form["id"];

        preg_match_all("/{all_poll_results(:(.*?))?}/", $text, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {

            $full_tag       = $match[0];
            $options_string = isset($match[2]) ? $match[2] : "";
            $options        = shortcode_parse_atts($options_string);

            extract(shortcode_atts(array(
                'field'       => 0,
                'style'       => "green",
                'percentages' => "true",
                'counts'      => "true"
            ), $options));
            $percentages     = strtolower($percentages) == "false" ? false : true;
            $counts          = strtolower($counts) == "false" ? false : true;
            $results         = GFPolls::gpoll_get_results($form_id, $field, $style, $percentages, $counts, $entry);
            $results_summary = $results["summary"];
            $new_value       = $results_summary;

            $text = str_replace($full_tag, $new_value, $text);

            $enqueue_scripts = true;
        }

        preg_match_all("/\{gpoll:(.*?)\}/", $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $full_tag = $match[0];

            $options_string = isset($match[1]) ? $match[1] : "";
            $options        = shortcode_parse_atts($options_string);

            extract(shortcode_atts(array(
                'field'       => 0,
                'style'       => "green",
                'percentages' => "true",
                'counts'      => "true"
            ), $options));

            $percentages     = strtolower($percentages) == "false" ? false : true;
            $counts          = strtolower($counts) == "false" ? false : true;
            $results         = GFPolls::gpoll_get_results($form_id, $field, $style, $percentages, $counts, $entry);
            $results_summary = $results["summary"];
            $new_value       = $results_summary;

            $text = str_replace($full_tag, $new_value, $text);

            $enqueue_scripts = true;

        }

        if ($enqueue_scripts) {
            wp_enqueue_script('jquery');
            wp_enqueue_style('gpoll_css');
            wp_enqueue_style('gpoll_form_editor_css');
            wp_enqueue_script('gpoll_js');
            self::localize_scripts();
        }

        return $text;

    }

    public static function custom_merge_tags($merge_tags, $form_id, $fields, $element_id) {
        $contains_poll_field = false;
        foreach ($fields as $field) {
            if ($field["type"] == "poll") {
                $contains_poll_field = true;
                $field_id            = $field["id"];
                $field_label         = $field['label'];
                $merge_tags[]        = array('label' => $field_label . ': Poll Results', 'tag' => "{gpoll:field={$field_id}}");
            }
        }
        if ($contains_poll_field)
            $merge_tags[] = array('label' => 'All Poll Results', 'tag' => '{all_poll_results}');

        return $merge_tags;
    }


    public static function form_settings($position, $form_id) {
        $form        = RGFormsModel::get_form_meta($form_id);
        $poll_fields = GFCommon::get_fields_by_type($form, array('poll'));

        $display_style = empty ($poll_fields) ? "display:none;" : "";

        //create settings on position 50 (right after Admin Label)
        if ($position == 500) {

            ?>
            <div id="gpoll-form-settings" style="<?php echo $display_style ?>">
                <strong><?php _e("Poll Settings", "gravityformspolls"); ?></strong>
                <br/><br/>
                <ul>
                    <li>
                        <input type="checkbox" id="gpoll-form-setting-display-results"
                               onclick="gpollSetFormProperty('gpollDisplayResults', this.checked)"/> <label
                            for="gpoll-form-setting-display-results"><?php _e("Display results of submitted poll fields", "gravityformspolls") ?> <?php gform_tooltip("gpoll_form_settings_display_results") ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="gpoll-form-setting-show-results-link"
                               onclick="gpollSetFormProperty('gpollShowResultsLink', this.checked)"/> <label
                            for="gpoll-form-setting-show-results-link"><?php _e("Show link to view results", "gravityformspolls") ?> <?php gform_tooltip("gpoll_form_settings_show_results_link") ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="gpoll-form-setting-show-percentages"
                               onclick="gpollSetFormProperty('gpollShowPercentages', this.checked)"/> <label
                            for="gpoll-form-setting-show-percentages"><?php _e("Show percentages", "gravityformspolls") ?> <?php gform_tooltip("gpoll_form_settings_show_percentages") ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="gpoll-form-setting-show-counts"
                               onclick="gpollSetFormProperty('gpollShowCounts', this.checked)"/> <label
                            for="gpoll-form-setting-show-counts"><?php _e("Show counts", "gravityformspolls") ?> <?php gform_tooltip("gpoll_form_settings_show_counts") ?></label>
                    </li>
                    <li class="gform_setting_left_half">
                        <label style="display:block;"
                               for="gpoll-form-setting-style"><?php _e("Style", "gravityformspolls"); ?>:</label>
                        <select id="gpoll-form-setting-style" name="gpoll-form-setting-style" style="width:90%;"
                                onchange="gpollSetFormProperty('gpollStyle', this.value)">
                            <option value="green"><?php _e("Green", "gravityformspolls") ?></option>
                            <option value="blue"><?php _e("Blue", "gravityformspolls") ?></option>
                            <option value="red"><?php _e("Red", "gravityformspolls") ?></option>
                            <option value="orange"><?php _e("Orange", "gravityformspolls") ?></option>
                        </select>
                    </li>
                    <li>
                        Repeat Voters <?php gform_tooltip("gpoll_form_settings_repeat_voters") ?><br/> <br/>
                        <input type="radio" name="gpoll-form-setting-block-repeat-voters" value="0"
                               id="gpoll-form-setting-block-repeat-voters-0"
                               onclick="gpollSetFormProperty('gpollBlockRepeatVoters', false);jQuery('#gpoll-form-setting-cookie-options').hide('slow');">
                        <label
                            for="gpoll-form-setting-block-repeat-voters-0"><?php _e("Don't block repeat voting", "gravityformspolls"); ?></label><br>
                        <input type="radio" name="gpoll-form-setting-block-repeat-voters" value="1"
                               id="gpoll-form-setting-block-repeat-voters-1"
                               onclick="gpollSetFormProperty('gpollBlockRepeatVoters', true);jQuery('#gpoll-form-setting-cookie-options').show('slow');">
                        <label
                            for="gpoll-form-setting-block-repeat-voters-1"><?php _e("Block repeat voting using cookie", "gravityformspolls"); ?></label><br>

                        <div id="gpoll-form-setting-cookie-options" style="width:44%; display:none;">
                            <br>
                            <label style="display:block;"
                                   for="gpoll-form-setting-cookie"><?php _e("Expires:", "gravityformspolls"); ?></label>
                            <select id="gpoll-form-setting-cookie" name="gpoll-form-setting-cookie" style="width:90%;"
                                    onchange="gpollSetFormProperty('gpollCookie', this.value)">
                                <option value="20 years"><?php _e("Never", "gravityformspolls") ?></option>
                                <option value="1 hour"><?php _e("1 hour", "gravityformspolls") ?></option>
                                <option value="6 hours"><?php _e("6 hours", "gravityformspolls") ?></option>
                                <option value="12 hours"><?php _e("12 hours", "gravityformspolls") ?></option>
                                <option value="1 day"><?php _e("1 day", "gravityformspolls") ?></option>
                                <option value="1 week"><?php _e("1 week", "gravityformspolls") ?></option>
                                <option value="1 month"><?php _e("1 month", "gravityformspolls") ?></option>
                            </select>
                        </div>

                    </li>
                </ul>

            </div>


        <?php
        }
    }


    public static function add_poll_field_tooltips($tooltips) {
        //form settings
        $tooltips["gpoll_form_settings_display_results"]      = "<h6>" . __("Display Results After Voting", "gravityformspolls") . "</h6>" . __("Select this to display the results of submitted poll fields.", "gravityformspolls");
        $tooltips["gpoll_form_settings_display_confirmation"] = "<h6>" . __("Display Confirmation", "gravityformspolls") . "</h6>" . __("Select this option to display the form confirmation message after the visitor has voted.", "gravityformspolls");
        $tooltips["gpoll_form_settings_show_results_link"]    = "<h6>" . __("Show Results Link", "gravityformspolls") . "</h6>" . __("Add a link to the form which allows the visitor to see the results without voting.", "gravityformspolls");
        $tooltips["gpoll_form_settings_show_percentages"]     = "<h6>" . __("Show Percentages", "gravityformspolls") . "</h6>" . __("Show the percentage of the total votes for each choice.", "gravityformspolls");
        $tooltips["gpoll_form_settings_show_counts"]          = "<h6>" . __("Show Counts", "gravityformspolls") . "</h6>" . __("Show the total number of votes for each choice.", "gravityformspolls");
        $tooltips["gpoll_form_settings_repeat_voters"]        = "<h6>" . __("Repeat Voters", "gravityformspolls") . "</h6>" . __("Choose whether to allow visitors to vote more than once. Repeat voting is controlled by storing a cookie on the visitor's computer.", "gravityformspolls");

        $tooltips["form_poll_question"]           = "<h6>" . __("Poll Question", "gravityformspolls") . "</h6>" . __("Enter the question you would like to ask the user. The user can then answer the question by selecting from the available choices.", "gravityformspolls");
        $tooltips["form_poll_field_type"]         = "<h6>" . __("Poll Type", "gravityformspolls") . "</h6>" . __("Select the field type you'd like to use for the poll.", "gravityformspolls");
        $tooltips["form_field_randomize_choices"] = "<h6>" . __("Randomize Choices", "gravityformspolls") . "</h6>" . __("Check the box to randomize the order in which the choices are displayed to the user. This setting affects only voting - it will not affect the order of the results.", "gravityformspolls");

        return $tooltips;
    }


    public static function poll_field_settings($position, $form_id) {

        //create settings on position 25 (right after Field Label)
        if ($position == 25) {
            ?>

            <li class="poll_question_setting field_setting">
                <label for="poll_question">
                    <?php _e("Poll Question", "gravityformspolls"); ?>
                    <?php gform_tooltip("form_poll_question"); ?>
                </label>
                <input type="text" id="poll_question" class="fieldwidth-3" onkeyup="SetFieldLabel(this.value)"
                       size="35"/>
            </li>

            <li class="poll_field_type_setting field_setting">
                <label for="poll_field_type">
                    <?php _e("Poll Type", "gravityformspolls"); ?>
                    <?php gform_tooltip("form_poll_field_type"); ?>
                </label>
                <select id="poll_field_type"
                        onchange="if(jQuery(this).val() == '') return; jQuery('#field_settings').slideUp(function(){StartChangePollType(jQuery('#poll_field_type').val());});">
                    <option value="select"><?php _e("Drop Down", "gravityformspolls"); ?></option>
                    <option value="radio"><?php _e("Radio Buttons", "gravityformspolls"); ?></option>
                    <option value="checkbox"><?php _e("Checkboxes", "gravityformspolls"); ?></option>

                </select>

            </li>

        <?php
        } elseif ($position == 1368) {
            //right after the other_choice_setting
            ?>
            <li class="randomize_choices_setting field_setting">

                <input type="checkbox" id="field_randomize_choices"
                       onclick="var value = jQuery(this).is(':checked'); SetFieldProperty('enableRandomizeChoices', value); UpdateFieldChoices(GetInputType(field));"/>
                <label for="field_randomize_choices" class="inline">
                    <?php _e('Randomize order of choices', "gravityformspolls"); ?>
                    <?php gform_tooltip("form_field_randomize_choices") ?>
                </label>

            </li>
        <?php
        }
    }

    public static function add_poll_field($field_groups) {

        foreach ($field_groups as &$group) {
            if ($group["name"] == "advanced_fields") {
                $group["fields"][] = array("class" => "button", "value" => __("Poll", "gravityformspolls"), "onclick" => "StartAddField('poll');");
                break;
            }
        }

        return $field_groups;
    }

    public static function display_poll_on_entry_detail($value, $field, $lead, $form) {
        $new_value = "";

        if ($field["type"] == 'poll') {
            $new_value .= '<div class="gpoll_entry">';
            $results         = GFPolls::gpoll_get_results($form["id"], $field["id"], "green", true, true, $lead);
            $results_summary = $results["summary"];
            $new_value .= $results_summary;
            $new_value .= '</div>';
            GFPolls::$gpoll_add_scripts = true;

            //if orginal response is not in results display below
            // TODO
            $selected_values  = array();
            $selected_values  = self::get_selected_values($form["id"], $field["id"], $lead);
            $possible_choices = array();
            $possible_choices = self::get_possible_choices($form["id"], $field["id"]);
            foreach ($selected_values as $selected_value) {
                if (!in_array($selected_value, $possible_choices)) {
                    $new_value = $new_value . __("<h2>Original Response</h2>", "gravityformspolls") . $value;
                    break;
                }
            }

        } else {
            $new_value = $value;
        }

        return $new_value;
    }

    public static function display_poll_on_entry_print($value, $field, $lead, $form) {

        $new_value = $value;

        if ($field["type"] == "poll" && is_array($field["choices"])) {
            if ($field["inputType"] == "checkbox") {

                foreach ($field["choices"] as $choice) {

                    $val       = $choice["value"];
                    $text      = RGFormsModel::get_choice_text($field, $val);
                    $new_value = str_replace($val, $text, $new_value);
                }

            } else {
                //replacing value with text
                $new_value = RGFormsModel::get_choice_text($field, $value);
            }
        }

        return $new_value;
    }


    // adds gpoll_field class to poll fields
    public static function add_custom_class($classes, $field, $form) {
        if ($field["type"] == "poll") {
            $classes .= " gpoll_field";
        }

        return $classes;
    }


    public static function add_permissions() {
        global $wp_roles;
        $wp_roles->add_cap("administrator", "gpoll");
    }

    private static function is_gravityforms_installed() {
        return class_exists("RGForms");
    }

    private static function is_gravityforms_supported() {
        if (class_exists("GFCommon")) {
            $is_correct_version = version_compare(GFCommon::$version, self::$min_gravityforms_version, ">=");

            return $is_correct_version;
        } else {
            return false;
        }
    }

    protected static function has_access($required_permission) {
        $has_members_plugin = function_exists('members_get_capabilities');
        $has_access         = $has_members_plugin ? current_user_can($required_permission) : current_user_can("level_7");
        if ($has_access)
            return $has_members_plugin ? $required_permission : "level_7";
        else
            return false;
    }

    //Returns the url of the plugin's root folder
    public static function get_base_url() {
        return plugins_url(null, __FILE__);
    }

    //Returns the physical path of the plugin's root folder
    public static function get_base_path() {
        $folder = basename(dirname(__FILE__));

        return WP_PLUGIN_DIR . "/" . $folder;
    }

    //*******************************************************


    /*
    Cycles through all entries, counts responses and returns an associative array with the data for each field. It's then optionally cached later according to the user settings.
    */
    public static function gpoll_get_data($form_id, $gpoll_data = array()) {
        $time_start         = microtime(true);
        $max_execution_time = 20; //seconds
        $form_meta          = RGFormsModel::get_form_meta($form_id);
        $form_meta          = apply_filters("gform_polls_form_pre_results_$form_id", apply_filters("gform_polls_form_pre_results", $form_meta));
        $totals             = RGFormsModel::get_form_counts($form_id);
        $total              = $totals["total"];

        $sort_field_number = 0;
        $sort_direction    = 'DESC';
        $search            = '';
        $offset            = 0;
        $page_size         = 200;
        $star              = null;
        $read              = null;
        $is_numeric_sort   = false;
        $start_date        = null;
        $end_date          = null;
        $status            = 'active';
        $field_counter     = 0;
        if (empty($gpoll_data)) {

            //first build list of fields to count and later count the entries
            //it's split up this way to avoid a timeout on large resultsets

            foreach ($form_meta["fields"] as $field) {

                $fieldid = $field["id"];

                if ($field["type"] !== "poll")
                    continue;

                $gpoll_field_data = array(
                    "field_label" => $field["label"],
                    "field_id"    => $fieldid,
                    "type"        => $field["type"],
                    "inputType"   => $field["inputType"]
                );

                $gpoll_data["fields"][$field_counter] = $gpoll_field_data;
                $gpoll_input_data                     = array();

                //for checkboxes
                if ($field["inputType"] == "checkbox") {
                    $input_counter = 0;
                    foreach ($field["inputs"] as $input) {
                        $inputid                                                        = str_replace(".", "_", $input["id"]);
                        $gpoll_input_data                                               = array(
                            "input_id" => "#choice_{$inputid}",
                            "label"    => $input["label"]
                        );
                        $gpoll_data["fields"][$field_counter]["inputs"][$input_counter] = $gpoll_input_data;
                        $input_counter += 1;
                    }
                } else {
                    //for radio & dropdowns

                    $choice_counter = 0;
                    if (isset($field["enableOtherChoice"]) && $field["enableOtherChoice"] === true) {
                        $choice_index                            = count($field["choices"]);
                        $field["choices"][$choice_index]["text"] = __("Other", "gravityformspolls");
                    }

                    foreach ($field["choices"] as $choice) {
                        $gpoll_input_data                                                = array(
                            "input_id" => "#choice_{$fieldid}_{$choice_counter}",
                            "label"    => $choice["text"]
                        );
                        $gpoll_data["fields"][$field_counter]["inputs"][$choice_counter] = $gpoll_input_data;
                        $choice_counter += 1;
                    }
                }
                $field_counter += 1;
                $i = $offset;

            }
        } else {
            $i = $gpoll_data["offset"];
            unset($gpoll_data["offset"]);
        }


        //get leads in groups of $page_size to avoid timeouts
        while ($i < $total) {
            $field_counter = 0;

            $get_leads_time_start = microtime(true);
            $entries              = RGFormsModel::get_leads($form_id, $sort_field_number, $sort_direction, $search, $i, $page_size, null, null, false, null, null);
            $get_leads_time_end   = microtime(true);
            $get_leads_time       = $get_leads_time_end - $get_leads_time_start;

            //loop through each field currently on the form and count the entries for each choice
            foreach ($form_meta["fields"] as $field) {

                $fieldid = $field["id"];

                if ($field["type"] !== "poll") {
                    continue;
                }

                if (isset ($gpoll_data["fields"][$field_counter]["total_entries"])) {
                    $field_total_entries = $gpoll_data["fields"][$field_counter]["total_entries"];
                } else {
                    $field_total_entries = 0;
                }

                foreach ($entries as $entry) {
                    $entry_value = RGFormsModel::get_lead_field_value($entry, $field);

                    if (false === empty($entry_value))
                        $field_total_entries++;
                }
                $gpoll_data["fields"][$field_counter]["total_entries"] = $field_total_entries;

                $gpoll_input_data = array();

                // checkboxes store entries differently to radio & dropdowns
                if ($field["inputType"] == "checkbox") {
                    //for checkboxes

                    // loop through all the choices and count the entries for each choice
                    $input_counter = 0;
                    foreach ($field["inputs"] as $input) {

                        // running total of entries for each set of entries
                        if (isset ($gpoll_data["fields"][$field_counter]["inputs"][$input_counter]["total_entries"])) {
                            $total_entries = $gpoll_data["fields"][$field_counter]["inputs"][$input_counter]["total_entries"];
                        } else {
                            $total_entries = 0;
                        }
                        $entry_index = 1;

                        // loop through all the entries and count the entries for the choice
                        foreach ($entries as $entry) {

                            // loop through each item in the lead object and pick out the entries for this field id
                            foreach ($entry as $key => $entry_value) {

                                // checkboxes store the key as [field number].[input index] (e.g. 2.1 or 2.2)
                                // so convert to integer to identify all the responses inside the lead object for this field id
                                if (intval($key) == $field["id"]) {
                                    //compare the user's response with the current choice
                                    if ($entry_value == $field["choices"][$input_counter]["value"]) {
                                        // found a response for this choice so continue to the next lead
                                        $total_entries++;
                                        break;
                                    }
                                }
                            }
                            $entry_index += 1;
                        }

                        //calculate the ratio of total number of responses counted to the total number of entries for this form
                        $ratio = 0;

                        if ($field_total_entries != 0) {
                            $ratio = round(($total_entries / $field_total_entries * 100), 0);
                        }

                        //store the data
                        $gpoll_data["fields"][$field_counter]["inputs"][$input_counter]["value"]         = $field["choices"][$input_counter]["value"];
                        $gpoll_data["fields"][$field_counter]["inputs"][$input_counter]["total_entries"] = $total_entries;
                        $gpoll_data["fields"][$field_counter]["inputs"][$input_counter]["ratio"]         = $ratio;
                        $input_counter += 1;
                    }
                } else {
                    // for radio & dropdowns

                    $choice_counter = 0;

                    // if the Enable "other" choice option is selected for this field then add it as a pseudo-value
                    if (isset($field["enableOtherChoice"]) && $field["enableOtherChoice"] === true) {
                        $choice_index                             = count($field["choices"]);
                        $field["choices"][$choice_index]["value"] = "gpoll_other";
                    }

                    // loop through each choice and count the responses
                    foreach ($field["choices"] as $choice) {

                        // running total of entries for each set of entries
                        if (isset ($gpoll_data["fields"][$field_counter]["inputs"][$choice_counter]["total_entries"])) {
                            $total_entries = $gpoll_data["fields"][$field_counter]["inputs"][$choice_counter]["total_entries"];
                        } else {
                            $total_entries = 0;
                        }

                        // count responses for "Other"
                        if ($choice["value"] == "gpoll_other") {
                            $possible_choices = array();
                            foreach ($field["choices"] as $possible_choice) {
                                array_push($possible_choices, $possible_choice["value"]);
                            }

                            foreach ($entries as $entry) {
                                $entry_value = RGFormsModel::get_lead_field_value($entry, $field);

                                if (!empty($entry_value) && !in_array($entry_value, $possible_choices))
                                    $total_entries++;
                            }

                        } else {

                            // count entries
                            foreach ($entries as $entry) {
                                $entry_value = RGFormsModel::get_lead_field_value($entry, $field);
                                if ($entry_value === $choice["value"])
                                    $total_entries++;

                            }
                        }

                        // calculate the ratio of total number of responses counted to the total number of entries for this form
                        $ratio = 0;
                        if ($field_total_entries != 0)
                            $ratio = round(($total_entries / $field_total_entries * 100), 0);


                        //store the data
                        $gpoll_data["fields"][$field_counter]["inputs"][$choice_counter]["value"]         = $choice["value"];
                        $gpoll_data["fields"][$field_counter]["inputs"][$choice_counter]["total_entries"] = $total_entries;
                        $gpoll_data["fields"][$field_counter]["inputs"][$choice_counter]["ratio"]         = $ratio;
                        $choice_counter += 1;
                    }
                }

                $field_counter += 1;
            }
            $i += $page_size;
            $time_end                     = microtime(true);
            $execution_time               = ($time_end - $time_start);
            $gpoll_data["execution_time"] = $execution_time;
            $gpoll_data["incomplete"]     = false;
            if ($execution_time + $get_leads_time > $max_execution_time) {
                $gpoll_data["incomplete"] = true;
                $gpoll_data["offset"]     = $i;
                break;
            }

        } //end while
        return $gpoll_data;
    } // end function gpoll_get_data


    // returns the results in an array of HTML formatted data
    public static function gpoll_get_results($formid, $display_field = "0" /* zero = all fields */, $style = "green", $show_percentages = true, $show_counts = true, $lead = array()) {

        $gpoll_output = array();
        $gpoll_data   = array();

        // each bar will receive this HTML formatting
        $bar_html = "<div class='gpoll_wrapper {$style}'><div class='gpoll_ratio_box'><div class='gpoll_ratio_label'>%s</div></div><div class='gpoll_bar'>";
        $bar_html .= "<span class='gpoll_bar_juice' data-origwidth='%s' style='width: %s%%'><span class='gpoll_bar_count'>%s</span></span></div></div>";


        // if data is cached then pull the data out of the cache

        if (false === ($gpoll_data = GFCache::get('gpoll_data_' . $formid))) {

            // cache has timed out so get the data again and cache it again
            $gpoll_data = self::update_cache($formid);
        }


        // build HTML output

        $gpoll_output["summary"] = "<div class='gpoll_container'>";
        $field_counter           = 0;

        // loop through polls data field by field
        foreach ($gpoll_data["fields"] as $field) {

            $fieldid = $field["field_id"];

            // only build html for the field(s) specified in the parameter. 0 = all fields
            if (is_array($display_field)) {
                if (false === in_array($fieldid, $display_field))
                    continue;
            } elseif ($display_field != "0" && $fieldid != $display_field) {
                continue;
            }


            // build 2 sections: summary and individual fields
            $field_number = $field_counter + 1;
            $gpoll_output["summary"] .= "<div class='gpoll_field'>";
            $gpoll_output["summary"] .= "<div class='gpoll_field_label_container'>";

            $gpoll_output["summary"] .= "<div class='gpoll_field_label'>";
            $gpoll_output["summary"] .= $field["field_label"] . "</div></div>";

            // the individual fields HTML was used in the past but not used now.
            // it was used to display results "inline" with the form (i.e. form input then the bar below)
            // I've left it because it may be useful either to designers or for a future use
            $gpoll_output["fields"][$field_counter]["field_id"] = $field["field_id"];
            $gpoll_output["fields"][$field_counter]["type"]     = $field["type"];

            $selected_values = array();

            // if the lead object is passed then prepare to highlight the selected choices
            if (!empty ($lead)) {
                $form_meta = RGFormsModel::get_form_meta($formid);
                // collect all the responses in the lead for this field
                $selected_values = self::get_selected_values($form_meta, $fieldid, $lead);

                //collect all the choices that are currently possible in the field

                $possible_choices = self::get_possible_choices($form_meta, $fieldid);

                $form_meta_field = RGFormsModel::get_field($form_meta, $fieldid);

                // if the "other" option is selected for this field
                // add the psuedo-value "gpoll_other" if responses are found that are not in the list of possible choices
                if (isset($form_meta_field["enableOtherChoice"]) && $form_meta_field["enableOtherChoice"] === true) {

                    foreach ($selected_values as $selected_value) {
                        if (!in_array($selected_value, $possible_choices))
                            array_push($selected_values, "gpoll_other");
                    }
                }
            }

            // loop through all the inputs in this field (poll data field not form object field) and build the HTML for the bar
            $input_counter = 0;
            foreach ($field["inputs"] as $input) {

                //highlight the selected value by adding a class to the label
                $selected_class = "";
                if (in_array($input["value"], $selected_values)) {
                    $selected_class .= " gpoll_value_selected";
                }

                //build the bar and add it to the summary
                $gpoll_output["summary"] .= sprintf("<div class='gpoll_choice_label%s'>%s</div>", $selected_class, $input["label"]);
                $ratio            = $input["ratio"];
                $value            = $input["value"];
                $count            = $show_counts === true ? $input["total_entries"] : '';
                $percentage_label = $show_percentages === true ? $ratio . '%' : '';
                $input_html       = sprintf($bar_html, $percentage_label, $ratio, $ratio, $count);
                $gpoll_output["summary"] .= $input_html;

                //add the bar HTML to the fields array ready to output alongside the summary
                $input_data                                                       = array(
                    "input_id"      => $input["input_id"],
                    "label"         => $input["label"],
                    "total_entries" => $input["total_entries"],
                    "ratio"         => $input["ratio"],
                    "bar_html"      => $input_html
                );
                $gpoll_output["fields"][$field_counter]["inputs"][$input_counter] = $input_data;

                $input_counter += 1;
            }
            $gpoll_output["summary"] .= "</div>";
            $field_counter += 1;
        }
        $gpoll_output["summary"] .= "</div>";

        return $gpoll_output;

    } //end function gpoll_get_results

    // collect all the responses in the lead for this field and returns an array
    public static function get_selected_values($form_meta, $fieldid, $lead) {

        $selected_values = array();

        //pick out the field we need from the fields collection in the form object
        //and add the selected values to the selected_values array
        if (is_array($form_meta["fields"])) {
            foreach ($form_meta["fields"] as $field) {
                if ($field["id"] == $fieldid) {
                    if ($field["inputType"] == "checkbox") {
                        for ($i = 1; $i <= count($field["inputs"]); $i++) {
                            $lead_index = 0;
                            $lead_index = $fieldid . "." . $i;
                            if (isset($lead[$lead_index]) && !empty($lead[$lead_index]))
                                array_push($selected_values, $lead[$lead_index]);
                        }
                    } else {
                        for ($i = 1; $i <= count($field["choices"]); $i++) {
                            $lead_index = $fieldid;
                            if (isset($lead[$lead_index]) && !empty($lead[$lead_index]))
                                array_push($selected_values, $lead[$lead_index]);
                        }
                    }
                    break;
                }
            }
        }

        return $selected_values;
    }

    public static function get_possible_choices($form_meta, $fieldid) {

        $possible_choices = array();

        //pick out the field we need from the fields collection in the form object
        //and add the possible choices to the possible_choices array
        if (is_array($form_meta["fields"])) {
            foreach ($form_meta["fields"] as $field) {
                if ($field["id"] == $fieldid) {
                    foreach ($field["choices"] as $possible_choice) {
                        array_push($possible_choices, $possible_choice["value"]);
                    }

                    return $possible_choices;
                }
            }
        }
    }


    public static function display_confirmation($confirmation, $form, $lead, $ajax) {
        $poll_fields          = GFCommon::get_fields_by_type($form, array('poll'));
        $display_confirmation = false;
        $display_results      = false;
        if (empty ($poll_fields))
            return $confirmation;

        $form_id = $form["id"];

        $override = false;

        $field_values = array();
        if (isset($_POST["gform_field_values"]))
            $field_values = wp_parse_args($_POST["gform_field_values"]);


        if (rgar($field_values, "gpoll_enabled") == "1") {

            $field_values      = wp_parse_args($_POST["gform_field_values"]);
            $show_results_link = rgar($field_values, "gpoll_show_results_link");
            $show_results_link = $show_results_link == "1" ? true : false;
            $style             = rgar($field_values, "gpoll_style");
            $percentages       = rgar($field_values, "gpoll_percentages");
            $percentages       = $percentages == "1" ? true : false;
            $counts            = rgar($field_values, "gpoll_counts");
            $counts            = $counts == "1" ? true : false;
            $cookie            = rgar($field_values, "gpoll_cookie");

            $display_results = rgar($field_values, "gpoll_display_results");
            $display_results = $display_results == "1" ? true : false;

            $display_confirmation = rgar($field_values, "gpoll_confirmation");
            $display_confirmation = $display_confirmation == "1" ? true : false;

            $checksum = rgar($field_values, 'gpoll_checksum');
            if ($checksum == self::generate_checksum($display_results, $show_results_link, $cookie, $display_confirmation, $percentages, $counts, $style))
                $override = true;

        }


        if (false === $override) {
            $style       = self::get_form_setting($form, "gpollStyle");
            $percentages = self::get_form_setting($form, "gpollShowPercentages");
            $counts      = self::get_form_setting($form, "gpollShowCounts");

            $display_results      = self::get_form_setting($form, "gpollDisplayResults");
            $display_confirmation = true;
        }

        $submitted_fields = array();
        foreach ($poll_fields as $field) {
            $field_id    = $field["id"];
            $entry_value = RGFormsModel::get_lead_field_value($lead, $field);
            if (is_array($entry_value))
                $entry_value = implode("", $entry_value);
            if (false === empty($entry_value))
                $submitted_fields[] = $field_id;
        }

        if ($display_confirmation && $display_results) {
            //confirmation message plus results

            //override confirmation if it's a redirect
            $str_pos = strpos($confirmation, 'gformRedirect');
            if (false !== $str_pos)
                $confirmation = "";

            //override in the case of headers already sent or ajax = true
            if (is_array($confirmation) && array_key_exists("redirect", $confirmation))
                $confirmation = "";

            $has_confirmation_wrapper = false !== strpos($confirmation, 'gform_confirmation_wrapper') ? true : false;

            if ($has_confirmation_wrapper)
                $confirmation = substr($confirmation, 0, strlen($confirmation) - 6); //remove the closing div of the wrapper

            $has_confirmation_message = false !== strpos($confirmation, 'gforms_confirmation_message') ? true : false;

            if ($has_confirmation_message)
                $confirmation = substr($confirmation, 0, strlen($confirmation) - 6); //remove the closing div of the message
            else
                $confirmation .= "<div id='gforms_confirmation_message' class='gform_confirmation_message_{$form_id}'>";

            $results = self::gpoll_get_results($form["id"], $submitted_fields, $style, $percentages, $counts, $lead);
            $confirmation .= $results["summary"] . "</div>";

            if ($has_confirmation_wrapper)
                $confirmation .= '</div>';

        } elseif (!$display_confirmation && $display_results) {

            //only the results without the confirmation message
            $results = self::gpoll_get_results($form["id"], $submitted_fields, $style, $percentages, $counts, $lead);

            $results_summary = $results["summary"];
            $confirmation    = sprintf("<div id='gforms_confirmation_message' class='gform_confirmation_message_{$form_id}'>%s</div>", $results_summary);

        } elseif (!$display_confirmation && !$display_results) {
            $confirmation = "<div id='gforms_confirmation_message' class='gform_confirmation_message_{$form_id}'></div>";
        }

        return $confirmation;
    } // end function gpoll_confirmation

    //displays the form and specifies hidden form values to enable and configure the poll.
    //if the cookie is already set then display the results.

    function poll_shortcode($string, $attributes, $content) {

        extract(shortcode_atts(array(
            'title'             => true,
            'description'       => true,
            'confirmation'      => false,
            'id'                => 0,
            'name'              => '',
            'field_values'      => "",
            'ajax'              => false,
            'disable_scripts'   => false,
            'tabindex'          => 1,
            'mode'              => 'poll',
            'field'             => 0,
            'style'             => 'green',
            'display_results'   => true,
            'show_results_link' => true,
            'percentages'       => true,
            'counts'            => true,
            'cookie'            => ''

        ), $attributes));


        $currentDate            = strtotime("now");
        $cookie                 = strtolower($cookie);
        $cookie_expiration_date = date(strtotime($cookie));

        $confirmation = strtolower($confirmation) == "false" ? false : true;
        if (!empty($cookie) && $cookie_expiration_date <= $currentDate) {
            return __("Gravity Forms Polls Add-on Shortcode error: Please enter a valid date or time period for the cookie expiration cookie_expiration_date: $cookie_expiration_date", "gravityformspolls");
        }

        $percentages       = strtolower($percentages) == "false" ? false : true;
        $counts            = strtolower($counts) == "false" ? false : true;
        $display_results   = strtolower($display_results) == "false" ? false : true;
        $show_results_link = strtolower($show_results_link) == "false" ? false : true;

        $title           = strtolower($title) == "false" ? false : true;
        $description     = strtolower($description) == "false" ? false : true;
        $ajax            = strtolower($ajax) == "true" ? true : false;
        $disable_scripts = strtolower($disable_scripts) == "true" ? true : false;

        $return = true;

        $poll_ui = self::build_poll_ui($id, $field, $style, $mode, $percentages, $counts, $title, $description, $confirmation, $show_results_link, $ajax, $cookie, $display_results, $field_values, $disable_scripts, $tabindex, $return);

        return $poll_ui;

    } // end function poll_shortcode

    public static function build_poll_ui($form_id, $field_id = 0 /* zero = all fields */, $style = "green", $mode = "poll", $percentages = true, $counts = true, $title = true, $description = true, $confirmation = false, $show_results_link, $ajax = false, $cookie = "", $display_results = true, $field_values = "", $disable_scripts = false, $tabindex, $return = true) {
        $form = RGFormsModel::get_form_meta($form_id);
        if (empty($form))
            return;

        GFPolls::$gpoll_add_scripts = true;

        if ($mode == "results") {

            $results = self::gpoll_get_results($form_id, $field_id, $style, $percentages, $counts);
            $output  = $results["summary"];


        } else {
            $checksum          = self::generate_checksum($display_results, $show_results_link, $cookie, $confirmation, $percentages, $counts, $style);
            $show_results_link = false === $show_results_link ? 0 : 1;

            $field_values = htmlspecialchars_decode($field_values);
            $field_values = str_replace("&#038;", "&", $field_values);

            $percentages     = $percentages === false ? 0 : 1;
            $counts          = $counts === false ? 0 : 1;
            $display_results = $display_results ? 1 : 0;

            if ($disable_scripts === false)
                RGForms::print_form_scripts($form, $ajax);


            if ($field_values != "") $field_values .= "&";
            $field_values .= "gpoll_enabled=1&gpoll_field={$field_id}&gpoll_style={$style}&gpoll_display_results={$display_results}&gpoll_show_results_link={$show_results_link}&gpoll_cookie={$cookie}&gpoll_confirmation={$confirmation}&gpoll_percentages={$percentages}&gpoll_counts={$counts}&gpoll_checksum={$checksum}";

            parse_str($field_values, $field_value_array);
            $field_value_array = stripslashes_deep($field_value_array);

            $output = RGForms::get_form($form_id, $title, $description, false, $field_value_array, $ajax, $tabindex);


        }

        if (false === $return)
            echo $output;
        else
            return $output;
    }

    private static function generate_checksum($display_results, $show_results_link, $cookie, $confirmation, $percentages, $counts, $style) {
        $checksum_vars = array($display_results, $show_results_link, $cookie, $confirmation, $percentages, $counts, $style);

        return wp_hash(crc32(base64_encode(serialize($checksum_vars))));

    }

    function poll_total_shortcode($atts, $content = null) {

        extract(shortcode_atts(array(
            'id' => '1',
        ), $atts));

        $totals = RGFormsModel::get_form_counts($id);
        $total  = $totals["total"];

        return $total;
    } // end function poll_total_shortcode


    //--------------   Automatic upgrade ---------------------------------------------------

    //Integration with ManageWP
    public static function premium_update_push($premium_update) {

        if (!function_exists('get_plugin_data'))
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        $update = GFCommon::get_version_info();
        if ($update["is_valid_key"] == true && version_compare(self::$version, $update["version"], '<')) {
            $plugin_data                = get_plugin_data(__FILE__);
            $plugin_data['type']        = 'plugin';
            $plugin_data['slug']        = self::$path;
            $plugin_data['new_version'] = isset($update['version']) ? $update['version'] : false;
            $premium_update[]           = $plugin_data;
        }

        return $premium_update;
    }

    //Integration with ManageWP
    public static function premium_update($premium_update) {

        if (!function_exists('get_plugin_data'))
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        $update = GFCommon::get_version_info();
        if ($update["is_valid_key"] == true && version_compare(self::$version, $update["version"], '<')) {
            $plugin_data         = get_plugin_data(__FILE__);
            $plugin_data['slug'] = self::$path;
            $plugin_data['type'] = 'plugin';
            $plugin_data['url']  = isset($update["url"]) ? $update["url"] : false; // OR provide your own callback function for managing the update

            array_push($premium_update, $plugin_data);
        }

        return $premium_update;
    }

    public static function flush_version_info() {
        require_once("plugin-upgrade.php");
        RGPollsUpgrade::set_version_info(false);
    }

    public static function plugin_row() {
        require_once("plugin-upgrade.php");

        if (!self::is_gravityforms_supported()) {
            $message = sprintf(__("Gravity Forms " . self::$min_gravityforms_version . " is required. Activate it now or %spurchase it today!%s", "gravityformspolls"), "<a href='http://www.gravityforms.com'>", "</a>");
            RGPollsUpgrade::display_plugin_message($message, true);
        } else {
            $version_info = RGPollsUpgrade::get_version_info(self::$slug, self::get_key(), self::$version);

            if (!$version_info["is_valid_key"]) {
                $new_version = version_compare(self::$version, $version_info["version"], '<') ? __('There is a new version of Gravity Forms Polls Add-On available.', 'gravityformspolls') . ' <a class="thickbox" title="Gravity Forms Polls Add-On" href="plugin-install.php?tab=plugin-information&plugin=' . self::$slug . '&TB_iframe=true&width=640&height=808">' . sprintf(__('View version %s Details', 'gravityformspolls'), $version_info["version"]) . '</a>. ' : '';
                $message     = $new_version . sprintf(__('%sRegister%s your copy of Gravity Forms to receive access to automatic upgrades and support. Need a license key? %sPurchase one now%s.', 'gravityformspolls'), '<a href="admin.php?page=gf_settings">', '</a>', '<a href="http://www.gravityforms.com">', '</a>') . '</div></td>';
                RGPollsUpgrade::display_plugin_message($message);
            }
        }
    }

    //Displays current version details on Plugin's page
    public static function display_changelog() {
        if ($_REQUEST["plugin"] != self::$slug)
            return;

        //loading upgrade lib
        require_once("plugin-upgrade.php");

        RGPollsUpgrade::display_changelog(self::$slug, self::get_key(), self::$version);
    }

    public static function check_update($update_plugins_option) {
        require_once("plugin-upgrade.php");

        return RGPollsUpgrade::check_update(self::$path, self::$slug, self::$url, self::$slug, self::get_key(), self::$version, $update_plugins_option);
    }

    private static function get_key() {
        if (self::is_gravityforms_supported())
            return GFCommon::get_key();
        else
            return "";
    }

    //---------------------------------------------------------------------------------------

} //end class GFPolls
