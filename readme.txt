=== Advanced Custom Fields: FIELD_LABEL Field ===
Contributors: AUTHOR_NAME
Tags: PLUGIN_TAGS
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

DESCRIPTION

Adds a 'ACF Sites' field type for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

EXTENDED_DESCRIPTION

This field type gives the user the option to choose from displaying sites as a drop down or as checkboxes (to allow for multiple selections). These sites IDs are then saved and can be retrieved for any kind of use. You might use this to have an ads post type which can be set to be displayed on a single or multiple sites in a network.. just as an example! 

= Compatibility =

This ACF field type is compatible with:
* ACF 5
* ACF 4

== Installation ==

1. Copy the `acf-sites` folder into your `wp-content/plugins` folder
2. Activate the ACF - Sites plugin via the plugins admin page
3. Create a new field via ACF and select the Sites type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==

= 1.0.0 =
* Initial Release.

= 1.1.0 =
* This update adds support for ACF 5.0. It also deprecates ACF 3 so users with ACF3 need to use the previous version of this add-on or update ACF (highly recommended)

= 1.1.1 =
* Bugfix - non selected items are no longer automatically selected (same for checkboxes)