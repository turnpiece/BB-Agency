=== Contact Form 7 Honeypot ===
Tags: honeypot, antispam, captcha, spam, form, forms, contact form 7, contactform7, contact form, cf7, cforms, Contact Forms 7, Contact Forms, contacts
Requires at least: 2.8
Tested up to: 3.5
Stable tag: trunk
Contributors: DaoByDesign
Donate link: http://www.daobydesign.com/buy-us-a-coffee/

Contact Form 7 - Adds honeypot functionality to Contact Form 7 forms.

== Description ==

This simple addition to the wonderful <a href="http://wordpress.org/extend/plugins/contact-form-7/">Contact Form 7</a> plugin adds basic honeypot functionality to thwart spambots without the need for an ugly captcha.

The principle of a honeypot is simple -- <em>bots are stupid</em>. While some spam is hand-delivered, the vast majority is submitted by bots scripted in a specific (wide-scope) way to submit spam to the largest number of form types. In this way they somewhat blindly fill in fields, irregardless of whether the field should be filled in or not. This is how a honeypot catches the bot -- it introduces an additional field in the form that if filled out will cause the form not to validate.

Follow us on [Twitter](http://www.twitter.com/daobydesign) and on [Facebook](http://www.facebook.com/daobydesign) for updates and news.

Visit the <a href="http://www.daobydesign.com/free-plugins/honeypot-module-for-contact-form-7-wordpress-plugin">Contact Form 7 Honeypot plugin page</a> for support & additional information.

== Installation ==

1. Upload plugin files to your plugins folder <strong>or</strong> install using Wordpress' "Add Plugin" feature -- just search for "Contact Form 7 Honeypot"
1. Activate the plugin
1. Edit a form in Contact Form 7
1. Choose "Honeypot" from the Generate Tag dropdown. <em>Recommended: change the honeypot element's ID.</em>
1. Insert the generated tag anywhere in your form. The added field uses inline CSS styles to hide the field from your visitors.

== Frequently Asked Questions == 

= Will this module stop all my contact form spam? =

* Probably not. But it should reduce it to a level whereby you don't require any additonal spam challenges (CAPTCHA, math questions, etc.).

= Are honeypots better than CAPTCHAs? =

* This largely depends on the quality of the CAPTCHA. Unfortunately the more difficult a CAPTCHA is to break, the more user-unfriendly it is. This honeypot module was created because we don't like CAPTCHA's cluttering up our forms. Our recommendation is to try this module first, and if you find that it doesn't stop enough spam, then employ more challenging anti-spam techniques.

= What is the plugin license? =

* This plugin is released under a GPL license.

== Changelog ==

= 1.1 =
Small update for W3C compliance. Thanks <a href="http://wordpress.org/support/topic/plugin-contact-form-7-honeypot-not-w3c-compliant">Jeff</a>.

= 1.0.0 =
* Initial release.