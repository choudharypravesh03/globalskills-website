import props from './variables';

class PodcastHeader {

	/**
	 * Screen resize timeout.
	 */
	resizeTimeout = null;

	/**
	 * Manage podcast player header elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID. 
	 */
	constructor(id) {

		// Define variables.
		this.id = id;
		this.podcast = props[id].podcast;
		this.infoToggle = this.podcast.find( '.pod-info__toggle' );
		this.podInfo = this.podcast.find( '.pod-info__header' );

		// Run methods.
		this.events();
	}

	// Event handling.
	events() {

		this.infoToggle.click( function() {
			this.podInfo.slideToggle( 'fast' );
			this.infoToggle.toggleClass( 'toggled-on' ).attr( 'aria-expanded', this.infoToggle.hasClass( 'toggled-on' ) );
		}.bind(this) );
	}
}

export default PodcastHeader;
