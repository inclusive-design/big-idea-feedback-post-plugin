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
 Feedback form labels and strings.
 */
 define('FORM_AUTHOR_LABEL', 'Your name (required).');
 define('FORM_AUTHOR_DESCRIPTION', 'You can use a nickname if you would like to remain anonymous.');
 define('FORM_BUSINESS_NAME_FIELDSET_LABEL', 'Business name (required)');
 define('FORM_BUSINESS_NAME_MENU_LABEL', 'Choose a business.');
 define('FORM_BUSINESS_NAME_MENU_DESCRIPTION', 'Who are you leaving feedback for?');
 define('FORM_BUSINESS_NAME_MENU_DEFAULT', 'Choose a business');
 define('FORM_BUSINESS_NAME_MENU_OTHER', 'Not listed');
 define('FORM_BUSINESS_NAME_OTHER_LABEL', 'If a business is not listed, please provide their contact information (i.e. business name, phone number, address)');
 define('FORM_MESSAGE_LABEL', 'Message (required).');
 define('FORM_MESSAGE_DESCRIPTION', 'Tell us about your experience or ideas.');
 define('FORM_AUTHOR_EMAIL_LABEL', 'Your email (optional).');
 define('FORM_AUTHOR_EMAIL_DESCRIPTION', 'Enter your email if you like the business to contact you regarding your feedback.');
 define('FORM_SECURITY_LABEL', 'Security question (required):');

/*
Error message strings used in the Feedback Post submission form. Customize as necessary. Refreshing the submission form should be enough to display any changes.
*/
define('ERROR_NAME', 'Enter your name.');
define('ERROR_EMAIL', 'The email entered appears to be improperly formatted. Please check the email address.');
define('ERROR_RECIPIENT_ID', 'Choose a different business. Specified business is not registered on this site.');
define('ERROR_NO_RECIPIENT', 'Choose a business, or enter information about the business.');
define('ERROR_MESSAGE', 'Feedback message should not be blank.');
define('ERROR_SECURITY', 'Security question answer is incorrect.');
define('SUBMISSION_ERROR', 'Message was not sent, please try again. If this problem persists, please contact example@example.com');
define('ERROR_OTHER_RECIPIENT', 'If a business is not listed, please provide their contact information.');

/*
Security question and answer.
*/
define('SECURITY_QUESTION', 'What is the second word in the phrase "big idea"? The answer has 4 letters, and is case-insensitive.');
define('SECURITY_ANSWER', 'idea');



/*
The name of the menu item associated with the feedback_post_type.

If you change this value, you will need to deactivate and reactivate the
plugin in order for the new value to take effect.
*/
define('FEEDBACK_POST_ADMIN_LINK_TEXT','Feedback Post Submissions');

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
Text used in the Feedback email notification to administrators.
*/
define('FEEDBACK_EMAIL_DESCRIPTION', 'New feedback has been posted on BIG IDeA. To manage this feedback, go to: <a href="https://bigidea.one/wp-admin/edit.php?post_type=feedback_post_type">bigidea.one/wp-admin/edit.php?post_type=feedback_post_type</a>');
?>
