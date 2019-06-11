# Change Log

## [In Development](https://github.com/ppfeufer/eve-online-wordpress-theme/tree/development)
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20190611...development)
- in development

## [0.1-r20190611](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20190611) - 2019-06-11
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20190330...v0.1-r20190611)
### Fixed
- WordPress deprecation notice for uppcoming WP 5.2 (Notice: login_headertitle is deprecated since version 5.2.0! Use login_headertext instead. Usage of the title attribute on the login logo is not recommended for accessibility reasons. Use the link text instead. in /var/www/ppfeufer/webroot/dev.ppfeufer.de/wordpress/wp-includes/functions.php on line 4699)

### Changed
- Code updated to work with the new release of the ESI Client

## [v0.1-r20190330](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20190330) - 2019-03-30
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20181226...v0.1-r20190330)
### Fixed
- Lightbox-Effect for (Gutenberg) gallery is now detecting the right image again when clicking on "previous".
- Deprecated warnings

## [v0.1-r20181226](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20181226) - 2018-12-26
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20180823...v0.1-r20181226)
### Added
- Modal window capability for Gutenberg gallery
- ```[latestblogposts]``` shortcode to documentation

### Changed
- Autoloading classes instead of including them individually
- Common ESI client integrated

## Updated
- German translation

### Removed
- MO Cache (not really needed)
- Support for older Internet Explorer (Version 9 and older)

## [v0.1-r20180823](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20180823) - 2018-08-23
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20180709...v0.1-r20180823)
### Changed
- Serving the font from the same server as the theme instead from google fonts server. GDPR Update!

## [v0.1-r20180709](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20180709) - 2018-07-09
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20180601...v0.1-r20180709)
### Changed
- Developer contact updated
- Discord server added to readme
- Code formatting

## [v0.1-r20180601](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20180601) - 2018-06-01
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20180529...v0.1-r20180601)
### Added
- GPDR related checkbox to comment form (Issue: #24)

## [v0.1-r20180529](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20180529) - 2018-05-29
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20180104...v0.1-r20180529)
### Changed
- ESI URL to the new one

## [v0.1-r20180104](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20180104) - 2018-01-04
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170929...v0.1-r20180104)
### Added
- New background for the "Moon Mining Changes" expansion

### Fixed
- Field name for corporation_name and alliance_name in ESI endpoints. Thanks CCP for changing them again :-(

## [v0.1-r20170929](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170929) - 2017-09-29
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170826...v0.1-r20170929)
### Changed
- Removed link to EVE Gate from author credits since CCP shut down EVE Gate

### Fixed
- Implemented a check if the cache might be empty, so redo it
- getEveIdFromName method checks now if it got the right ID to the name ...

## [v0.1-r20170826](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170826) - 2017-08-26
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170822...v0.1-r20170826)
### Changed
- Moved cache directory to a more common place
- Set different transient cache times for different API calls
- Renamed transient cache to build a common cache for my plugins that uses the same API calls

## [v0.1-r20170822](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170822) - 2017-08-22
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170821-2...v0.1-r20170822)
### Fixed
- Some `undefined variable` notices and broken avatars

## [v0.1-r20170821-2](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170821-2) - 2017-08-21
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170821...v0.1-r20170821-2)
### Changed
- Had to debug some stuff on a live environment during an update.

## [v0.1-r20170821](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170821) - 2017-08-21
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170818...v0.1-r20170821)
### Changed
- API calls to ESI, no more XML API ...

## [v0.1-r20170818](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170818) - 2017-08-18
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170812...v0.1-r20170818)
### Changed
- Switched to short array syntax in codebase
- More reliable way to create the cache directories

## [v0.1-r20170812](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170812) - 2017-08-12
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170719...v0.1-r20170812)
### Added
- Cache time for remote images that are downloaded to be cached is now configurable

### Changed
- Thumbnails to a more reasonable size
- Deactivated EncodeEmailAddresses class for the time being, due to an issue with the Retina Plugin

### Fixed
- No longer jumping to bootstrap tabs
- Hover state of subpage menu
- Category header image fixed

