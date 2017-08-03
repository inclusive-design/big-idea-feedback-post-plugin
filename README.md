# feedback-post-plugin
A WordPress plugin that allows feedback to be submitted as custom posts, and viewable by a custom user role.

# Add a link to the feedback

```
require_once ( plugin_dir_path( __FILE__ ) . 'feedback-post-settings.php' );


$user = wp_get_current_user();
if (function_exists('get_feedback_post_role')) {
    if ( in_array(get_feedback_post_role(), (array) $user->roles ) ) {
        echo '<li><a href="'.get_post_type_archive_link( 'feedback_post' ).'">Feedback Posts</a></li>';
    }
}
```
