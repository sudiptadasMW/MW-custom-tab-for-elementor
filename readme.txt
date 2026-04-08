=== MW Custom Tab for Elementor ===
Contributors: mw
Tags: elementor, tabs, fabrication, widget, carousel
Requires at least: 5.6
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom Elementor widget with two advanced layout styles for fabrication/service sections.

== Description ==

MW Custom Tab for Elementor adds a powerful "MW Fabrication Tabs" widget to the Elementor panel. It supports two distinct layout styles:

**Style One – Vertical Tabs**
* Left: vertical tab navigation with active indicator
* Right: large image + description content
* Smooth fade transitions between tabs
* Fully keyboard-navigable

**Style Two – Category + Sub Tabs**
* Top: horizontal scrollable category tab bar (dark themed)
* Left: vertical sub-tab list per category
* Middle: static image or Swiper.js carousel
* Right: description text + CTA button
* Carousel syncs with sub-tab clicks and vice versa

Both styles are fully responsive:
* Desktop: exact 3-column / side-by-side layouts
* Tablet: intelligent column collapsing
* Mobile: horizontal scroll tabs or accordion behavior

== Installation ==

1. Upload the `fabrication-widget` folder to `/wp-content/plugins/`
2. Activate the plugin through the "Plugins" menu in WordPress
3. Make sure Elementor (free or Pro) is installed and active
4. Edit any page with Elementor and search for "MW Fabrication Tabs" in the widget panel

== Frequently Asked Questions ==

= Does this require Elementor Pro? =
No. It works with the free version of Elementor.

= How do I assign items to categories in Style Two? =
Each item has a "Category Index" field (0-based). Set it to 0 for the first category, 1 for the second, etc.

= Can I use my own images? =
Yes. Each tab/item has a Media control to select images from the WordPress Media Library.

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release — no upgrade needed.
