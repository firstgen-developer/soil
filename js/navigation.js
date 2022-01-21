/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	// const siteNavigation = document.getElementById( 'site-navigation' );
	// console.log(siteNavigation)
	// Return early if the navigation don't exist.
	// if ( ! siteNavigation ) {
	// 	return;
	// }

	// const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];
	

	// Return early if the button don't exist.
	// if ( 'undefined' === typeof button ) {
	// 	return;
	// }

	// const menu = document.getElementById( 'primary-menu-list' );
	// console.log(menu)

	// console.log(Object.entries(siteNavigation.childNodes))

	// Hide menu toggle button if menu is empty and return early.
	// if ( 'undefined' === typeof menu ) {
	// 	button.style.display = 'none';
	// 	return;
	// }

	// if ( ! menu.classList.contains( 'nav-menu' ) ) {
	// 	menu.classList.add( 'nav-menu' );
	// }
	
	const button = document.getElementById( 'hamburger-btn' );
	const menu = document.querySelector(".mobile-nav")
	const hamburgerInner = document.querySelector('.hamburger-inner')
	// const after = window.getComputedStyle(hamburgerInner, '::after')
	// console.log(after['background-color'])
	// after.style.backgroundColor = 'white'


	button.addEventListener('click', function() {
		button.classList.toggle('is-active');
		menu.classList.toggle('showMenu');
		hamburgerInner.classList.toggle('white-hamburger')


	});

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	// button.addEventListener( 'click', function() {
	// 	siteNavigation.classList.toggle( 'toggled' );
	// 	// button.setAttribute('aria-expanded', true)
		
	
	// 	console.log(button.getAttribute('aria-expanded') )
	// 	if ( button.getAttribute( 'aria-expanded' ) == 'true' ) {
	// 		button.setAttribute( 'aria-expanded', 'false' );
	// 	} else {
	// 		button.setAttribute( 'aria-expanded', 'true' );
	// 	}
	// } );

	// button.addEventListener( 'click', function(e){
	// 	e.preventDefault()

	// });


	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	// document.addEventListener( 'click', function( event ) {
	// 	const isClickInside = siteNavigation.contains( event.target );

	// 	if ( ! isClickInside ) {
	// 		siteNavigation.classList.remove( 'toggled' );
	// 		button.setAttribute( 'aria-expanded', 'false' );
	// 	}
	// } );

	// Get all the link elements within the menu.
	// const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	// const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	// for ( const link of links ) {
	// 	link.addEventListener( 'focus', toggleFocus, true );
	// 	link.addEventListener( 'blur', toggleFocus, true );
	// }

	// Toggle focus each time a menu link with children receive a touch event.
	// for ( const link of linksWithChildren ) {
	// 	link.addEventListener( 'touchstart', toggleFocus, false );
	// }

	/**
	 * Sets or removes .focus class on an element.
	 */
	// function toggleFocus() {
	// 	if ( event.type === 'focus' || event.type === 'blur' ) {
	// 		let self = this;
	// 		// Move up through the ancestors of the current link until we hit .nav-menu.
	// 		while ( ! self.classList.contains( 'nav-menu' ) ) {
	// 			// On li elements toggle the class .focus.
	// 			if ( 'li' === self.tagName.toLowerCase() ) {
	// 				self.classList.toggle( 'focus' );
	// 			}
	// 			self = self.parentNode;
	// 		}
	// 	}

	// 	if ( event.type === 'touchstart' ) {
	// 		const menuItem = this.parentNode;
	// 		event.preventDefault();
	// 		for ( const link of menuItem.parentNode.children ) {
	// 			if ( menuItem !== link ) {
	// 				link.classList.remove( 'focus' );
	// 			}
	// 		}
	// 		menuItem.classList.toggle( 'focus' );
	// 	}
	// }
}() );


// ( function() {
//     var nav = document.getElementById( 'site-navigation' ), button, menu;
//     if ( ! nav ) {
//         return;
//     }
 
//     button = nav.getElementsByTagName( 'button' )[0];
//     menu   = nav.getElementsByTagName( 'ul' )[0];
//     if ( ! button ) {
//         return;
//     }
 
//     // Hide button if menu is missing or empty.
//     if ( ! menu || ! menu.childNodes.length ) {
//         button.style.display = 'none';
//         return;
//     }
 
//     button.onclick = function() {
//         if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
//             menu.className = 'nav-menu';
//         }
 
//         if ( -1 !== button.className.indexOf( 'toggled-on' ) ) {
//             button.className = button.className.replace( ' toggled-on', '' );
//             menu.className = menu.className.replace( ' toggled-on', '' );
//         } else {
//             button.className += ' toggled-on';
//             menu.className += ' toggled-on';
//         }
//     };
// } )(jQuery);

