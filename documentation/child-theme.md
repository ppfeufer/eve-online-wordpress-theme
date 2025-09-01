# Using a Child Theme

**A quick walk through on how to use a child theme for this theme**

Child Themes are generally used when you want to tweak your theme a bit, without the fear your changes will be overwritten again, when the theme gets an update. Also, editing a theme is generally a bad idea, that's why you always should use a child theme.

In a child theme, you only apply your changes and have only the files you've changed, and since it's nasically a theme for its own, it won't be overwritten when the main theme is updated.

### Creating your child theme

First you have to create a new folder in your themes directory. Let's call this folder "eve-online-child". Once you've done that you have to create a file called "style.css" with the following content ...

```css
/*!
 * Theme Name: EVE Online Child Theme
 * Theme URI:
 * Description: Child Theme for the EVE Online Theme ( https://github.com/ppfeufer/eve-online-wordpress-theme )
 * Version: 0.1
 * Author: Your Name
 * Author URI: Your UWL
 * Template: eve-online
 * License: GNU General Public License v2 or later
 * License URI: https://github.com/ppfeufer/eve-online-wordpress-theme/blob/master/LICENSE
 * Tags: EVE Online, MMORPG, Bootstrap, Responsive
 * Text Domain: eve-online
 */

/* here goes your own CSS for your child theme
----------------------------------------------------------------------------- */
```

To explain these lines:

- **Theme Name:** (mandatory) This is the name of your Child Theme. Should be something telling like "EVE Online Child Theme" to make sure you know what that theme is for.
- **Theme URI:** (optional) The themes URI. Is not really used anywhere, so you can keep it empty.
- **Description:** (optional) The themes description. Will be used in your backend in your themes listing.
- **Version:** (mandatory) The themes version. This should always be in here, and be updated once you change something on your child theme. Just to keep track of changes and such.
- **Author:** (mandatory) The name of the child themes author, so that's you ...
- **Author URI:** (optional) This can be the website of your corp or alliance.
- **Template:** (mandatory) the dirctoy of teh parent theme. In our case: eve-online. If you have your EVE online theme in a differently named directory, make sure you put the right one in here.
- **License URI:** (mandatory / do not change) Since the EVE Online theme is under the mentioned [license](../LICENSE) every adaption of it has to be under the same license as well.
- **Tags:** (completely optional) Tags are only used in the WordPress theme repository to filter themes. So can be ignored in our case.
- **Text Domain:** (mandatory / do not change) The text-domain has to be "eve-online". It's for propper translations.

Now you have to make sure your style.css will be enqueued propperly.
The theme is trying to actually find a minified version of the **style.css**, called **style.min.css**, no matter what. If it doesn't find this in your child theme directory, it will just load the one from the parent theme. So you either have to make sure you provide the **style.min.css** or you force the theme to accept your **style.css** as well.

If you provide your own **style.min.css** that can be loaded automaticalls by the theme, make sure you add this line _before_ your own styles. This ensures that the parenty theme style.min.css is loaded as well. Make sure the normal style.css is provided as well, that's where WordPress gets the theme informations from.

```css
@import url("../eve-online/style.min.css");
```

To force the theme to use your **style.css** it is needed to change the way the EVE Online theme is handling its styles. Create a functions.php file in your child themes directory and add the following code.

```php
/**
 * functions.php for the child theme of the EVE Online theme.
 *
 * If you want to edit the themes functions here, keep in mind, it's all namespaced!
 */

/**
 * The main themes namespace
 */
namespace Ppfeufer\Theme\EVEOnline;

/* start your own stuff here
---------------------------------------------------------------------------- */
/**
 * This is to overwrite the function in functions.php
 *
 * @uses ChildTheme\Helper\ThemeHelper::getThemeStyleSheets getting the stylesheets that need to be enqueued
 */
function eve_get_stylesheets() {
    return ChildTheme\Helper\ThemeHelper::getThemeStyleSheets();
}
```

Also, you need to overwrite the ThemeHelper class a bit. Create a "ThemeHelper.php" under /helper directory in your child theme directory and add the following content:

```php
<?php

namespace Ppfeufer\Theme\EVEOnline\ChildTheme\Helper;

\defined('ABSPATH') or die();

require_once(\get_parent_theme_file_path('/helper/ThemeHelper.php'));

class ThemeHelper extends \Ppfeufer\Theme\EVEOnline\Helper\ThemeHelper {
    public static function getThemeStyleSheets() {
        // getting the parent's theme style sheets
        $enqueue_style = parent::getThemeStyleSheets();

        // manipulating the way the parent's theme style sheet is loaded
        $enqueue_style['EVE Online Theme Styles'] = array(
            'handle' => 'eve-online',
            'source' => \get_template_directory_uri() . '/style.min.css',
            'deps' => array(
                'normalize',
                'google-font',
                'bootstrap'
            ),
            'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
            'media' => 'all'
        );

        // adding the child theme style sheet
        $enqueue_style['EVE Online Child Theme Styles'] = array(
            'handle' => 'eve-online-child',
            'source' => \get_theme_file_uri('/style.css'),
            'deps' => array(
              ' eve-online'
            ),
            'version' => \sanitize_title(self::getThemeData('Name')) . '-' . self::getThemeData('Version'),
            'media' => 'all'
        );

        return $enqueue_style;
    }
}
```

This expected behaviour goes for all css files the theme uses. You either provide the respective .min-version or you overwrite the loading function to make the theme accept your file. If you don't know what and how to do, than it might be easier to simply provide a .min-version of the file. Keep in mind, the **style.css** is a special file for WordPress, so it **_has_** to be there as well, regardless which method you chose. Because WordPress is getting the theme information out of it ...

With this example you can see how to overwrite theme functions. The theme itself is split in different PHP classes and is using namespaces, which makes it pretty comfortable to use with a child theme.

The parent theme's namespace is:
`Ppfeufer\Theme\EVEOnline`

The namespace you should use in your child theme is:
`Ppfeufer\Theme\EVEOnline\ChildTheme`
