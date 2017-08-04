<?php
/**
 * Settings (mostly strings) used for the Feedback Post plugin.
 *
 * Customize as necessary.
 *
 * @package feedback_post_plugin
 */

/**
 * Copyright 2017 OCAD University
 *
 * Licensed under the Educational Community License (ECL), Version 2.0 or the New
 * BSD license. You may not use this file except in compliance with one these
 * Licenses.

 * You may obtain a copy of the ECL 2.0 License and BSD License at
 * https://github.com/fluid-project/infusion/raw/master/Infusion-LICENSE.txt
 */

/*
Strings used in the Feedback Post submission form. Customize as necessary.
Refreshing the submission form should be enough to display any changes.
*/
define('ERROR_NAME', 'Enter your name.');
define('ERROR_EMAIL', 'Use a valid email address.');
define('ERROR_RECIPIENT_ID'  , 'Choose a different recipient. Specified recipient is not registered on this site.');
define('ERROR_MESSAGE', 'Feedback message should not be blank.');
define('ERROR_SECURITY', 'Security question answer is incorrect.');
define('SUBMISSION_ERROR', 'Message was not sent, please try again. If this problem persists, please contact David Pereyra at info@idrc.ocadu.ca');
define('SECURITY_QUESTION', 'What is the name of this website? (hint: answer has two words)');
define('SECURITY_ANSWER', 'big idea');

/*
Strings used on the Feedback archive page.
*/
define('NO_FEEDBACK','There is no feedback for you.');

/*
The default role name. Feedback posts are only viewable to users with this
role.

It is recommended you keep the default value, unless you want to assign
Feedback Post view privileges to a different role.

If you change these values, you will need to deactivate and reactivate the
plugin in order for the new values to take effect.
*/
define('FEEDBACK_ROLE_NAME', 'feedback_role_name');
define('FEEDBACK_ROLE_DISPLAY_NAME', 'Feedback Subscriber');

/*
The name of the menu item associated with the feedback_post_type.

If you change this value, you will need to deactivate and reactivate the
plugin in order for the new value to take effect.
*/
define('FEEDBACK_POST_MENU_NAME','Visitor Feedback');
?>
