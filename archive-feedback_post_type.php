<?php
get_header();
get_sidebar();
?>

<main id="content" class="a11y-site-main columns">
<?php
the_archive_title( '<h1>', '</h1>' );

$user = wp_get_current_user();

// Custom query to loop through feedback posts
$query = array(
    'post_type' => 'feedback_post_type',
    'post_status' => array('any')
);
$loop = new WP_Query($query);

/* Check to see if the user is a subscriber before doing anything. */
if ( $loop->have_posts() && in_array( FEEDBACK_ROLE_NAME, (array) $user->roles ) ){


    /* default ouput - this is replaced if there are Feedback Posts. */
    $output = '<p>There is nothing here for you.</p>';

    /* Start the Loop */

    while ( $loop->have_posts() ) : $loop->the_post();
        $output = '';
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
            $output .= '<p><strong>Date:</strong> '.$post_date.' at '.get_the_time().'<br/><strong>Feedback Author:</strong> '.$author[0].'</br/><strong>Author Email:</strong> '.$author_email[0].'</p>';
            $output .= '<div><strong>Message: </strong><br/>'.get_the_content().'</div></article>';
            $user_feedback_post_count++;
        }
        echo $output;
    endwhile;
    wp_reset_query();
    the_posts_pagination();
}

if ($user_feedback_post_count == 0) {
   echo '';
}
?>

</main>

<?php
get_footer();
