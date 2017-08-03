<?php
require_once ( plugin_dir_path( __FILE__ ) . 'feedback-post-settings.php' );

//function to generate response
function feedback_post_form_generate_response($type, $messages){
    global $response;

    if ($type == 'success') {
    }
    else {
        $response = '<div class="feedback-post-error">Please check the following:<ul>';
        foreach ($messages as $message) {
            $response .= '<li>'.$message.'</li>';
        }
        $response .= '</ul></div>';
    }
}

function is_valid_recipient_id ($input) {
    $feedback_post_recipient = get_users(array('role'=>FEEDBACK_ROLE_NAME));
    foreach ($feedback_post_recipient as $recipient) {
        if ($input == $recipient->id) return true;
    }
    return false;
}

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

function feedback_post_submit_validation () {
    //array to store any error messages
    $error_messages  = array();

    // Sanitize the input.
    $form_values = strip_tags_from_post ();

    // Check the hidden field to see if it was filled in.
    // If it's filled in, then it is likely spam.
    if (strlen ($form_values['submission_check']) > 0) {
        $error_messages[] = SUBMISSION_ERROR;
    }

    // Check name not blank
    if (empty($form_values['feedback_post_author'])) {
        $error_messages[] = ERROR_NAME;
    }

    // Check email is valid form
    if (!empty($form_values['feedback_post_author_email']) && ! is_email($form_values['feedback_post_author_email'])){
        $error_messages[] = ERROR_EMAIL;
    }

    // Check that the input business ID matches an ID of a subscriber of the site.
    if (!is_valid_recipient_id ($form_values['recipient_id'])) {
        $error_messages[] = ERROR_RECIPIENT_ID;
    }

    // Check that message is not empty
    if (strlen($form_values['message_text']) == 0) {
        $error_messages[] = ERROR_MESSAGE;
    }

    // Check security
    if(strcasecmp($form_values['message_security'], SECURITY_ANSWER) !== 0) {
        $error_messages[] = ERROR_SECURITY;
    }

    if (count ($error_messages) >= 1) {
        feedback_post_form_generate_response("error", $error_messages);
    } else {
        // If passes all these checks, create a post.
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
            $error_messages[] = SUBMISSION_ERROR;
            feedback_post_form_generate_response("error", $error_messages);
        } else {
            $location = empty($_POST['redirect_to']) ? get_redirect_link() : $_POST['redirect_to'];
            wp_safe_redirect($location);
        	exit;
        }
    }
}


?>
