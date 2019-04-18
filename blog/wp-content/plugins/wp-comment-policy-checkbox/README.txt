=== WP Comment Policy Checkbox ===
Contributors: fcojgodoy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C7M43R6RDXRBG
Tags: comments, privacy policy, checkbox, GDPR, customized text
Requires at least: 3.0.1
Tested up to: 5.0.2
Stable tag: trunk
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a checkbox and custom text to the comment forms so that the user can be informed and give consent to the web's privacy policy. And save this consent in the database.

== Description ==

Add a checkbox to the comment forms so that the user can give consent to the web's privacy policy. And save this consent in the database.

= Features =

- Add a required privacy policy checkbox to the default WordPress comment forms, with a link to you privacy policy page, opening in new tab.
- Optionally, you can display a customized text before the checkbox.
- Allowed an external link as a page of the privacy policy.
- The consent is stored in the database, in `wp_commentmeta` table with the metakey `wpcpc_private_policy_accepted`, and the commentator's email as value.
- The consent is exported by WordPress's Export Personal Data function.
- The consent is erased by WordPress's Erase Personal Data function.

= Use =

You can configure the plugin in the Discussion Settings on your WordPress administration.

= Theme compatibility =

The plugin only works if the theme uses the native WordPress function for comment forms.
Also, the plugin creates a concrete HTML structure to print the checkbox. Not in all theme will be displayed correctly. In that case, you could use the 'Additional CSS' box in the Customize of your theme.

= Contributing =

- Active development of this plugin is handled on [GitLab](https://gitlab.com/fcojgodoy/wp-comment-policy-checkbox)
- Translation of the plugin into different languages is on [the translation page](https://translate.wordpress.org/projects/wp-plugins/wp-comment-policy-checkbox).

= Donation =

If you enjoy using this plugin and find it useful, please consider making a donation in [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C7M43R6RDXRBG).
Thank you!

== Installation ==

Use the installer via back-end of your install or:
1. Unpack the download-package.
2. Upload the files to the /wp-content/plugins/ directory.
3. Activate the plugin through the Plugins menu in WordPress.

== Screenshots ==

1. Setting fields on comments setting page.
2. Basic policy information, policy checkbox and link printed on the bottom of the comment form, before the submit button.
2. Error message if the policy checkbox is not checked.


== Frequently Asked Questions ==

= I installed the plugin correctly, put in the text & set the privacy policy page, but the checkbox & text does not appear below the comment box =

The plugin only works if the theme uses the native WordPress function for comment forms. Your theme is probably not compatible with this plugin.

== Changelog ==

= 0.3.1 =
* [New Feature] Allowed an external link as a page of the privacy policy.
* Improved the translatability of the text string of the privacy policy notice.
* Fixed HTML markup error.
* Changed 'error' to 'warning' in the unchecked checkbox warning message.

= 0.3.0 =
* [New Feature] Store, export and erase the commentator's consent.

= 0.2.5 =
* Fix grammar mistake
* Margin right on input
* Fix attribute _blank error

= 0.2.4 =
* Fix translatable text

= 0.2.3 =
* Update pot translate file

= 0.2.2 =
* Improve gettext strings

= 0.2.1 =
* Improve gettext strings
* Small font for policy basic info

= 0.2.0 =
* Improve code
* Add id for accessibility to checkbox input
* Force checkbox appearance
* NEW! Optionally, you can display a customized text before the checkbox.

= 0.1.7 =
* Add 'GDPR' tag to plugin.
* Fix readme errors.

= 0.1.6 =
* Fix error occurred when user is logged in.

= 0.1.5 =
* Change languages file names.

= 0.1.4 =
* Fix file names.

= 0.1.3 =
* Fix Internationalization.

= 0.1.2 =
* Fix generic define names.

= 0.1.1 =
* Modified main file Description on head comment.

= 0.1 =
* First version, still in beta.
