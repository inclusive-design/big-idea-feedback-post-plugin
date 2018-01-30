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

require_once( plugin_dir_path( __FILE__ ) . 'feedback-post-strings.php' );
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
                  <form class="feedback-post-form" action="<?php the_permalink(); ?>" method="post">
                      <?php wp_nonce_field('form_submit','feedback_post_submit'); ?>

                    <label for="feedback_post_author">
                        <?php echo FORM_AUTHOR_LABEL; ?>
                    </label>
                    <span class="feedback-post-form-desc" id="feedback_post_author_desc">
                    <?php echo FORM_AUTHOR_DESCRIPTION;?>
                    </span>
                    <input type="text" name="feedback_post_author" value="<?php echo esc_attr($_POST['feedback_post_author']); ?>" aria-describedby="feedback_post_author_desc">




                    <fieldset>
                        <legend><?php echo FORM_BUSINESS_NAME_FIELDSET_LABEL; ?></legend>

                        <label for="recipient_id">
                            <?php echo FORM_BUSINESS_NAME_MENU_LABEL; ?>
                        </label>
                        <span class="feedback-post-form-desc" id="recipient_id_desc">
                            <em><?php echo FORM_BUSINESS_NAME_MENU_DESCRIPTION; ?></em>
                        </span>
                        <select id="business_select" name="recipient_id" aria-describedby="recipient_id_desc">
                            <optgroup label="Choose a recipient">
                                <option disabled selected value><?php echo FORM_BUSINESS_NAME_MENU_DEFAULT; ?></option>

                                <?php
                                /* Add "Not listed / Other" option to the list. */
                                echo '<option value="other"';
                                if (strcmp ('other', esc_attr($_POST['$recipient_id'])) == 0) {
                                    echo selected;
                                }
                                echo '>' . FORM_BUSINESS_NAME_MENU_OTHER . '</option>';

                                /*
                                Populate a Select menu with the registered business info.
                                */
                                $feedback_post_recipient = get_users(array('role'=>FEEDBACK_ROLE_NAME));
                                foreach ($feedback_post_recipient as $recipient) {
                                    /* Check that cimy user extra fields plugin is installed and use that metadata.
                                    Otherwise use the wordpress display name. */
                                    $option_text = '';
                                    if (function_exists ('get_cimyFieldValue')) {
                                        $option_text = get_cimyFieldValue($recipient->ID, 'BUSINESS_NAME');
                                        $option_text .= ' - '.get_cimyFieldValue($recipient->ID, 'ADDRESS');
                                        $option_text .= ', '.get_cimyFieldValue($recipient->ID, 'CITY');
                                        $option_text = cimy_uef_sanitize_content($option_text);
                                    } else {
                                        $option_text = $recipient->display_name;
                                    }

                                    echo '<option value="'.$recipient->ID.'"';
                                    if ($recipient->ID == esc_attr($_POST['$recipient_id'])) {
                                        echo ' selected';
                                    }
                                    echo '>' . $option_text . '</option>';
                                }
                                ?>
                            </optgroup>
                        </select>

                        <label for="other_business">
                            <?php echo FORM_BUSINESS_NAME_OTHER_LABEL; ?>
                            <textarea class="feedback-post-other-business" type="text" name="other_recipient"><?php echo esc_textarea($_POST['other_recipient']); ?></textarea>
                        </label>
                    </fieldset>

                    <label for="message_text" aria-labelledby="message_text_desc">
                        <?php echo FORM_MESSAGE_LABEL; ?>
                    </label>
                    <span class="feedback-post-form-desc" id="message_text_desc">
                        <em><?php echo FORM_MESSAGE_DESCRIPTION; ?></em>
                    </span>
                    <textarea class="feedback-post-message-text-area" type="text" name="message_text" aria-describedby="message_text_desc"><?php echo esc_textarea($_POST['message_text']); ?></textarea>

                    <label for="feedback_post_author_email">
                        <?php echo FORM_AUTHOR_EMAIL_LABEL ?>
                    </label>
                    <span class="feedback-post-form-desc" id="message_author_email_desc">
                        <em><?php echo FORM_AUTHOR_EMAIL_DESCRIPTION; ?></em>
                    </span>
                    <input type="text" name="feedback_post_author_email" value="<?php echo esc_attr($_POST['feedback_post_author_email']); ?>" aria-describedby="message_author_email_desc">

                    <label for="message_security">
                        <?php echo FORM_SECURITY_LABEL; ?>
                        <em><?php echo SECURITY_QUESTION; ?></em>
                        <input type="text" name="message_security" value="<?php echo esc_attr($_POST['message_security']); ?>">
                    </label>

                    <input class="feedback-post-form-hidden" type="text" name="submission_check">
                    <input class="feedback-post-submit float-center" type="submit" value="Submit Feedback">
                  </form>
                </div>

            </div><!-- .entry-content -->

        </article><!-- #post -->

        <?php endwhile; // end of the loop. ?>
    </main>
<?php
get_footer();
