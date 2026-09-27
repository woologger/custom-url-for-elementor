/**
 * Custom URL for Elementor – makes elements with [data-cufe-url] behave like links.
 *
 * - Clicks on nested links, buttons and form fields keep their own behaviour.
 * - Ctrl/Cmd/Shift + click and middle click open in a new tab, like a normal link.
 * - Enter activates the element when it has keyboard focus.
 * - Only http(s), mailto, tel and sms URLs are followed.
 */
( function () {
	'use strict';

	var SELECTOR = '[data-cufe-url]';
	var INTERACTIVE = 'a[href], button, input, select, textarea, label, summary, video, audio, iframe, [contenteditable=""], [contenteditable="true"], [role="button"], [role="link"], [role="tab"], [role="menuitem"], [data-cufe-ignore]';
	var ALLOWED_PROTOCOLS = [ 'http:', 'https:', 'mailto:', 'tel:', 'sms:' ];

	function isEditor() {
		var frontend = window.elementorFrontend;
		return document.body.classList.contains( 'elementor-editor-active' ) ||
			!! ( frontend && typeof frontend.isEditMode === 'function' && frontend.isEditMode() );
	}

	function safeUrl( raw ) {
		try {
			var url = new URL( raw, window.location.href );
			return ALLOWED_PROTOCOLS.indexOf( url.protocol ) !== -1 ? url.href : null;
		} catch ( e ) {
			return null;
		}
	}

	function findTarget( event ) {
		if ( ! ( event.target instanceof Element ) ) {
			return null;
		}

		var wrapper = event.target.closest( SELECTOR );
		if ( ! wrapper ) {
			return null;
		}

		// Let nested interactive elements (and nested clickable wrappers) handle their own clicks.
		var interactive = event.target.closest( INTERACTIVE );
		if ( interactive && interactive !== wrapper && wrapper.contains( interactive ) ) {
			return null;
		}

		return wrapper;
	}

	function navigate( wrapper, forceNewTab ) {
		var url = safeUrl( wrapper.getAttribute( 'data-cufe-url' ) );
		if ( ! url ) {
			return;
		}

		if ( forceNewTab || wrapper.getAttribute( 'data-cufe-target' ) === '_blank' ) {
			window.open( url, '_blank', 'noopener' );
		} else {
			window.location.assign( url );
		}
	}

	function onClick( event ) {
		if ( event.defaultPrevented || event.button !== 0 || isEditor() ) {
			return;
		}

		var wrapper = findTarget( event );
		if ( ! wrapper ) {
			return;
		}

		// Don't hijack text selection inside the element.
		var selection = window.getSelection && window.getSelection();
		if ( selection && ! selection.isCollapsed && wrapper.contains( selection.anchorNode ) ) {
			return;
		}

		navigate( wrapper, event.ctrlKey || event.metaKey || event.shiftKey );
	}

	function onAuxClick( event ) {
		if ( event.defaultPrevented || event.button !== 1 || isEditor() ) {
			return;
		}

		var wrapper = findTarget( event );
		if ( wrapper ) {
			event.preventDefault();
			navigate( wrapper, true );
		}
	}

	function onKeyDown( event ) {
		if ( event.defaultPrevented || event.key !== 'Enter' || isEditor() ) {
			return;
		}

		if ( event.target instanceof Element && event.target.matches( SELECTOR ) ) {
			event.preventDefault();
			navigate( event.target, event.ctrlKey || event.metaKey );
		}
	}

	document.addEventListener( 'click', onClick );
	document.addEventListener( 'auxclick', onAuxClick );
	document.addEventListener( 'keydown', onKeyDown );
}() );
