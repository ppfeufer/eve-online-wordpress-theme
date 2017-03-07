# Change Log

## [In Development](https://github.com/ppfeufer/eve-online-wordpress-theme/)
[Full Changelog](https://github.com/ppfeufer/eve-online-wordpress-theme/compare/v0.1-r20170203...HEAD)
- nothing so far

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
