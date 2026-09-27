# Custom URL for Elementor 🔗

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/custom-url-for-elementor.svg)](https://wordpress.org/plugins/custom-url-for-elementor/)
[![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/r/custom-url-for-elementor.svg)](https://wordpress.org/plugins/custom-url-for-elementor/)
[![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/custom-url-for-elementor.svg)](https://wordpress.org/plugins/custom-url-for-elementor/)

Enhance your Elementor designs! Add custom URLs and CSS to Container, Section, Column, and Inner Section elements.

![12](https://github.com/user-attachments/assets/a0d8d82c-a14d-424f-92c8-320a42de6f98)



## 📝 Description

Custom URL for Elementor is a powerful plugin that extends Elementor's functionality. With this plugin, you can add clickable URLs to Container, Section, Column, and Inner Section elements, making your website more interactive and dynamic.

This plugin is developed by the [Woologger](https://woologger.com) team, passionate about creating useful tools for WordPress and Elementor users.

### 🌟 Key Features

- **Custom URLs**: Add clickable links to Container, Section, Column, and Inner Section elements.
- **New Tab Option**: Choose whether links open in the same window or a new tab.
- **Behaves Like a Real Link**: Ctrl/Cmd + click and middle click open a new tab, and buttons or links inside the element keep working.
- **Accessible**: Keyboard focus and Enter key support, plus an optional screen reader label.
- **Popups & Lightbox**: Open Elementor Pro popups or a lightbox with the Dynamic Tags › Actions option.
- **Smooth Anchor Scrolling**: Link to #sections on the same page, with an offset for sticky headers.
- **Per-Device Control**: Disable the link on desktop, tablet, mobile or any active breakpoint.
- **Hover Effects**: Lift, grow, shrink, shadow or dim on hover, with adjustable duration and cursor style (respects reduced motion).
- **Tooltip**: Show a tooltip when the element is hovered.
- **Click Tracking**: Send click events to Google Tag Manager or Google Analytics 4 with your own event name.
- **Developer Friendly**: A cancelable `cufe:click` JavaScript event and a `cufe_enable_custom_css` filter.
- **Custom CSS**: Apply custom CSS styles directly to your elements for advanced customization.
- **User-Friendly Interface**: Seamlessly integrates with Elementor's interface.
- **Lightweight**: Optimized code minimizes impact on your website's performance.
- **Responsive Design**: Works flawlessly across all devices and screen sizes.

## ⚙️ Requirements

- WordPress 6.5 or later (tested up to 7.1)
- PHP 7.4 – 8.4 (including PHP 8.1)
- Elementor (tested up to 4.0); Elementor Pro optional

## 🚀 Installation

1. Upload the plugin files to the `/wp-content/plugins/custom-url-for-elementor` directory, or install the plugin directly from the WordPress plugin screen.
   - To install from the WordPress Plugin Repository: [https://wordpress.org/plugins/custom-url-for-elementor/](https://wordpress.org/plugins/custom-url-for-elementor/)
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the new 'Custom URL' options in Elementor's editor for Container, Section, Column, and Inner Section elements.

## ❓ Frequently Asked Questions

### Does this plugin work with the latest version of Elementor?

Yes, we regularly update the plugin to ensure compatibility with the latest versions of both Elementor and WordPress.

### Can I add custom URLs to other Elementor elements?

Currently, the plugin supports Container, Section, Column, and Inner Section elements. We're considering expanding this functionality in future updates.

### I use Elementor Pro. Where is the Custom CSS field?

Elementor Pro already has its own Custom CSS field (Advanced tab) that uses the same setting, so the plugin hides its duplicate field when Pro is active. CSS you added earlier keeps working and appears in Pro's field.

### Can a clickable element open an Elementor popup?

Yes. Click the Dynamic Tags icon in the Element URL field, choose Actions › Popup (Elementor Pro) or Lightbox, and pick the action. The popup opens when the element is clicked.

### How do I scroll to a section on the same page?

Give the target element a CSS ID (Advanced › CSS ID), then enter that ID with a # (for example #contact) as the Element URL. Use "Anchor Scroll Offset" if you have a sticky header.

### How does click tracking work?

Enable "Track Clicks" and optionally set an event name (default: custom_url_click). If Google Tag Manager is on the page, an event is pushed to the dataLayer with link_url, link_text and element_id; otherwise it is sent with gtag() to Google Analytics 4.

### Which PHP versions are supported?

PHP 7.4 through 8.4, including PHP 8.1.

### Will this plugin slow down my website?

No, Custom URL for Elementor is designed to be lightweight and optimized. It should not have any noticeable impact on your website's loading speed.

## 📜 Changelog

### 2.2.0
- Added: Open Elementor Pro popups and the lightbox via Dynamic Tags › Actions
- Added: Smooth scrolling to on-page #anchors with an adjustable offset for sticky headers
- Added: "Disable Link On" option to turn the link off on selected devices / breakpoints
- Added: Hover effects (lift, grow, shrink, shadow, dim) with transition duration, live in the editor
- Added: Cursor style and tooltip options
- Added: Click tracking for Google Tag Manager (dataLayer) and Google Analytics 4 (gtag)
- Added: Cancelable `cufe:click` JavaScript event for developers
- Improved: Verified compatibility with PHP 7.4 through 8.4 (including PHP 8.1)

### 2.1.0
- Fixed: Custom URL panel now appears on Column elements (it was only shown on Containers and Sections)
- Fixed: Security – `javascript:` and other unsafe URLs are no longer executed; only http(s), mailto, tel and sms links are followed
- Fixed: Conflict with Elementor Pro's own Custom CSS control
- Fixed: Clicking a link or button inside a clickable element no longer triggers the element link as well
- Fixed: PHP warning when the "Open in New Tab" setting was never saved
- Improved: Inline `onclick` replaced with a small, deferred script (works with strict Content Security Policies)
- Improved: Ctrl/Cmd + click and middle click open the link in a new tab; new tabs open with `noopener`
- Improved: Accessibility – keyboard focus, Enter key support and an optional accessible label
- Improved: The URL control's "Open in new window" option is honoured
- Improved: Custom CSS is sanitized before being written to the stylesheet
- Improved: Containers already rendered as an `<a>` tag keep Elementor's native link
- Updated: Requires WordPress 6.5+ and PHP 7.4+, declares the Elementor dependency; updated for WordPress 7.1 and Elementor 4.0

### 2.0.0
- Added: Custom CSS functionality for advanced styling options
- Improved: Security enhancements with proper output escaping
- Optimized: Code structure for better performance
- Fixed: Potential XSS vulnerabilities
- Updated: Compatibility with latest WordPress and Elementor versions

### 1.0.0
- Initial release

## 🆘 Support

If you encounter any issues or have questions, please visit our [support forum](https://wordpress.org/support/plugin/custom-url-for-elementor/) or [contact us directly](https://www.woologger.com/en/contact-us/).

## 🤝 Contributing

We welcome contributions to improve Custom URL for Elementor. Please feel free to submit pull requests or open issues on this repository.

## 👏 Credits

Custom URL for Elementor is brought to you by [Woologger](https://woologger.com), a team dedicated to creating useful tools for WordPress and Elementor users. Woologger specializes in e-commerce and content management solutions, committed to enhancing the WordPress ecosystem.
