/**
 * Custom URL for Elementor – makes elements with [data-cufe-url] behave like links.
 *
 * - Clicks on nested links, buttons and form fields keep their own behaviour.
 * - Ctrl/Cmd/Shift + click and middle click open in a new tab, like a normal link.
 * - Enter activates the element when it has keyboard focus.
 * - Only http(s), mailto, tel and sms URLs are followed.
 * - Elementor action links (Popup, Lightbox dynamic tags) are run in place.
 * - On-page #anchors scroll smoothly, with an optional offset for sticky headers.
 * - Links can be disabled per device, and clicks can be sent to GTM / GA4.
 *
 * Developers can listen for the cancelable `cufe:click` event on the element;
 * calling preventDefault() on it stops the navigation.
 */
( function () {
	'use strict';

	var SELECTOR = '[data-cufe-url]';
	var INTERACTIVE = 'a[href], button, input, select, textarea, label, summary, video, audio, iframe, [contenteditable=""], [contenteditable="true"], [role="button"], [role="link"], [role="tab"], [role="menuitem"], [data-cufe-ignore]';
	var ALLOWED_PROTOCOLS = [ 'http:', 'https:', 'mailto:', 'tel:', 'sms:' ];
	var ACTION_PREFIX = /^(#|%23)elementor-action/i;
	var OFF_CLASS = 'cufe-link--off';

	function isEditor() {
		var frontend = window.elementorFrontend;
		return document.body.classList.contains( 'elementor-editor-active' ) ||
			!! ( frontend && typeof frontend.isEditMode === 'function' && frontend.isEditMode() );
	}

	function getDeviceMode() {
		var frontend = window.elementorFrontend;
		try {
			if ( frontend && typeof frontend.getCurrentDeviceMode === 'function' ) {
				return frontend.getCurrentDeviceMode() || 'desktop';
			}
		} catch ( e ) {}
		return document.body.getAttribute( 'data-elementor-device-mode' ) || 'desktop';
	}

	function isDisabled( wrapper ) {
		var devices = wrapper.getAttribute( 'data-cufe-disable' );
		return !! devices && devices.split( ' ' ).indexOf( getDeviceMode() ) !== -1;
	}

	// Keep link semantics in sync with the per-device "Disable Link On" setting.
	function syncDisabled() {
		var wrappers = document.querySelectorAll( '[data-cufe-disable]' );
		for ( var i = 0; i < wrappers.length; i++ ) {
			var wrapper = wrappers[ i ];
			var off = isDisabled( wrapper );
			wrapper.classList.toggle( OFF_CLASS, off );
			if ( off ) {
				wrapper.removeAttribute( 'role' );
				wrapper.removeAttribute( 'tabindex' );
			} else {
				wrapper.setAttribute( 'role', 'link' );
				wrapper.setAttribute( 'tabindex', '0' );
			}
		}
	}

	function safeUrl( raw ) {
		try {
			var url = new URL( raw, window.location.href );
			return ALLOWED_PROTOCOLS.indexOf( url.protocol ) !== -1 ? url : null;
		} catch ( e ) {
			return null;
		}
	}

	function findTarget( event ) {
		if ( ! ( event.target instanceof Element ) ) {
			return null;
		}

		var wrapper = event.target.closest( SELECTOR );
		if ( ! wrapper || isDisabled( wrapper ) ) {
			return null;
		}

		// Let nested interactive elements (and nested clickable wrappers) handle their own clicks.
		var interactive = event.target.closest( INTERACTIVE );
		if ( interactive && interactive !== wrapper && wrapper.contains( interactive ) ) {
			return null;
		}

		return wrapper;
	}

	function getLabel( wrapper ) {
		var label = wrapper.getAttribute( 'aria-label' ) || wrapper.textContent || '';
		return label.replace( /\s+/g, ' ' ).trim().slice( 0, 100 );
	}

	function track( wrapper, url ) {
		var eventName = wrapper.getAttribute( 'data-cufe-track' );
		if ( ! eventName ) {
			return;
		}

		var params = {
			link_url: url,
			link_text: getLabel( wrapper ),
			element_id: wrapper.getAttribute( 'data-id' ) || ''
		};

		try {
			// Prefer GTM when it is present; gtag also writes to dataLayer, so using both would double count.
			if ( window.google_tag_manager && Array.isArray( window.dataLayer ) ) {
				window.dataLayer.push( Object.assign( { event: eventName }, params ) );
			} else if ( typeof window.gtag === 'function' ) {
				window.gtag( 'event', eventName, Object.assign( { transport_type: 'beacon' }, params ) );
			} else if ( Array.isArray( window.dataLayer ) ) {
				window.dataLayer.push( Object.assign( { event: eventName }, params ) );
			}
		} catch ( e ) {}
	}

	function runElementorAction( raw, event ) {
		var frontend = window.elementorFrontend;
		if ( frontend && frontend.utils && frontend.utils.urlActions && typeof frontend.utils.urlActions.runAction === 'function' ) {
			frontend.utils.urlActions.runAction( raw, event );
		}
	}

	function focusTarget( target ) {
		if ( ! target.hasAttribute( 'tabindex' ) && ! target.matches( 'a[href], button, input, select, textarea' ) ) {
			target.setAttribute( 'tabindex', '-1' );
		}
		target.focus( { preventScroll: true } );
	}

	// Smoothly scrolls to an anchor on the current page. Returns false when the URL points elsewhere.
	function scrollToAnchor( wrapper, url ) {
		var here = window.location;
		if ( ! url.hash || url.origin + url.pathname + url.search !== here.origin + here.pathname + here.search ) {
			return false;
		}

		var id = decodeURIComponent( url.hash.slice( 1 ) );
		var target = id && ( document.getElementById( id ) || document.querySelector( '[name="' + CSS.escape( id ) + '"]' ) );
		if ( ! target ) {
			return false;
		}

		var offset = parseInt( wrapper.getAttribute( 'data-cufe-offset' ), 10 ) || 0;
		var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		window.scrollTo( {
			top: target.getBoundingClientRect().top + window.scrollY - offset,
			behavior: reduceMotion ? 'auto' : 'smooth'
		} );

		if ( window.history && window.history.pushState ) {
			window.history.pushState( null, '', url.hash );
		}

		focusTarget( target );
		return true;
	}

	function activate( wrapper, event, forceNewTab ) {
		var raw = wrapper.getAttribute( 'data-cufe-url' ) || '';
		var isAction = ACTION_PREFIX.test( raw );
		var url = isAction ? null : safeUrl( raw );

		if ( ! isAction && ! url ) {
			return;
		}

		var newTab = ! isAction && ( forceNewTab || wrapper.getAttribute( 'data-cufe-target' ) === '_blank' );
		var detail = { url: isAction ? raw : url.href, newTab: newTab, originalEvent: event };
		var custom = new CustomEvent( 'cufe:click', { bubbles: true, cancelable: true, detail: detail } );

		if ( ! wrapper.dispatchEvent( custom ) ) {
			return;
		}

		track( wrapper, detail.url );

		if ( isAction ) {
			event.preventDefault();
			runElementorAction( raw, event );
		} else if ( newTab ) {
			window.open( url.href, '_blank', 'noopener' );
		} else if ( scrollToAnchor( wrapper, url ) ) {
			event.preventDefault();
		} else {
			window.location.assign( url.href );
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

		activate( wrapper, event, event.ctrlKey || event.metaKey || event.shiftKey );
	}

	function onAuxClick( event ) {
		if ( event.defaultPrevented || event.button !== 1 || isEditor() ) {
			return;
		}

		var wrapper = findTarget( event );
		if ( wrapper && ! ACTION_PREFIX.test( wrapper.getAttribute( 'data-cufe-url' ) || '' ) ) {
			event.preventDefault();
			activate( wrapper, event, true );
		}
	}

	function onKeyDown( event ) {
		if ( event.defaultPrevented || event.key !== 'Enter' || isEditor() ) {
			return;
		}

		var wrapper = event.target;
		if ( wrapper instanceof Element && wrapper.matches( SELECTOR ) && ! isDisabled( wrapper ) ) {
			event.preventDefault();
			activate( wrapper, event, event.ctrlKey || event.metaKey );
		}
	}

	var resizeFrame = 0;
	function onResize() {
		window.cancelAnimationFrame( resizeFrame );
		resizeFrame = window.requestAnimationFrame( syncDisabled );
	}

	document.addEventListener( 'click', onClick );
	document.addEventListener( 'auxclick', onAuxClick );
	document.addEventListener( 'keydown', onKeyDown );

	if ( document.querySelector( '[data-cufe-disable]' ) ) {
		syncDisabled();
		window.addEventListener( 'load', syncDisabled );
		window.addEventListener( 'resize', onResize );
	}
}() );
