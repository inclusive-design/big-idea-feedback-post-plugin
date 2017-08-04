<?php
/**
 * Plugin Name: Feedback Post Plugin
 * Plugin URI:
 * Description: Allows visitors to submit feedback as posts visible to a custom user role.
 * Version: 0.0.1
 * Author: Floe Project
 * Author URI: http://floeproject.org
 * License: New BSD license or the Educational Community License, Version 2.0
 *
 * FeedbackPostPluginTemplate portion of this template based on Page
 * Template Plugin : 'Good To Be Bad'
 * http://www.wpexplorer.com/wordpress-page-templates-plugin/
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
require_once( plugin_dir_path( __FILE__ ) . 'feedback-post-admin-options.php' );

class FeedbackPostPluginTemplate {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new FeedbackPostPluginTemplate();
        }

        return self::$instance;

    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

            // 4.6 and older
            add_filter(
                'page_attributes_dropdown_pages_args',
                array( $this, 'register_project_templates' )
            );

        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                'theme_page_templates', array( $this, 'add_new_template' )
            );

        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );


        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );


        // Add your templates to this array.
        $this->templates = array(
            'feedback-post-template.php' => 'Feedback Post Template',
        );

    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {

        // Get global post
        global $post;

        // Return template if post is empty
        if ( ! $post ) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[get_post_meta(
            $post->ID, '_wp_page_template', true
        )] ) ) {
            return $template;
        }

        $file = plugin_dir_path( __FILE__ ). get_post_meta(
            $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if ( file_exists( $file ) ) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;

    }

}
add_action( 'plugins_loaded', array( 'FeedbackPostPluginTemplate', 'get_instance' ) );

/**
 * Enqueue the plugin styles.
 */
function feedback_post_plugin_styles () {
    wp_register_style ('feedback-post-plugin-style', plugin_dir_url(__FILE__) . 'feedback-post-plugin.css');
    wp_enqueue_style ('feedback-post-plugin-style');
}
add_action ('wp_enqueue_scripts', 'feedback_post_plugin_styles');

/**
 * Add a new custom role which determines if a user is allowed to see the
 * feedback posts.
 */
function feedback_post_view_role_activation () {
    $args = array (
        'read' => true,
        'delete_posts' => false,
    );
    add_role (FEEDBACK_ROLE_NAME, FEEDBACK_ROLE_DISPLAY_NAME, $args);
    // TODO: check to see that add_role was successful.
}
register_activation_hook (__FILE__,'feedback_post_view_role_activation');

/**
 * Create a custom post type to store the input from the feedback form.
 */
function create_feedback_post_type() {
    if (!post_type_exists('feedback_post_type')) {
        register_post_type( 'feedback_post_type',
            array(
                'labels' => array(
                    'name' => FEEDBACK_POST_MENU_NAME, // use the value specified in the feedback-post-settings.php file
                    'singular_name' => 'Feedback Post',
                    'add_new_item' => 'Add New Feedback Post',
                    'menu_name' => 'Feedback'
                ),
                'public' => true,
                'has_archive' => true,
                'show_in_menu' => true,
                'rewrite' => array ('slug' => 'feedback_post_type')
            )
        );
        flush_rewrite_rules();
    }
}
add_action( 'init', 'create_feedback_post_type' );

/**
 * Add the meta boxes for the custom post type.
 */
function feedback_post_meta_init () {
    add_meta_box(
        "feedback_post_recipient_id",
        "Recipient ID",
        "feedback_post_recipient_id_callback",
        "feedback_post_type",
        "normal",
        "low"
    );
    add_meta_box(
        "feedback_post_author",
        "Feedback Author",
        "feedback_post_author_callback",
        "feedback_post_type",
        "normal",
        "low"
     );

     add_meta_box(
         "feedback_post_author_email",
         "Feedback Author Email",
         "feedback_post_author_email_callback",
         "feedback_post_type",
         "normal",
         "low"
      );
}
add_action("admin_init", "feedback_post_meta_init");

/*
Callback function to display the name of the feedback recipient.
The value can not be modified.
*/

function feedback_post_recipient_id_callback() {
    /*
    TODO: Future improvement would be to make the input a select so it can be changed.
    */
    global $post;
    $custom = get_post_custom($post->ID);
    $feedback_post_recipient = get_userdata($custom['feedback_post_recipient_id'][0]);
    ?>
    <label>Feedback recipient: </label>
    <input disabled="disabled" name="feedback_post_recipient_id" value="<?php echo $feedback_post_recipient->display_name;?>"/>
    <?php
}

/*
Callback function to display the author's name.
*/
function feedback_post_author_callback() {
    global $post;
    $custom = get_post_custom($post->ID);
    $author = $custom['feedback_post_author'][0];
    ?>
    <label>Feedback author: </label>
    <input name="feedback_post_author" value="<?php echo $author;?>"/>
    <?php
}

/*
Callback function to display the author's email.
*/
function feedback_post_author_email_callback() {
    global $post;
    $custom = get_post_custom($post->ID);
    $author_email = $custom['feedback_post_author_email'][0];
    ?>
    <label>Feedback author email: </label>
    <input name="feedback_post_author_email" value="<?php echo $author_email;?>"/>
    <?php
}

/*
Save any changes made to the meta. Currently only supports changing of author
and author email.
*/
function feedback_post_save_callback(){
    global $post;
    update_post_meta ($post->ID, "feedback_post_author", $_POST["feedback_post_author"]);
    update_post_meta ($post->ID, "feedback_post_author_email", $_POST["feedback_post_author_email"]);
    /*
    TODO: Future improvement would be to save changes to the feedback recipient.
    */
}
add_action ('save_post', 'feedback_post_save_callback');

/*
Function which returns the user role which has privleges to view feedback_post_type.
FEEDBACK_ROLE_NAME is defined in feedback-post-settings.php.
*/
function get_feedback_post_role() {
    return FEEDBACK_ROLE_NAME;
}

/*
Function which defines a custom archive page for feedback_post_type.
*/
function get_custom_post_type_template( $archive_template ) {
     global $post;

     if ( is_post_type_archive ( 'feedback_post_type' ) ) {
          $archive_template = dirname( __FILE__ ) . '/archive-feedback_post_type.php';
     }
     return $archive_template;
}
add_filter( 'archive_template', 'get_custom_post_type_template' ) ;

?>