### Removed
- Killboard widget, use the [EVE Online Killboard Widget](https://github.com/ppfeufer/eve-online-killboard-widget) Plugin instead

## [v0.1-r20170719](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170719) - 2017-07-19
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170630...v0.1-r20170719)
### Added
- Short "how to" on using Child Themes with this theme
- Plugin support for [Categories Images](https://wordpress.org/plugins/categories-images/)
- Plugin support for [EVE Online Fitting Manager for WordPress](https://github.com/ppfeufer/eve-online-fitting-manager)

### Changed / Fixed
- Changed some functions in functions.php to be able to be overwritten in a child theme.
- Removed themes's own security class from being automatically loaded and executed. Use a [plugin](https://github.com/ppfeufer/pp-wp-basic-security) for that, it's better that way and also protects you when you are no longer using this theme.
- Imagecache fixed, in case the image can't be downloaded, it will be taken from CCP's image server
- Fixed EVE Avatars in comments
- Removed the ```[button]``` shortcode, use the [Bootstrap 3 Shortcodes for WordPress](https://github.com/ppfeufer/bootstrap-3-shortcodes-for-wordpress) plugin instead, which better implements the bootstrap shortcodes.
- Fixed the scroll issue when using bootstrap carousels
- Fixed the scroll issue when using bootstrap accordions
- Visualized an open menu in mobile nav also with the caret

## [v0.1-r20170630](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170630) - 2017-06-30
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170419...v0.1-r20170630)
### Changed / Fixed
- Fixed carets in head and foot navigation, they don't belong there.
- Fixed removal of comments in output compression.
- Fixed cron job call for cleaning up the image cache. (Please deactivate the cron job in your theme's performance settings before updating to this version, in order to make sure the old and briken cron is removed properly)
- Moved the slider and headerimages from articles and pages into their own container "stage" for better accessability via CSS.
- Child Theming made easier
- Documentation updated
- Removed border from sidebar in mobile view

## [v0.1-r20170419](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170419) - 2017-04-19
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170325...v0.1-r20170419)
### Added
- Cron and Image Cache via Theme Options (Design -> Options -> Performance Settings)
- Article Navigation for default posts
- Option to show the corporation logo on a corp page left from your text
- New Shortcodes: [contengrid] and [gridelement]
- First try on fome sort of documentation (See documentation folder)
- New widget: Childpages Submenu

### Changed / Fixed
- Fixed missing text domains for translations (If you guys have translation files for this theme, let me know)
- Fixed sidebar on blog index
- Changed theme credits in footer from statis text to an action that can be used (eve_online_theme_credits)

## [v0.1-r20170325](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170325) - 2017-03-25
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170307...v0.1-r20170325)
### Added
- **Minimum required WordPress Version: 4.7.0**
- CacheHelper class
- Cron class
- Cron to clear image cache once every day to not litter thousands of images there
- Warp Bubbles and Engeniering Complexes added to the killboard Plugin
- Added "position" to columns shortcode, accepts "first" and "last" as parameter

### Changed / Fixed
- Missing clearfixes in the content areas
- Styled the author info for articles
- Fixed the CSS enqueue logic, so that Child Theming is possible without any weird hacks
- Sanitized and normalized some theme settings

## [v0.1-r20170307](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170307) - 2017-03-07
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170203...v0.1-r20170307)
### Added
- CHANGELOG.md file to keep track of changes from now on
- New shortcode "[credits]" for article credits
- New shortcode "[latestblogposts]" (only use it at a static page, not in articles)
- Pagination for video galleries
- Filter to remove the version string from enqueued CSS and JS
- Imagecache for images fetched from CCP's imageserver

### Changed / Fixed
- Fixed a typo in themes version number, we had a 0 to much :-)
- Fixed the radio buttons in background settings so the are no longer ellipsoid
- Fixed an issue with the head menu disappearing in mobile view so you couldn't navigate any longer
- Structure names updated

### Removed
- Deactivated HTML Minify for now, since there seems to be an issue with inline JS comments that are starting with //
- Deactivated zKillboard settings in killboard plugin for now to not confuse any one right now, it's still not implemented propperly

## [v0.1-r20170203](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170203) - 2017-02-03
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170127...v0.1-r20170203)
### Added
- HTML minify class
- "minify HTML" as option to the theme options page

### Changed
- CSS responsive style to mobile first as it makes things easier

## [v0.1-r20170127](https://github.com/ppfeufer/eve-online-wordpress-theme/releases/tag/v0.1-r20170127) - 2017-01-27
### Changed
- First"official" release, still not considered final or stable or what ever :-)
- Major code cleanup
