=== Better Admin Pointers ===
Contributors: ssuess,
Tags: admin pointers, help
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40c%2esatoristephen%2ecom&lc=US&item_name=Stephen%20Suess&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows creation and placement of Admin Pointers (those little blue help boxes) on any page or post, including custom post types. 

== Description ==
This plugin will allow creation of admin pointers on any screen in the WordPress admin area, or any front end page, or on all pages. It creates a custom post type called Pointers to store information.  You need to add the following info to make it work:

1. **Title** - the part in the blue title bar
2. **Main** content area 
3. **Pointer id** - A unique id so that it can be tracked in the WP DB as dismissed
4. **Screen** (or Page/Post ID) - What page/screen it should appear on (if in admin) or what is the post ID (if showing on the front end). OR you have the ability to show on all admin or all front end pages (using ALL_ADMIN or ALL_FRONT)
5. **Target** - CSS id or class we want the pointer to attach to on the screen or post above
6. **Position Edge** - Which edge should be adjacent to the target? (left, right, top, or bottom)
7. **Position Align** - How should the pointer be aligned on this edge, relative to the target? (top, bottom, left, right, or middle)
8. (OPTIONAL) **Nudge Horizontal** - How much should we nudge the pointer horizontally? (Value in pixels. ex: -50, from edge value above, only works if edge above is left or right)
9. (OPTIONAL) **Nudge Vertical** - How much should we nudge the pointer vertically? (Value in pixels. ex: -50, from align value above, only works if align above is top or bottom)
10. (OPTIONAL) **Z-index** - What should the depth of the pointer be along the z-axis (in case you want some of them to sit higher/lower than others or higher/lower than other elements on the page)


**EXAMPLE:**
Let's say I want to add a pointer on the edit plugin page that tells a user to notice which plugin they are editing. I would use:

1. "Which Plugin am I Editing?"
2. "This is the file you are editing, duh"
3. "ss_editplugs"
4. "plugin-editor"
5. ".fileedit-sub"
6. "top"
7. "right"
8. "-50"
9. "-5"
10. "50000"

NOTE: This will only work for logged in users, whether on front end or back end.

NOTE: I also have a plugin that does help tabs, if you are interested in that one you can find it here: http://wordpress.org/plugins/better-admin-help-tabs/

This plugin leverages the great work done by others here:

For configuring metaboxes on the custom post type:
https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress

For base class and code to allow pointers to display properly:
https://gist.github.com/brasofilo/6947539


== Installation ==
It is always a good idea to make a backup of your database before installing any plugin.

There are 3 ways to install this plugin:

1. Search for it in your WordPress Admin (Plugins/Add New/Search) area and install from there

2. Download the zip file from http://wordpress.org/plugins/better-admin-pointers/ and then go to Plugins/Add New/Upload and then upload and activate it.

3. Upload the folder "better-admin-pointers" to "/wp-content/plugins/", then activate the plugin through the "Plugins" menu in WordPress




== Frequently Asked Questions ==
= Q: How can I find the screen/page id name to use? =
A: This can be easily deduced from looking at the URL in the admin. For regular posts, it would just be "post". For a custom post type, it would be the name of that custom post type (my-custom-post-type). For other pages (like my plugin editor example), it usually works to just remove the ".php" from the end of the url (i.e. "plugin-editor.php" becomes "plugin-editor").

= Q: Is there some handy reference somewhere for the main admin screen ids? =
A: You are in luck: <http://codex.wordpress.org/Plugin_API/Admin_Screen_Reference>

= Q: I'm the lazy type, is there some tool to help me identify admin screens? =
A: You are in even greater luck. I just added an option to show you what screen you are on anywhere in the admin. Go to BAP Options page and check the box for "Show Current Screen". A small header on every page will identify your admin screen.

= Q: How can I find what to target (i.e. how to know what to put in for target to tell the system where to place my pointers on the page)? =
A: Using built in tools in Chrome or Safari or Firefox or Other, right click on the element you want to target, select "Inspect Element" from the contextual menu, then look for the class or id of that item. Note, if it is a class, prepend with a period (.classname) and if it is an ID prepend with a hash (#idname).

= Q: How can I target just the "New" post page, and not have my pointer show up on the "Edit" post page? =
A: You will find that each type of page has its own base css, so for example if I am wanting to target the Add Media button on just the new post, I would use ".post-new-php #insert-media-button" as my target.

= Q: Sometimes the boxes are slightly offset from where I want them, is there any way to correct for that? =
A: There is now (as of version 1.2). Use the newly added Nudge Vertical and Nudge Horizontal fields. You can leave these fields blank for default, or use positive or negative numbers only. Don't put any other text in those boxes or bad things may happen.




== Screenshots ==
1. The config page for my example pointer.
2. The example pointer in action.


== Changelog ==
= Version 2.0 =
* NEW FEATURE: Ability to set Z-axis of each pointer
* NEW FEATURE: Ability of pointers to show on front end of site as well as in admin
* NEW FEATURE: Ability to have pointers show on every page of admin or every page of front end
* BUGFIX: fixed alignment problems if wp adminbar was present
* BUGFIX: cleaned up js code

= Version 1.5.1 =
* BUGFIX: Screen display option now properly coded and falls after admin bar loads.
* BUGFIX: Options function naming cleanup.

= Version 1.5 =
* NEW FEATURE: Added tool in BAP Options to help identify current admin screen id.

= Version 1.4.1 =
* NEW TRANSLATION: Added French translation (fr_FR)

= Version 1.4 =
* NEW FEATURE: Plugin is now fully internationalized. Start your translation engines (and let me know if you would like to provide a translation).
* BUGFIX: System will no longer allow bad naming for the Pointer ID (They need to be lower case and no spaces. Plugin will now apply rules to change a badly named pointer)

= Version 1.3 =
* NEW FEATURE: Removed default get_posts limit of 5, now can display more than 5 pointers per page (but really a bad idea, so try to avoid it ok?)
* NEW FEATURE: Options button to reset all admin pointers for all users. Use with caution, as this will also reset the built in WP admin pointers.
* NEW FEATURE: Now listing version number on Settings page
* CHANGE: New format for pointers in DB, please go to options page to update your old pointers. They will not show up until you do.
* BUGFIX: Pointer script was not localized correctly


= Version 1.2.2 =
* BUGFIX: Correct for null values error if nudge fields are left blank

= Version 1.2.1 =
* BUGFIX: If there is more than one pointer on a screen, the correct one will be dismissed. (previously sometimes the wrong item would be dismissed)

= Version 1.2 =
* NEW FEATURE: Ability to nudge pointer in settings if it isn't sitting exactly in the right place
* NEW FEATURE: Added columns to listing page
* ALSO: added to FAQ, cleaned up strings

= Version 1.1 =
Added options page with permissions

= Version 1.0.1 =
Added FAQ, better explanation strings

= Version 1.0 =
First Version, awaiting bug reports...