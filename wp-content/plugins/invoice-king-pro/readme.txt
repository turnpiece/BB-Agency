=== Invoice King Pro ===
Contributors: ashdurham
Donate link: http://durham.net.au/donate/
Tags: invoice, invoicing, pdf, clients, revenue, money, themes, theme, dollars, paid, payment, receipt, clients, email
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 1.1.7
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Invoice King Pro makes invoicing simple.

== Description ==

--

Stay up-to-date with the latest by following [@kingproplugins on Twitter](http://twitter.com/kingproplugins), [KingProPlugins on Facebook](http://facebook.com/kingproplugins) or [King Pro Plugins on Google+](https://plus.google.com/b/101488033905569308183/101488033905569308183/about)

--

[Invoice King Pro](http://kingpro.me/plugins/invoice-king-pro/) makes creating and sending your invoices simple. The invoice creation interface gives you full control
over every word on your invoice. Choose a different theme to give your invoices the look, style and layout that you want. If there isn't a layout that
suits your needs, thats ok, it can be created! Themes are available on the [King Pro Plugins website](http://kingpro.me/plugins/invoice-king-pro/themes/).

The [Invoice King Pro](http://kingpro.me/plugins/invoice-king-pro/) settings area will allow you to set some data that will be consistent across all of your invoices, so that you don't need to enter them for every invoice
you create. Rest assured that if you need to change these details as a one-off, you can! Define your own columns for the invoice, no matter the theme/template/design.
Its your invoice, so you have control on what is on it. Once the columns have been defined, you set up the calculation for the invoice simply by assigning columns and 
operators. Finally assigning what column will calculate the subtotal column will find you on your way to creating and sending your invoices.

Client information is stored and editable in the system so next time you need to invoice them, it is even quicker to create your invoice. Just choose the existing client from
the dropdown and click the insert button which will populate all the client fields that the theme uses.

So you've created your invoice, "Now what?" you say. Watch your invoice transform into a PDF file that you can save and email manually or just check to make sure
the output meets your standards. Made a mistake in the details? No problem, the PDF is created on the fly so you can make a change and view your PDF again with the new
details.

Don't have the time to download your newly created PDF invoice and draft and email to your client with the invoice attached? [Invoice King Pro](http://kingpro.me/plugins/invoice-king-pro/) has you covered. Provided you
have entered and email address for the client you have attached to the invoice, you can email the invoice to your client straight from Wordpress! You can modify the details of
the email in the settings screen. Who it comes from and what email it comes from so that your client can simply reply to that email to contact you. Modify the subject
and body of the email to your liking. You can even insert invoice specific details into the subject and body of the email by using the variables listed! Suddenly you
have a dynamic invoicing email system at your finger tips!

Client requests a receipt? Guess what... [Invoice King Pro](http://kingpro.me/plugins/invoice-king-pro/) has your back! Simply change the invoice status to paid and email the client from the system again (you can download
the PDF again which will contain the paid status). [Invoice King Pro](http://kingpro.me/plugins/invoice-king-pro/) automatically adds a watermark on the document based on what you enter in the settings. Email the client
from the system with the invoice set to paid will send the paid message that you can also modify.

--

If you have any suggestions or would like to see a feature in the plugin, please let me know in the support forum.

Any issues you are having, I'd also love to know, so again, please let me know using the support forum.

--

--

[Check out the King Pro Plugins range](http://kingpro.me/)


== Installation ==

1. Download and unzip the zip file onto your computer
2. Upload the 'invoicekingpro' folder into the `/wp-content/plugins/` directory (alternatively, install the plugin from the plugin directory within the admin)
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Give the output folder write permissions on the server to enable the plugin to save your PDFs on generation
5. Set your details up in the settings area and define your columns
6. Save and define your calculation and subtotal column
7. Create your first invoice within the 'Invoices' section of the admin
8. View or email your invoice as a PDF via the options under the invoice in the "All Invoices" section.

--

Having Trouble? Get support either on the support forums here or at [@kingproplugins on Twitter](http://twitter.com/kingproplugins), [KingProPlugins on Facebook](http://facebook.com/kingproplugins) or [King Pro Plugins on Google+](https://plus.google.com/b/101488033905569308183/101488033905569308183/about)

--

== Frequently Asked Questions ==

= After activating this plugin, my site has broken! Why? =

Nine times out of ten it will be due to your own scripts being added above the standard area where all the plugins are included. If you move your javascript files below the function, "wp_head()" in the "header.php" file of your theme, it should fix your problem.

= I change my columns in the settings yet my existing invoices don't show the change. Why? =

This is done to protect your existing invoices from future updates. You may run your invoices with a specific set of information for a year then update the set which wouldn't match your old invoices. If this wasn't in place your old invoice data would be lost.

= Some fields I see used around the plugin don't exist on my invoice. How do I set them? =

Not all themes will use every field available in the system. For example, the default theme that comes with the plugin does not use the clients contact details (address, phone, email) on the invoice, nor does the due date field get used. Some of the other themes available may use these.

= I made a change to my invoice and viewed the PDF to see the update, but it hasn't changed. Why? =

If your viewing this in the browser then its highly possible that your viewing a cached version. You should get your new version simply by refreshing the page. Depending on what browser you use will determine how it displays the PDF to you. You shouldn't have this problem if you download it.

= I get an error saying the PDF can't be saved due to write permissions on the server. What do I do? =

The plugin needs your permission to save the PDFs you generate to the output folder in the plugins folder. To do this, you are required to update the outputs permissions to be writable. Please see [the wordpress help page](http://codex.wordpress.org/Changing_File_Permissions) to carry this out

--

Have a question thats not listed? Get support either on the support forums here or at [@kingproplugins on Twitter](http://twitter.com/kingproplugins), [KingProPlugins on Facebook](http://facebook.com/kingproplugins) or [King Pro Plugins on Google+](https://plus.google.com/b/101488033905569308183/101488033905569308183/about)

--

== Screenshots ==

1. Settings page filled in with example content
2. Invoice list screen
3. Create new invoice with default data populated from settings
4. Populated invoice
5. Client list screen
6. Client add/edit screen
7. Example output PDF using "Default" theme

== Changelog ==

= 1.1.7 =
* Emergency fix to PDF creation

= 1.1.6 =
* Fix to date output after save
* Styling updates

= 1.1.5 =
* Fix to missing invoice_type variable in email
* Addition of default TAX/GST value setting in admin

= 1.1.4 =
* Added functionality to populate date field based when creating
* Date invoice was last sent information added
* Added variable support in open content fields 1 and 2
* CSS update for latest version of WP
* Update to KPP logo

= 1.1.3 =
* Made totals labels editable within admin
* Converted text to enable translations
* Update to client dropdown display amount

= 1.1.2 =
* Update to KPP section with release of new plugin
* Created local copy of Font Awesome as requested by Wordpress
* Added ability to control filename output
* CSS Tweaks

= 1.1.1 =
* Fix to column creation error
* Fix to email PDF code

= 1.1 =
* Major styling update to settings page
* Update to KPP Page styling and layout
* Major update to how columns are set up
* Version checking of paid themes
* Addition of hooks to enable addons

= 1.0.10 =
* Update to KPP page with new plugin details
* Added option to BCC yourself into emailed correspondence
* Added notice to inform if an email was sent successfully or not
* Enqueue script updated to only include scripts on required pages

= 1.0.9 =
* Updated details including new release of King Pro Plugin
* Changed location of settings page in menu.
* Addition of auto generated invoice numbers (courtesy of [Mark](http://profiles.wordpress.org/mstaaij/))

= 1.0.8 =
* Small fix to save client code

= 1.0.7 =
* Small fix to install/update code
* Update to links to follow to new website

= 1.0.6 =
* Cosmetic images in admin (CSS/Icons/etc)

= 1.0.5 =
* Modified code to save newly added rows correctly

= 1.0.4 =
* Updated code to render special characters (£ is the example) correctly in PDF

= 1.0.3 =
* Added field to enable the ability to add a currency symbol to the PDF values

= 1.0.2 =
* Fix to allow slashes, single quotes and double quotes to work in row fields

= 1.0.1 =
* Major oversight regarding emailing out Invoices to clients

= 1.0 =
* Initial

== Upgrade Notice ==

= 1.1.7 =
* Emergency fix to PDF creation

= 1.1.6 =
* Fix to date output after save
* Styling updates

= 1.1.5 =
* Fix to missing invoice_type variable in email
* Addition of default TAX/GST value setting in admin

= 1.1.4 =
* Added functionality to populate date field based when creating
* Date invoice was last sent information added
* Added variable support in open content fields 1 and 2
* CSS update for latest version of WP

= 1.1.3 =
* Made totals labels editable within admin
* Converted text to enable translations
* Update to client dropdown display amount

= 1.1.2 =
* Update to KPP section with release of new plugin
* Created local copy of Font Awesome as requested by Wordpress
* Added ability to control filename output
* CSS Tweaks

= 1.1.1 =
* Fix to column creation error
* Fix to email PDF code

= 1.1 =
* Major styling update to settings page
* Update to KPP Page styling and layout
* Major update to how columns are set up
* Version checking of paid themes
* Addition of hooks to enable addons

= 1.0.10 =
* Update to KPP page with new plugin details
* Added option to BCC yourself into emailed correspondence
* Added notice to inform if an email was sent successfully or not
* Enqueue script updated to only include scripts on required pages

= 1.0.9 =
* Updated details including new release of King Pro Plugin
* Changed location of settings page in menu.
* Addition of auto generated invoice numbers (courtesy of [Mark](http://profiles.wordpress.org/mstaaij/))

= 1.0.8 =
* Small fix to save client code

= 1.0.7 =
* Small fix to install/update code
* Update to links to follow to new website

= 1.0.6 =
* Cosmetic images in admin

= 1.0.5 =
* Modified code to save newly added rows correctly

= 1.0.4 =
* Updated code to render special characters (£ is the example) correctly in PDF

= 1.0.3 =
* Added field to enable the ability to add a currency symbol to the PDF values

= 1.0.2 =
* Fix to allow slashes, single quotes and double quotes to work in row fields

= 1.0.1 =
* Major oversight regarding emailing our invoices to clients - PLEASE UPGRADE IMMEDIATELY

= 1.0 =
* Gotta start somewhere