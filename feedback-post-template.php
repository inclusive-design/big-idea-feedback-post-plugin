<?php
/**
 * Page template for the business feedback form.
 *
 * To use this:
 * 1. Create a Page in your Wordpress instance called "Business Feedback Form".
 * 2. Note the name of the page and the filename of this template should match. For more information, see https://developer.wordpress.org/themes/template-files-section/page-template-files/
 * 3. The body / contents of this page can be blank, but it is recommended to provide instructions.
 * 4. Save the page.
 * 5. Go to yoursite.com/business-feedback-form/ to see the page with the feedback form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package a11y
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
                    <!-- TODO: pull redirect from the configuration -->
                    <input type='hidden' name='redirect_to' id='redirect_to' value='business-feedback-form-success/' />
                    <input type="submit">
                  </form>
                </div>

            </div><!-- .entry-content -->

        </article><!-- #post -->

        <?php endwhile; // end of the loop. ?>
    </main>
<?php
get_footer();
