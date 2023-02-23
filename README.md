# Matchbox Fire Protection Plugin

> The Matchbox Fire Protection plugin configures WordPress to better protect our clients' sites. It is not meant as a general-distribution plugin and does not have an open development process, but is available for public perusal.

[![Support Level](https://img.shields.io/badge/support-active-green.svg)](#support-level) [![Release Version](https://img.shields.io/github/v/tag/matchboxdesigngroup/matchbox-fire-protection?label=release)](https://github.com/matchboxdesigngroup/matchbox-fire-protection/tags) ![WordPress tested up to version](https://img.shields.io/badge/WordPress-v5.9%20tested-success.svg) [![GPLv2 License](https://img.shields.io/github/license/matchboxdesigngroup/matchbox-fire-protection.svg)](https://github.com/matchboxdesigngroup/matchbox-fire-protection/blob/develop/LICENSE.md)

## Requirements

* PHP 5.4+
* [WordPress](http://wordpress.org) 4.7+

## Installation

### Composer

The recommended way to use this plugin is with Composer.

```
composer require matchboxdesigngroup/matchbox-fire-protection
```

### Git
For development purposes, you can clone the plugin into `wp-content/plugins` and install the dependencies.

```
git clone git@github.com:matchboxdesigngroup/matchbox-fire-protection.git && cd matchbox-fire-protection && composer install && npm install
```

### Archive
If you need a built version of the plugin to install via the dashboard, [download](https://github.com/matchboxdesigngroup/matchbox-fire-protection/archive/master.zip) and extract the plugin into `wp-content/plugins`. Make sure you use the `main` branch which contains the latest stable release.

## Activation

Activate the plugin via the dashboard or WP-CLI.

```
wp plugin activate matchbox-fire-protection
```

## Updates

Updates use the built-in WordPress update system to pull from GitHub releases.

## Functionality

### REST API

Adds an option to general settings to restrict REST API access. The options are: show REST API to everyone, only show REST API to logged-in users, and show REST API to everyone except `/users` endpoint. By default, the plugin requires authentication for the `/users` endpoint.

*Configured in `Settings > Reading`.*

### Authors

Removes Matchbox user author archives so they aren't mistakenly indexed by search engines.

### Gutenberg

Adds an option in writing to switch back to Classic Editor.

*Configured in `Settings > Writing`.*

### Plugins

 Adds a Matchbox Suggested Plugins section to the plugins screen. Warns users who attempt to deactivate the Matchbox Fire Protection plugin. Outputs a notice on non-suggested plugins tabs warning users from installing non-approved plugins. If `DISALLOW_FILE_MODS` is on, update notices will be shown in the plugins table.

### Post Passwords

Password protecting post functionality is removed both in Gutenberg and the classic editor. This can be disabled in the writing section of the admin.

*Configured in `Settings > Writing`.*

### Authentication

By default, all users must use a medium or greater-strength password. This can be turned off in general settings (or network settings if network activated). Reserved usernames such as `admin` are prevented from being used.

*Configured in `Settings > General` or `Settings > Network Settings` if network activated.*

 *Password strength functionality requires the PHP extension [mbstring](https://www.php.net/manual/en/mbstring.installation.php) to be installed on the web server. The functionality will be bypassed if the extension is not installed.*


### Headers

`X-Frame-Origins` is set to `sameorigin` to prevent clickjacking.

*Note:* Matchbox admin branding can be disabled by defining the constant `MATCHBOX_DISABLE_BRANDING` as `true`.

There are 2 filters available here:
- `matchbox_fire_protection_x_frame_options` - (default value) `SAMEORIGIN` can be changed to `DENY`.
- `matchbox_fire_protection_disable_x_frame_options` - (default value) `FALSE` can be changed to `TRUE` - doing so will omit the header.

## Support Level

**Active:** Matchbox is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress.  Bug reports, feature requests, questions, and pull requests are welcome.

## Changelog

A complete listing of all notable changes to the Matchbox Fire Protection plugin is documented in [CHANGELOG.md](https://github.com/matchboxdesigngroup/matchbox-fire-protection/blob/develop/CHANGELOG.md).


## Shout out to 10up

We would like to thank the incredible team at [10up](https://github.com/10up) for their commitment to open source and contributions to the WordPress community. This plugin is based on their [10up Experience](https://github.com/10up/10up-experience) plugin and was modified to meet the needs of the Matchbox team and our clients.
