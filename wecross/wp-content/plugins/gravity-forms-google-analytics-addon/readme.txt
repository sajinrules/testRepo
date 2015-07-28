=== Plugin Name ===
Contributors: MikevHoenselaar
Tags: gravityforms, ga, google analytics, pageview
Requires at least: 3.0.1
Tested up to: 3.9.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Google Analytics pageviews in Gravity Forms Text Confirmations and Multipage forms.

== Description ==

When using Gravity Forms and Google Analytics you want to measure conversion rates of forms.
When working with AJAX and Gravity Forms a lot of it isn't visible in a report in GA.
Thats where this plugin comes in.

####Confirmations####
When using a Page as Confirmation you can track that pretty easy in GA.
The page has a unique URL.
You want to use Text as Confirmation because you don't want visitors to leave the page the form is on.
When you choose Text as Confirmation in Gravity Forms you can't measure that in Google Analytics by default.

So you want to use an extra Pageview that we can track in Google Analytics.
This plugin makes that really easy for you.
You don't need any coding experience to get it working.
Just make sure you have a working Google Analytics script on your website.

The plugin supports Old notation (ga.js) and Universal Analytics (analytics.js).
It only displays input fields for forms from Gravity Forms that have the radio button Text selected.
Page and Redirects forms are shown but you can't add the extra URL.

####Multipage forms (since v0.7) ####
There are situations when you want to use multipage forms in Gravity Forms. Or multistep forms.
By default you don't know how far in the process used the form. If they abandon your website in step 3 you will never know.
With this plugin you can add custom URL's to use for every page.
In Google Analytics you can setup your funnels this way and have better insights where visitors have problems.

####Plugin is developed by Online Boswachters####
Online Boswachters is a Dutch online marketing agency at its core with a lot of development knowledge. We use that knowledge on a lot of WordPress sites as well. Visit [http://onlineboswachters.nl](http://onlineboswachters.nl) to see our portfolio of WordPress sites we manage and developed as well.

####Questions or feedback?####
You can always send an e-mail to mike@onlineboswachters.nl if something isn't working for you or if you have an idea to improve the plugin.

####You want to use this plugin but don't have Gravity Forms####
Gravity Forms is the best plugin out there for working with forms in WordPress.
You can start using Gravity Forms of course. Current pricing of Gravity Forms is $39.00, $99.00 and $199.00 USD. [Purchase Gravity Forms](http://vruc.ht/gravityforms) (affiliate link, we receive 20% of the license you buy.)

== Installation ==

1. Upload `gravityforms-ga` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the Settings page of this plugin. You find it as a submenu under Gravity Forms main menu.

You can also do it from within WordPress. There is an 'Add New' button on the Plugins WP Admin page. It saves you the download :).

! If you don't see the Add new plugin button on the Plugins page in your WP Admin there is a setting inside wp-config that prevents it.!

== Frequently Asked Questions ==

= Does it support Google Analytics for WordPress by Yoast? =

Yoast currently doesn't support Universal Analytics. If he does we make sure we check automatically if we have to use Universal Analytics or not.

= Will it support Contact Form 7? =

We are currently investigating if we can make the plugin available for Contact Form 7 as well.

= Do you support other languages than English? =

We are working on that :).

= We want to know which fields were filled in on every page, is that possible? =

We are working on that :).


== Screenshots ==


== Changelog ==
= 0.7.3 (4 december 2014) =
* FIX: __gaTracker support (used in WordPress SEO by Yoast)

= 0.7.2 (4 September 2014) =
* FIX: _gaq fix in JavaScript

= 0.7.1 (30 May 2014) =
* FIX: Removed CDATA in output script GF for Confirmations

= 0.7 (30 May 2014) =
* FIX: Some grammatical errors in the text
* UPDATE: Plugin now supports multipage forms
* UPDATE: Settings design is better now, easier to use

= 0.6.1 (28 April 2014) =
* FIX: GF detection bug

= 0.5/0.6/0.6.1 (28 April 2014) =
* Small fixes for escaping URL's

= 0.4 (24 April 2014) =
* Better explanation on plugin page on wordpress.org and removed some comments in the code

= 0.3 (24 April 2014) =
* Small text tweaks

= 0.1 (April 2014) =
* First version of the plugin
