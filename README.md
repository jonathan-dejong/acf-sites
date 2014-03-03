# ACF Sites Field

Adds a 'ACF Sites' field type for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin.

-----------------------

### Overview

Extension for ACF which provides the user with a dropdown of the multisites blogs. This plugin provides an additional field for Advanced Custom Fields. The field outputs a dropdown in which the user may select one or more blogs in a multisite network. The stored value can then be used to fetch the selected blog(s) ids. More to follow. 

### Compatibility

This add-on will work with:

* version 4 and up
* version 3 and bellow

### Installation

This add-on can be treated as both a WP plugin and a theme include.

**Install as Plugin**

1. Copy the 'acf-sites' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

**Include within theme**

1.	Copy the 'acf-sites' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-sites.php file)

```php
include_once('acf-sites/acf-sites.php');
```

### More Information

Please read the readme.txt file for more information
