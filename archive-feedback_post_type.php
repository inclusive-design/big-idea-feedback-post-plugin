<?php
/*
 * Template for the archive that displays Feedback Posts for a Feedback Post
 * subscriber.
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

get_header();
get_sidebar();
?>

<main id="content" class="a11y-site-main columns">
<?php
/* Bit of a hack. Remove the "Archives:" string from the title. */
echo '<h1>'.str_replace('Archives: ','',get_the_archive_title().'</h1>');

/* default ouput - this is replaced if there are Feedback Posts. */
$output = '<p>'.NO_FEEDBACK.'</p>';

$user = wp_get_current_user();

/* Custom query to loop through feedback posts */
$query = array(
    'post_type' => 'feedback_post_type',
    'post_status' => array('any')
);
$loop = new WP_Query($query);

/* Check to see if the user is a feedback subscriber before doing anything. */
if ( $loop->have_posts() && in_array( FEEDBACK_ROLE_NAME, (array) $user->roles ) ){

    $output = ''; // replace the default string.

    /* Start the Loop */
    while ( $loop->have_posts() ) : $loop->the_post();
        $post_id = get_the_ID();
        $post_date = get_the_date( 'l F j, Y' );

        /*
        Only show the feedback that matches the subscriber's ID.
        */
        $feedback_post_recipient_id = get_post_meta ($post_id, 'feedback_post_recipient_id');
        if ( $feedback_post_recipient_id[0] == $user->ID ) {
            $author = get_post_meta ($post_id, 'feedback_post_author');
            $author_email = get_post_meta ($post_id, 'feedback_post_author_email');
            $output .= '<article><h2>'.the_title('','',false).'</h2>';
            $output .= '<p><strong>Date:</strong> '.$post_date.' at '.get_the_time().'<br/>';
            $output .= '<strong>Feedback Author:</strong> '.$author[0].'</br/>';
            $output .= '<strong>Author Email:</strong> '. (empty($author_email[0]) ? 'not entered' : $author_email[0]).'</p>';
            $output .= '<div><strong>Message: </strong><br/>'.get_the_content().'</div></article>';
        }
    endwhile;
    wp_reset_query();
    the_posts_pagination();
}
echo $output;
?>

</main>

<?php
get_footer();
