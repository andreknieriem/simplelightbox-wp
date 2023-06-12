=== Simplelightbox ===
Contributors: aknieriem
Donate link: https://paypal.me/anrinas
Tags: lightbox, simple, image, overlay, modal, dialog
Requires at least: 3.0.1
Tested up to: 6.2.2
Stable tag: 2.14.2
License: MIT
License URI: https://opensource.org/licenses/MIT

Touch-friendly image lightbox for mobile and desktop without requiring jQuery

== Description ==

The simplelightbox wordpress plugin brings the [simplelightbox](https://github.com/andreknieriem/simplelightbox) jquery plugin to wordpress. 

You can change every option that the lightbox have in the admin panel under Design -> Simplelightbox.

== Installation ==

= Install =

1. Upload the 'simplelightbox' folder  to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Design -> Simplelightbox and set up the lightbox like you want

= Uninstall =

1. Deactivate Simplelightbox in the 'Plugins' menu in Wordpress.
2. After Deactivation a 'Delete' link appears below the plugin name, follow the link and confim with 'Yes, Delete these files'.
3. This will delete all the plugin files from the server as well as erasing all options the plugin has stored in the database.


== Frequently Asked Questions ==

= Where can I get support? =

Please visit the [Support Forum](http://wordpress.org/support/plugin/simplelightbox "Use this for support and feature requests")
for questions, answers, support and feature requests.

== Screenshots ==

1. Admin page where you setup the plugin

== Changelog ==
= 2.14.2 =
* Fixing not using fileExt on special theme links

= 2.14.1 =
* Updated to latest simplelightbox version

= 2.14.0 =
* Updated to latest simplelightbox version

= 2.13.0 =
* Updated to latest simplelightbox version and fixing design break in backend; fixing caption opacity;

= 2.12.0 =
* Updated to latest simplelightbox version

= 2.11.0 =
* Fixing not working lightbox on newer templates

= 2.10.1 =
* Added latest simplelightbox.js (2.10.3) with bugfixing

= 2.10.0 =
* Added latest simplelightbox.js (2.10.2) with new option and bugfixing

= 2.9.0 =
* Added latest simplelightbox.js (2.9.0) with 2 new options for mousezooming

= 2.8.0 =
* Added latest simplelightbox.js (2.8.0) with some tweaks and passive event listeners

= 2.7.3 =
* Added latest simplelightbox.js (2.7.3)

= 2.7.2 =
* Fixed #232 - sourceAttr does not work. Thanks to @bivisual for the issue

= 2.7.2 =
* Fixed #231 - disableRightClick doesn't. Thanks to @DrMint for the fix

= 2.7.1 =
* Removed jQuery dependency in the frontend plugin. Was only used for 3 simple selectors.

= 2.7.0 =
* Added latest simplelightbox.js (2.7.0) and an option for additional html selectors.

= 2.6.2 =
* Bugfixing css output

== Changelog ==
= 2.6.1 =
* Bugfixing wrong z-index

= 2.6.0 =
* Added latest simplelightbox.js (2.6.0) with some fixes and new options.

= 2.4.1 =
* Added latest simplelightbox.js (2.4.1) with lots of fixes and improvments and a new option to use the legacy version with IE support.

= 2.1.5 =
* Added latest simplelightbox.js (2.1.5) with lots of fixes and improvments

= 2.0.0 =
* Added latest simplelightbox.js (2.0.0) with new options and a complete rewrite

= 1.6.0 =
* Added simplelightbox.js (1.6.0) with new options

= 1.5.2 =
* fix issues on older php versions

= 1.5.1 =
* Code refactoring thanks to Andrej Cremoznik

= 1.5.0 =
* Added simplelightbox.js (1.5.0) with the two new options

= 1.4.6 =
* Added localization file and new version of simplelightbox.js (1.4.6)

= 1.4.5 =
* Added new simplelightbox.js Options for captions

= 1.4.4 =
* Bugfix saving does not work
* Added new version of simplelightbox.js (1.4.5)

= 1.4.3 =
* Added new Options for styling the lightbox
* Bugfix z-index for spinner to low
* Added new version of simplelightbox.js

= 1.4.2 =
* Bugfix for issue #2 - Drop Event does not fire when mouse leaves window
* Increased Z-index

= 1.4 =
* Caption Attribute can now be what, you want, or data-title 
* Fixed some small issues

= 1.3.1 =
* Bugfix: disable keyboard control if lightbox is closed

= 1.3.0  =
* Added current index indicator/counter

= 1.2.0 =
* Added option for captions attribute (title or data-title)

= 1.1.2 =
* Bugfix for looping images

= 1.1.1 =
* Bugfix for loading indicator and removed a log-event

= 1.1.0 =
* Added classname for lightbox wrapper and width/height ratio

= 1.0.0 =
* Initial Release
