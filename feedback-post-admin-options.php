<?php
/*
Copyright 2017 OCAD University

Licensed under the Educational Community License (ECL), Version 2.0 or the New
BSD license. You may not use this file except in compliance with one these
Licenses.

You may obtain a copy of the ECL 2.0 License and BSD License at
https://github.com/fluid-project/infusion/raw/master/Infusion-LICENSE.txt
*/

/*
Add a configuration page for the Feedback Post Plugin.
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

    // Section for choosing the success page
    add_settings_section (
        'feedback-post-plugin-settings-section',
        'Settings for the Feedback Post Plugin',
        'feedback_post_plugin_settings_section_callback',
        'feedback-post-plugin'
    );

    add_settings_field (
        'success-id',
        'Submit Success Page',
        'feedback_post_plugin_success_id_callback',
        'feedback-post-plugin',
        'feedback-post-plugin-settings-section'
    );
}

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
        'selected'              => $settings['page_id'],
        'name'                  => 'feedback-post-plugin-settings[page_id]',
        'show_option_none'      => "Not specified"
    );

    wp_dropdown_pages($args);
}

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

function feedback_post_plugin_settings_validate($input) {
    // validate the input
    $output = null;
    $settings = (array) get_option ('feedback-post-plugin-settings');
    $error = null;
    $input_success_id = $input['page_id'];
    if (!empty($input_success_id)) {
        $success_page = get_post($input_success_id);
        if ($success_page->post_status == 'publish' && $success_page->post_type == 'page') {
            $error = false;
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }

    if ($error != true) {
        $output['page_id'] = $input['page_id'];
    } else {
        // If there's an error, set the success page ID back to the last known value.
        add_settings_error('feedback-post-plugin-settings', 'invalid-feedback-success-id', 'You have entered an invalid success page. *' . $input['page_id'] .'*' );
        $output['page_id'] = $settings['page_id'];
    }

    return ($output);
}

?>
