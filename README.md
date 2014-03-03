=== Advanced Custom Fields: Blogs Field ===
Contributors: jonathandejong
Tags:
Requires at least: 3.4
Tested up to: 3.3.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extension for ACF which provides the user with a dropdown of the multisites blogs

== Description ==

Extension for ACF which provides the user with a dropdown of the multisites blogs. This plugin provides an additional field for Advanced Custom Fields. The field outputs a dropdown in which the user may select one or more blogs in a multisite network. The stored value can then be used to fetch the selected blog(s) ids. More to follow. 

= Compatibility =

This add-on will work with:

* version 4 and up
* version 3 and bellow

== Installation ==

This add-on can be treated as both a WP plugin and a theme include.

= Plugin =
1. Copy the 'acf-blogs' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

= Include =
1.	Copy the 'acf-blogs' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-blogs.php file)

`
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	include_once('acf-blogs/acf-blogs.php');
}
`

== Changelog ==

= 0.0.1 =
* Initial Release.
