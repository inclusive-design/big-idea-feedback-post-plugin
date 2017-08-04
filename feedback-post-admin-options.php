<?php

/**
 * The admin page for Feedback Post plugin.
 *
 * @package feedback_post_plugin
 */

 /*
 Copyright 2017 OCAD University

 Licensed under the Educational Community License (ECL), Version 2.0 or the New
 BSD license. You may not use this file except in compliance with one these
 Licenses.

 You may obtain a copy of the ECL 2.0 License and BSD License at
 https://github.com/fluid-project/infusion/raw/master/Infusion-LICENSE.txt
 */

add_action ('admin_menu', 'feedback_post_plugin_menu');
add_action ('admin_init', 'feedback_post_plugin_menu_init');

function feedback_post_plugin_menu() {
    add_options_page (
        'Feedback Post Plugin Options',
        'Feedback Post Plugin Options',
        'manage_options',
        'feedback-post-plugin',
        'create_feedback_post_plugin_options_page'
    );
}

/*
Specify what fields appear in the admin page.
*/
function feedback_post_plugin_menu_init() {
    register_setting (
        'feedback-post-plugin-settings-group',
        'feedback-post-plugin-settings',
        'feedback_post_plugin_settings_validate'
    );

    // Section containing the selector for choosing the feedback post success page.
    add_settings_section (
        'feedback-post-plugin-settings-section',
        'Settings for the Feedback Post Plugin',
        'feedback_post_plugin_settings_section_callback',
        'feedback-post-plugin'
    );

    // The selector for choosing the feedback post submission form success page.
    add_settings_field (
        'success-id',
        'Submit Success Page',
        'feedback_post_plugin_success_id_callback',
        'feedback-post-plugin',
        'feedback-post-plugin-settings-section'
    );
}

/*
Callback function for the settings section. Currently this is empty as there
is only 1 setting on the page and is fairly self descriptive.
 */
function feedback_post_plugin_settings_section_callback() {
    ?>

    <?php
}


/*
The form field for choosing feedback submit success page.
*/
function feedback_post_plugin_success_id_callback() {
    $settings = (array) get_option ('feedback-post-plugin-settings');

    $args = array (
        'selected'              => $settings['success_page_id'],
        'name'                  => 'feedback-post-plugin-settings[success_page_id]',
        'show_option_none'      => "Not specified"
    );

    wp_dropdown_pages($args);
}

/*
Lay out the feedback post settings page.
*/
function create_feedback_post_plugin_options_page() {
    ?>
    <section class="feedback-post-plugin-admin">
        <h1>Feedback Post Plugin Options</h1>

        <form action="options.php" method="POST">
            <?php
            settings_fields('feedback-post-plugin-settings-group');
            do_settings_sections('feedback-post-plugin');
            submit_button();
            ?>
        </form>
    </section>
    <?php
}

/*
Validate the input on the settings page and display error messages for every
error that occurs. The error messages are populated from
feedback-post-settings.php.
*/
function feedback_post_plugin_settings_validate($input) {
    $output = null; // the results to return.
    $error = null;
    $settings = (array) get_option ('feedback-post-plugin-settings');
    $input_success_id = $input['success_page_id'];

    if (!empty($input_success_id)) {
        $success_page = get_post($input_success_id);
        if ($success_page->post_status != 'publish') {
            $error = "Page chosen for Submit Success Page has not been published. Publish it so it is visible to the web. Reverting to last known value.";
        }
        if ($success_page->post_type != 'page') {
            $error = "Invalid page type. Reverting to last known value. Please choose from the items in the list.";
        }
    } else {
        $error = "Invalid page type. Reverting to last known value. Please choose from the items in the list.";
    }

    if (empty($error)) {
        // No errors.
        $output['success_page_id'] = $input['success_page_id'];
    } else {
        // If there's an error, set the success page ID back to the last known value.
        add_settings_error('feedback-post-plugin-settings', 'invalid-feedback-success-id', $error);
        $output['success_page_id'] = $settings['success_page_id'];
    }

    return ($output);
}

?>
