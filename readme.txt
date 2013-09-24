=== Plugin Name ===
Contributors: mastermind
Tags: travel, booking, tickets, bus, coach, transportation
Requires at least: 3.4.2
Tested up to: 3.6.1
Stable tag: 0.1
License: MIT
License URI: http://opensource.org/licenses/MIT

This plugin embeds a Tixys station search widget into a WordPress page via a shortcode.

== Description ==

Tixys is a booking plattform for long-distance coaches. This plugin uses the Tixys API to provide an interactive search for travel connections.

This is very handy for companies who have a Tixys shop and want to embed a booking form into their own website. It may also be used by partners and affiliates of such companies.

== Installation ==

1. Install the plugin from the WordPress repository.
2. Go to Settings → Tixys, and enter your shop ID and path.
3. Edit the page on which the form is to be embedded.
4. Use the shortcode `[tixysform]` to embed a form.

The `[tixysform]` shortcode accepts a number of parameters, all of which are optional:
* `target` (values: `same`, `new`. default: `same`): Open the Tixys page in the same or a new window.
* `datepicker` (values: `true`, `false`. default: `false`): Add a datepicker to allow selecting a journey date. If no datepicker is to be embedded, the current day will be used.
* `affiliate` (value: a user ID. default: `null`): Pass an affiliate ID, as provided by the shop owner. This can be used for tracking where customers came from and what turnovers they generated.
* `from` (value: a station ID. default: `null`): Pass a station ID, to have it preselected as starting point, and the select field hidden.
* `to` (value: a station ID. default: `null`): Pass a station ID, to have it preselected as destination, and the select field hidden.

== Frequently Asked Questions ==

= How can I apply my own CSS styles? =

Simple.

1. In your theme's functions.php, dequeue the stylesheets `tixys-form` and `tixys-jquery-ui-smoothness`.
2. You can add any styles you want in your theme's `style.css`.

= Can I modify the form? =

Apart from the configurational parameters and CSS styling, *no*. Of course, you could modify the source code. But this is strongly discouraged, because new updates will overwrite your modifications. And, you are advised to update the plugin as soon as new versions are available, because there may be updates to the Tixys API, which the new plugin version reflects.

== Changelog ==

= 0.1 =
* Initial release
