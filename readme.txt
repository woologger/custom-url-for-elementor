=== Custom URL for Elementor ===
Contributors: woologger
Tags: elementor, custom-url, link, container, section
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make Elementor Containers, Sections and Columns clickable: links, popups, smooth anchor scrolling, hover effects, click tracking and custom CSS.

== Description ==

Custom URL for Elementor is a powerful plugin that extends the functionality of Elementor, allowing you to create more interactive and dynamic layouts. With this plugin, you can easily add clickable URLs to Container, Section, Column, and Inner Section elements, transforming them into navigable components of your website.

= Key Features =

* **Custom URLs**: Add clickable links to Container, Section, Column, and Inner Section elements.
* **New Tab Option**: Choose whether links open in the same window or a new tab.
* **Behaves Like a Real Link**: Ctrl/Cmd + click and middle click open a new tab, and buttons or links inside the element keep working.
* **Accessible**: Keyboard focus and Enter key support, plus an optional screen reader label.
* **Popups & Lightbox**: Open Elementor Pro popups or a lightbox with the Dynamic Tags › Actions option.
* **Smooth Anchor Scrolling**: Link to #sections on the same page, with an offset for sticky headers.
* **Per-Device Control**: Disable the link on desktop, tablet, mobile or any active breakpoint.
* **Hover Effects**: Lift, grow, shrink, shadow or dim on hover, with adjustable duration and cursor style (respects reduced motion).
* **Tooltip**: Show a tooltip when the element is hovered.
* **Click Tracking**: Send click events to Google Tag Manager or Google Analytics 4 with your own event name.
* **Developer Friendly**: A cancelable `cufe:click` JavaScript event and a `cufe_enable_custom_css` filter.
* **Custom CSS**: Apply custom CSS styles directly to your elements for advanced customization.
* **User-Friendly Interface**: Seamlessly integrates with Elementor's interface for easy use.
* **Lightweight**: Optimized code ensures minimal impact on your website's performance.
* **Responsive**: Works flawlessly across all devices and screen sizes.

= How It Works =

1. Edit any Container, Section, Column, or Inner Section in Elementor.
2. Find the new "Custom URL" options in the element's settings.
3. Add your desired URL and customize the link behavior.
4. Optionally, add custom CSS for further styling.
5. Save and preview your changes.

= Pro Tips =

* Use custom URLs on sections to create clickable banners or call-to-action areas.
* Apply custom CSS to add hover effects or transitions to your linked elements.
* Combine with Elementor's built-in features for even more dynamic layouts.

= Compatibility =

Custom URL for Elementor is compatible with:

