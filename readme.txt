=== Advanced Custom Fields: Sites Field ===
Contributors: Jonathan de Jong
Tags: multisite,network,acf,advanced custom fields,sites,relational sites,blogs
Requires at least: 3.5
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a sites field type to ACF. Allows for selection of one or multiple sites in a multisite network.

== Description ==

Adds a sites field type to ACF. Allows for selection of one or multiple sites in a multisite network.
This field type gives the user the option to select sites in a network. You might use this to have an ads post type which can be set to be displayed on a single or multiple sites in a network… just as an example!

= Compatibility =

This ACF field type is compatible with:

* ACF 5
* ACF 4

= Languages =
* English
* Swedish
____
Do you want to translate this plugin to another language? I recommend using POEdit (http://poedit.net/) or if you prefer to do it straight from the WordPress admin interface (https://wordpress.org/plugins/loco-translate/). When you’re done, send us the file(s) to jonathan@tigerton.se and we’ll add it to the official plugin!

= Other =
* Uses Select2 for ACF 5.
* Completely WordPress Coding standard compliant.

== Installation ==

1. Copy the `acf-sites` folder into your `wp-content/plugins` folder
2. Activate the Sites plugin via the plugins admin page
3. Create a new field via ACF and select the Sites type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==

= 2.0.0 =
* Complete revamp of the entire codebase. This field now works properly with both latest version of ACF 4 and ACF 5 (PRO). There are new settings and the functionality is greatly improved. Make sure you check the settings of any existing fields after updating as this update does not include a conversion of settings.

= 1.1.2 =
* Bugfixes.

= 1.1.1 =
* Bugfix - non selected items are no longer automatically selected (same for checkboxes).
* Bugfix – added default values to field options.

= 1.1.0 =
* This update adds support for ACF 5.0. It also deprecates ACF 3 so users with ACF3 need to use the previous version of this add-on or update ACF (highly recommended).

= 1.0.0 =
* Initial Release.
