<?php
/**
 * Functions to handle submission, validation and creation of Feedback Posts.
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

require_once ( plugin_dir_path( __FILE__ ) . 'feedback-post-settings.php' );

/*
Function to generate response. In this case it is to display error messages.

@param string $type The type of message to display.
@param array $messages An array of messages to display.
*/
function feedback_post_form_generate_response($type, $messages){
    global $response;

    if (strcmp($type, 'error') === 0) {
        $response = '<div class="feedback-post-error">Please check the following:<ul>';
        foreach ($messages as $message) {
            $response .= '<li>'.$message.'</li>';
        }
        $response .= '<li>Why are there always errors?</li>';
        $response .= '</ul></div>';
    }
}

/*
Helper function which checks a user id to see if that user has privileges to
view feedback_post_type content.

@param string $id The user id to check.
@return bool True if the user with the input ID has privileges. False otherwise.
*/
function is_valid_recipient_id ($id) {
    $feedback_post_recipient = get_users(array('role'=>FEEDBACK_ROLE_NAME));
    foreach ($feedback_post_recipient as $recipient) {
        if ($id == $recipient->id) return true;
    }
    return false;
}

/*
Helper function to strip tags from the items in the submission form.

@return array An array of sanitized submission form items.
*/
function strip_tags_from_post () {
    $output = array(
        'feedback_post_author' => wp_strip_all_tags($_POST['feedback_post_author']),
        'feedback_post_author_email' => wp_strip_all_tags($_POST['feedback_post_author_email']),
        'recipient_id' => wp_strip_all_tags($_POST['recipient_id']),
        'message_text' => wp_strip_all_tags($_POST['message_text']),
        'request_follow_up' => wp_strip_all_tags($_POST['request_follow_up']),
        'message_security' => wp_strip_all_tags($_POST['message_security']),
        'submission_check' => wp_strip_all_tags ($_POST['submission_check'])
    );
    return $output;
}

/*
A function which validates the submission form input. Generates a new
feedback_post_type if all input is valid, otherwise generates errors and no post
is created.
*/
function feedback_post_submit_validation () {

    /* Check the Post - on initial load it is empty, so no need to validate. */
    if (!empty($_POST)){
        /* array to store any error messages */
        $error_messages  = array();

        /* Sanitize the input. */
        $form_values = strip_tags_from_post ();

        /* Check the hidden field to see if it was filled in.
        If it's filled in, then it is likely spam. */
        if (strlen ($form_values['submission_check']) > 0) {
            $error_messages[] = SUBMISSION_ERROR;
        }

        /* Check name not blank */
        if (empty($form_values['feedback_post_author'])) {
            $error_messages[] = ERROR_NAME;
        }

        /* Check email is valid form */
        if (!empty($form_values['feedback_post_author_email']) && ! is_email($form_values['feedback_post_author_email'])){
            $error_messages[] = ERROR_EMAIL;
        }

        /* Check that the input business ID matches an ID of a subscriber of the site. */
        if (!is_valid_recipient_id ($form_values['recipient_id'])) {
            $error_messages[] = ERROR_RECIPIENT_ID;
        }

        /* Check that message is not empty */
        if (strlen($form_values['message_text']) == 0) {
            $error_messages[] = ERROR_MESSAGE;
        }

        /* Check security question answer */
        if(strcasecmp($form_values['message_security'], SECURITY_ANSWER) !== 0) {
            $error_messages[] = ERROR_SECURITY;
        }

        if (count ($error_messages) >= 1) {
            /* If there are errors, generate error messages. */
            feedback_post_form_generate_response("error", $error_messages);
        } else {
            /* If passes all these checks, create a post. */
            $meta = array (
                'feedback_post_recipient_id'=>$form_values['recipient_id'],
                'feedback_post_author'=>$form_values['feedback_post_author'],
                'feedback_post_author_email'=>$form_values['feedback_post_author_email']
            );

            $post_options = array (
                'post_content'=>$form_values['message_text'],
                'post_title'=>'Feedback from '. $form_values['feedback_post_author'],
                'post_type'=>'feedback_post_type',
                'post_status'=>'private',
                'meta_input'=>$meta);

            $insert_post_result = wp_insert_post ($post_options, true);

            if (is_wp_error ($insert_post_result)){
                /* Could not create the post, then throw an error. */
                $error_messages[] = SUBMISSION_ERROR;
                feedback_post_form_generate_response("error", $error_messages);
            } else {
                /* If post creation was successful, then redirect to the success page specified in the options. */
                $settings = (array) get_option ('feedback-post-plugin-settings');
                $success_page_id = $settings['success_page_id'];

                $location = empty($success_page_id) ? get_redirect_link() : get_permalink($success_page_id);
                wp_safe_redirect($location);
            	exit;
            }
        }
    }
}
?>