* Elementor Free
* Elementor Pro
* Most popular WordPress themes

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/custom-url-for-elementor` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the new 'Custom URL' options in Elementor's editor for Container, Section, Column, and Inner Section elements.

== Frequently Asked Questions ==

= Does this plugin work with the latest version of Elementor? =

Yes, we regularly update the plugin to ensure compatibility with the latest versions of both Elementor and WordPress.

= Can I add custom URLs to other Elementor elements? =

Currently, the plugin supports Container, Section, Column, and Inner Section elements. We're considering expanding this functionality in future updates.

= Will this plugin slow down my website? =

No, Custom URL for Elementor is designed to be lightweight and optimized. It should not have any noticeable impact on your website's loading speed.

= Is the custom CSS feature safe to use? =

Yes, the custom CSS feature is safe when used responsibly. However, we recommend testing any custom CSS thoroughly to ensure it doesn't interfere with your site's existing styles.

= I use Elementor Pro. Where is the Custom CSS field? =

Elementor Pro already has its own Custom CSS field (Advanced tab) that uses the same setting, so the plugin hides its duplicate field when Pro is active. CSS you added earlier keeps working and appears in Pro's field.

= Can links inside a clickable element still be clicked? =

Yes. Links, buttons, form fields and other interactive elements inside a clickable element keep their own behaviour.

= Can a clickable element open an Elementor popup? =

Yes. Click the Dynamic Tags icon in the Element URL field, choose Actions › Popup (Elementor Pro) or Lightbox, and pick the action. The popup opens when the element is clicked.

= How do I scroll to a section on the same page? =

Give the target element a CSS ID (Advanced › CSS ID), then enter that ID with a # (for example #contact) as the Element URL. Use "Anchor Scroll Offset" if you have a sticky header.

= How does click tracking work? =

Enable "Track Clicks" and optionally set an event name (default: custom_url_click). If Google Tag Manager is on the page, an event is pushed to the dataLayer with link_url, link_text and element_id; otherwise it is sent with gtag() to Google Analytics 4.

= Which PHP versions are supported? =

PHP 7.4 through 8.4, including PHP 8.1.

= Can I use this plugin with other page builders? =

This plugin is specifically designed for Elementor. It may not function correctly with other page builders.

== Screenshots ==

1. Custom URL settings in Elementor editor
2. Adding a custom URL to a section
3. Custom CSS interface
4. Frontend view of a clickable section

== Changelog ==

= 2.2.0 =
* Added: Open Elementor Pro popups and the lightbox via Dynamic Tags › Actions
* Added: Smooth scrolling to on-page #anchors with an adjustable offset for sticky headers
* Added: "Disable Link On" option to turn the link off on selected devices / breakpoints
* Added: Hover effects (lift, grow, shrink, shadow, dim) with transition duration, live in the editor
* Added: Cursor style and tooltip options
* Added: Click tracking for Google Tag Manager (dataLayer) and Google Analytics 4 (gtag)
* Added: Cancelable `cufe:click` JavaScript event for developers
* Improved: Verified compatibility with PHP 7.4 through 8.4 (including PHP 8.1)

= 2.1.0 =
* Fixed: Custom URL panel now appears on Column elements (it was only shown on Containers and Sections)
* Fixed: Security – `javascript:` and other unsafe URLs are no longer executed; only http(s), mailto, tel and sms links are followed
* Fixed: Conflict with Elementor Pro's own Custom CSS control
* Fixed: Clicking a link or button inside a clickable element no longer triggers the element link as well
* Fixed: PHP warning when the "Open in New Tab" setting was never saved
* Improved: Inline `onclick` replaced with a small, deferred script (works with strict Content Security Policies)
* Improved: Ctrl/Cmd + click and middle click open the link in a new tab; new tabs open with `noopener`
* Improved: Accessibility – keyboard focus, Enter key support and an optional accessible label
* Improved: The URL control's "Open in new window" option is honoured
* Improved: Custom CSS is sanitized before being written to the stylesheet
* Improved: Containers already rendered as an `<a>` tag keep Elementor's native link
* Updated: Requires WordPress 6.5+ and PHP 7.4+, declares the Elementor dependency; updated for WordPress 7.1 and Elementor 4.0

= 2.0.0 =
* Added: Custom CSS functionality for advanced styling options
* Improved: Security enhancements with proper output escaping
* Optimized: Code structure for better performance
* Fixed: Potential XSS vulnerabilities
* Updated: Compatibility with latest WordPress and Elementor versions

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 2.2.0 =
Adds popup/lightbox support, smooth anchor scrolling, per-device control, hover effects, tooltips and click tracking.

= 2.1.0 =
Security and compatibility update: blocks unsafe link URLs, fixes Column support and the Elementor Pro Custom CSS conflict, and improves accessibility. Recommended for all users.

= 2.0.0 =
This update introduces custom CSS functionality, enhances security, and optimizes performance. It is strongly recommended for all users to update to this version.

== Support ==

If you encounter any issues or have any questions, please visit our [support forum](https://wordpress.org/support/plugin/custom-url-for-elementor/) or [contact us directly](https://www.woologger.com/en/contact-us/).

== Contributions ==

We welcome contributions to improve Custom URL for Elementor. Please visit our [GitHub repository](https://github.com/woologger/custom-url-for-elementor) to contribute.

== Privacy Policy ==

Custom URL for Elementor does not collect or store any user data. It functions entirely within your WordPress installation.

== Credits ==

Custom URL for Elementor is brought to you by [Woologger](https://woologger.com), a team passionate about creating useful tools for WordPress and Elementor users.