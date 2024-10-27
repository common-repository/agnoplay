# Agnoplay Wordpress

Tags: video
Requires PHP: 5.2
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: <http://www.gnu.org/licenses/gpl-2.0.html>

This plugin allows the user to add and configure an Agnoplay video within pages and posts.

## Installation

1. Upload `agnoplay-wordpress` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Configuring the plugin

### General configuration

The following general settings can be found on the Agnoplay settings page. This page can be accessed via the Agnoplay settings submenu-item of the settings menu-item in the wordpress backend

* Video brand ID
* Audio brand ID
* Live brand ID
* License keys
* Environment switcher

### Player specific configuration

The following player specific options can be configured within the Agnoplay Gutenberg block.

* Video ID
* Video title
* Video thumbnail
* Player variant

## Licensing

To enable the playback of video with the Agnoplay plugin an active license is mandatory. You will receive a license key for each active brand in your account. When configuring the player in your WordPress settings, just add the corresponding license key for each of your available player variants.

## Disclaimer

Use of this plugin is entirely at the risk of its user. Agnoplay disclaims any direct or indirect liability arising from its use. This plugin may be used freely by customers of Agnoplay, and merely serves as a basis for the integration of Agnoplay within Wordpress. Any conflicting issues with Wordpress and/or other plugins, themes, etc. must be resolved by the user itself. The use of this plugin falls outside the SLA that Agnoplay concludes with its customers, there is no support available on this Wordpress plugin.

## Styling

The Agnoplay player is inserted inline in the wordpress page. If your theme overrides default values for e.g. buttons this can also affect the player. One of the most common issues is that a specific font-size is set for elements on the page. Since the player UI scales based on the font-size adjusting this value on individual elements will produce unexpected/unwanted results.

If your theme applies these global style changes you can prevent them from affecting the player by appending a `:not` selector and specifically not-target the affected agnoplayer elements.

e.g. When setting a global font-size, to not affect the sizing of images inside the player

```text/css
*:not(.video-js) {
  font-size: 12px;
}
```
