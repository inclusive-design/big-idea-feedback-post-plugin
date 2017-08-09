<?php
/**
 * Template for displaying the public-facing Feedback Post form.
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

require_once( plugin_dir_path( __FILE__ ) . 'feedback-post-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . 'feedback-post-template-handler.php' );

feedback_post_submit_validation ();
get_header();
get_sidebar();
?>
    <main id="content" class="a11y-site-main columns">
        <?php while ( have_posts() ) : the_post(); ?>

          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
              </header>

              <div class="entry-content">
                <?php the_content(); ?>

                <div id="respond">
                  <?php echo $response; ?>
                  <form action="<?php the_permalink(); ?>" method="post">
                      <?php wp_nonce_field('form_submit','feedback_post_submit'); ?>

                    <label for="feedback_post_author">Your name (required):
                        <input type="text" name="feedback_post_author" value="<?php echo esc_attr($_POST['feedback_post_author']); ?>">
                    </label>

                    <label for="recipient_id">Feedback recipient (required):
                        <select name="recipient_id">
                            <optgroup label="Choose a recipient">
                            <?php
                                $feedback_post_recipient = get_users(array('role'=>FEEDBACK_ROLE_NAME));
                                foreach ($feedback_post_recipient as $recipient) {
                                    echo '<option value="'.$recipient->ID.'"';
                                    if ($recipient->ID == esc_attr($_POST['$recipient_id'])) {
                                        echo ' selected';
                                    }
                                    echo '>'.$recipient->display_name.'</option>';
                                }
                            ?>
                        </select>
                    </label>

                    <label for="message_text">
                        Message (required):
                        <textarea type="text" name="message_text"><?php echo esc_textarea($_POST['message_text']); ?></textarea>
                    </label>

                    <label for="feedback_post_author_email">
                        Your email (optional):
                        <em>Enter your email if you like the business to contact you regarding your feedback.</em>
                    </label>
                    <input type="text" name="feedback_post_author_email" value="<?php echo esc_attr($_POST['feedback_post_author_email']); ?>">

                    <label for="message_security">Security question (required):
                        <?php echo SECURITY_QUESTION; ?> <input type="text" name="message_security" value="<?php echo esc_attr($_POST['message_security']); ?>">
                    </label>

                    <input class="feedback-post-form-hidden" type="text" name="submission_check">
                    <input type="submit">
                  </form>
                </div>

            </div><!-- .entry-content -->

        </article><!-- #post -->

        <?php endwhile; // end of the loop. ?>
    </main>
<?php
get_footer();
