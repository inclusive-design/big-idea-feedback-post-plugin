# feedback-post-plugin
A WordPress plugin that allows feedback to be submitted as custom posts, and viewable by a custom user role.

## Who Can Submit Feedback?

This plugin enables visitors to a Wordpress site the ability to submit feedback to any registered user with the `Feedback Subscriber` role. Visitors do not need to register to submit feedback.

This creates a very easy way for visitors to give their input, however could create a way for spam and other unwanted content. Currently the submission form is secured with some validation techniques, and with a configurable, screen-reader accessible security question.

## Who Can See Visitor Submitted Feedback?

Feedback is only viewable by the Feedback Subscriber the visitor intends - they can only see their own feedback, and not the feedback intended for other Feedback Subscribers.

Site Editors and Administrators will also be able to view feedback through the Wordpress admin dashboard.

# Setting up feedback-post-plugin

This is the summary of the steps to set up the feedback-post-plugin.

1. Copy the `feedback-post-plugin` files to a directory called `feedback-post-plugin` in the Wordpress plugins directory (i.e. `./wordpress/wp-content/plugins/feedback-post-plugin/`).
2. Activate the Feedback Post plugin.
3. [Create a page using the Feedback Post Template](#feedback_form_setup)
4. [Create a "feedback submit success" Page](#success_page).
5. [Set up your feedback subscribers](#feedback_subscriber_setup).
6. [Add a link to the feedback archive to your site](#feedback_archive_link).

<a name="feedback_form_setup"></a>
## Create a page using the Feedback Post Template

To add a feedback form to your site, you will first need to create a new Page using the Feedback Post Template.

1. In the dashboard, add a new page. In the new page, choose "Feedback Post Template" under the Template drop-down in the Attributes panel.
2. Give a descriptive title which visitors will see. For example: "Give us feedback".
3. In the body of the page, you can give some additional details about the feedback process. For example: "Help us improve our products and services by filling out the following form."
4. Publish this new page.
5. Now that the feedback form is published, you can add it to your site's menu using the Appearance menu, or use the Page's Permalink and use this link somewhere on your site.

<a name="success_page"></a>
## Create a "feedback submit success" Page

When a visitor submits feedback successfully, they will be redirected to a page
confirming their submission - you will need to create this page.

1. In the Wordpress dashboard, create and publish a new Page which will serve as
the feedback submission success page. For example, the Page can say: "Feedback
submitted successfully" with some links back to the home page.
2. In the dashboard, under "Settings" > "Feedback Post Plugin Options", choose
the appropriate page for the "Submit Success Page" and save the changes.

<a name="feedback_subscriber_setup"></a>
## Set up your feedback subscribers

When the feedback-post-plugin is activated, it will create a new Wordpress user role called `Feedback Subscriber`. Users assigned this role will show up as possible recipients on the feedback submission form.

Registered users can be given this new role via the Users menu in the dashboard.

If you have an existing role you would rather use (i.e. you want to use the default 'Subscriber' instead), you can make that change by editing the `feedback-post-strings.php` file. See [Customization](#customization) below.

<a name="feedback_archive_link"></a>
## Add a link to the feedback archive to your site

The last piece in this configuration is to add a link to your site so that registered users who have the "Feedback Subscriber" role can view their feedback.

To do this, you use the PHP function `get_feedback_post_archive_link()` to get an HTML link to the Feedback.

The following example shows a link to Feedback Posts in the site navigation if the current user matches the Feedback Subscriber role:

```php
<ul>
    <?php
        echo $nav_menu;
        /*
        Check that the Feedback Post plugin is installed.
        If it is, then display a link to the Feedback Post
        archive if the user has privledges to view it.
        */
        if (function_exists ('is_feedback_post_role')) {
            if (is_feedback_post_role(wp_get_current_user())) {
                echo '<li>'.get_feedback_post_archive_link().'</li>';
            }
        }
    ?>
</ul>
```
<a name="customization"></a>
# Customization

Feedback Post Plugin uses the `feedback-post-strings.php` file to define some settings and text labels used. You can modify this file to suit your needs. The settings file contains:

* Error message strings
* Submission security question and answer
* Feedback role name configuration
* Feedback link text string

## Submission form security question

You can customize the form security question and answer to suit your needs and audience, but the question and answer should be simple enough that most users can answer without too much thinking, and still obscure enough to foil abuse.

The security question should also provide a useful hint that assists in answering the question.

Some example security questions you can use:
* "What is the second planet from the Sun? (hint: it starts with the letter V)"
* "What is the name of this website? (hint: it is located at the top of this page)"
* "What is the name of the fruit, that is also the name of its colour? (hint: rhymes with the word 'doorhinge')"

## Custom Feedback View Role

It is possible to change the Feedback Post plugin to use a different role for controlling viewing privileges. You might want to do this if:
* you want to use an existing role like the Subscriber or Editor role, or
* you want to use a different role all together

To define a different role, change the values for the variables `FEEDBACK_ROLE_NAME`, and `FEEDBACK_ROLE_DISPLAY_NAME` in the `feedback-post-strings.php` file.

In order for changes to the role to take effect, you will first need to deactivate and then activate the Feedback Post Plugin.

# License and Copyright

Copyright 2017 OCAD University

Licensed under the Educational Community License (ECL), Version 2.0 or the New BSD license. You may not use this file except in compliance with one these Licenses.

You may obtain a copy of the ECL 2.0 License and BSD License at
https://github.com/fluid-project/infusion/raw/master/Infusion-LICENSE.txt
