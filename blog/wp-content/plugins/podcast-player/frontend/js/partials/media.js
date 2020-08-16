import props from './variables';

class MediaElements {

	/**
	 * Media Elements JS display control.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.podcast = props[id].podcast;
		this.mediaObj = props[id].mediaObj;
		this.msgMediaObj = props[id].msgMediaObj;
		this.controls = jQuery(this.mediaObj.controls);
		this.msgControls = this.msgMediaObj ? jQuery(this.msgMediaObj.controls) : false;
		this.layers = this.controls.prev('.ppjs__layers');
		this.media = this.mediaObj.media;
		this.msgMedia = this.msgMediaObj ? this.msgMediaObj.media : false;
		this.modalObj = props[id].modal;
		this.settings = props[id].settings;
		this.transcript = props[id].episode;
		this.list = props[id].list;
		this.props = props[id];
		this.instance = props[id].instance;
		this.player = props[id].player;
		this.data = window.podcastPlayerData || {};

		this.modControlMarkup();
		this.modLayersMarkup();

		this.plBtn = this.controls.find( '.ppjs__playpause-button > button' );
		this.forBtn = this.controls.find( '.ppjs__jump-forward-button > button' );
		this.bckBtn = this.controls.find( '.ppjs__skip-backward-button > button' );
		this.ttBtn = this.controls.find( '.ppjs__script-button > button' );
		this.ssBtn = this.controls.find( '.ppjs__share-button > button' );
		this.pbrBtn = this.controls.find( '.ppjs__play-rate-button > button' );
		if (this.msgControls) {
			this.msgplBtn = this.msgControls.find( '.ppjs__playpause-button > button' );
			this.msgforBtn = this.msgControls.find( '.ppjs__jump-forward-button > button' );
			this.msgbckBtn = this.msgControls.find( '.ppjs__skip-backward-button > button' );
		}

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		// Toggle play button class on play or pause events.
		this.media.addEventListener('loadedmetadata', this.condbtnPauseMedia.bind(this));
		this.media.addEventListener('play', this.btnPlayMedia.bind(this));
		this.media.addEventListener('playing', this.btnPlayMedia.bind(this));
		this.media.addEventListener('pause', this.btnPauseMedia.bind(this));
		this.forBtn.click(this.forwardMedia.bind(this));
		this.bckBtn.click(this.skipbackMedia.bind(this));
		this.ttBtn.click(this.showtranscript.bind(this));
		this.ssBtn.click(this.showsocialshare.bind(this));
		this.pbrBtn.click(this.mediaPlayRate.bind(this));
		this.podcast.find('.episode-single__close').click(this.hidetranscript.bind(this));
		if (this.msgMedia) {
			this.msgMedia.addEventListener('loadedmetadata', this.msgcondbtnPauseMedia.bind(this));
			this.msgMedia.addEventListener('play', this.msgbtnPlayMedia.bind(this));
			this.msgMedia.addEventListener('playing', this.msgbtnPlayMedia.bind(this));
			this.msgMedia.addEventListener('pause', this.msgbtnPauseMedia.bind(this));
		}
		if (this.msgControls) {
			this.msgplBtn.click(this.msgPlayPause.bind(this));
			this.msgforBtn.click(this.msgForwardMedia.bind(this));
			this.msgbckBtn.click(this.msgSkipbackMedia.bind(this));
		}
	}

	/**
	 * Forward audio by specified amount of time.
	 */
	forwardMedia() {

		const interval = 15;
		let currentTime;
		let duration;

		duration = !isNaN(this.media.duration) ? this.media.duration : interval;
		currentTime = ( this.media.currentTime === Infinity ) ? 0 : this.media.currentTime;
		this.media.setCurrentTime(Math.min(currentTime + interval, duration));
		this.forBtn.blur();
	}

	/**
	 * Skip back media by specified amount of time.
	 * 
	 * @since 1.3
	 */
	skipbackMedia() {

		const interval = 15;
		let currentTime;

		currentTime = ( this.media.currentTime === Infinity ) ? 0 : this.media.currentTime;
		this.media.setCurrentTime(Math.max(currentTime - interval, 0));
		this.bckBtn.blur();
	}

	/**
	 * Play/pause media on button click.
	 * 
	 * @since 1.3
	 */
	msgPlayPause() {

		if (this.msgMediaObj.paused) {
			this.msgMediaObj.play();
		} else {
			this.msgMediaObj.pause();
		}
	}

	/**
	 * Forward audio by specified amount of time.
	 */
	msgForwardMedia() {

		const interval = 15;
		let currentTime;
		let duration;

		duration = !isNaN(this.msgMedia.duration) ? this.msgMedia.duration : interval;
		currentTime = ( this.msgMedia.currentTime === Infinity ) ? 0 : this.msgMedia.currentTime;
		this.msgMedia.setCurrentTime(Math.min(currentTime + interval, duration));
		this.msgforBtn.blur();
	}

	/**
	 * Skip back media by specified amount of time.
	 * 
	 * @since 1.3
	 */
	msgSkipbackMedia() {

		const interval = 15;
		let currentTime;

		currentTime = ( this.msgMedia.currentTime === Infinity ) ? 0 : this.msgMedia.currentTime;
		this.msgMedia.setCurrentTime(Math.max(currentTime - interval, 0));
		this.msgbckBtn.blur();
	}

	/**
	 * Change media play back rate.
	 * 
	 * @since 2.0
	 */
	mediaPlayRate() {
		const curItem = this.pbrBtn.find('.current');
		let nxtItem = curItem.next('.pp-rate');
		const times   = this.pbrBtn.find('.pp-times');
		if (0 === nxtItem.length ) nxtItem = this.pbrBtn.find('.pp-rate').first();
		const num = parseFloat(nxtItem.text());
		curItem.removeClass('current');
		nxtItem.addClass('current');
		if (nxtItem.hasClass('withx')) {
			times.show();
		} else {
			times.hide();
		}
		this.media.playbackRate = num;
		this.pbrBtn.blur();
	}

	/**
	 * Manage button class for playing media.
	 */
	btnPlayMedia() {

		this.plBtn.addClass('playing');
		if (!this.podcast.hasClass('postview')) {
			if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
		}
	}

	/**
	 * Manage button class for pausing media.
	 */
	btnPauseMedia() {

		this.plBtn.removeClass('playing');
	}

	/**
	 * Show podcast transcript.
	 */
	showtranscript() {

		this.transcript.slideToggle('fast');
		this.ttBtn.parent().toggleClass('toggled-on');
	}

	/**
	 * Hide podcast transcript.
	 */
	hidetranscript() {

		this.transcript.slideUp('fast');
		this.ttBtn.parent().removeClass('toggled-on');
	}

	/**
	 * Show podcast transcript.
	 */
	showsocialshare() {
		const player = this.ssBtn.closest('.pp-podcast__player');
		const socialWrapper = player.siblings('.pod-content__social-share');

		socialWrapper.slideToggle('fast');
		this.ssBtn.parent().toggleClass('toggled-on');
	}

	/**
	 * Conditionally manage button for media.
	 */
	condbtnPauseMedia() {

		if (this.media.rendererName.indexOf('flash') === -1) {
			this.plBtn.removeClass( 'playing' );
		}
	}

	/**
	 * Manage button class for playing media.
	 */
	msgbtnPlayMedia() {

		this.msgplBtn.addClass('playing');
	}

	/**
	 * Manage button class for pausing media.
	 */
	msgbtnPauseMedia() {

		this.msgplBtn.removeClass('playing');
	}

	/**
	 * Conditionally manage button for media.
	 */
	msgcondbtnPauseMedia() {

		if (this.msgMedia.rendererName.indexOf('flash') === -1) {
			this.msgplBtn.removeClass( 'playing' );
		}
	}

	/**
	 * Modify media controls markup
	 * 
	 * @since 1.3
	 */
	modControlMarkup() {

		const pid = `pp-podcast-${this.instance}`;
		const id = `ppe-${this.instance}-1`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;
		let tempMarkup, episodeTitle, details, featured;

		if ( this.data[pid] ) {
			details = this.data[pid][id] ? this.data[pid][id] : {};
			featured = details.featured;
		}

		if (this.mediaObj.isVideo) {

			// Add SVG icons to video control section.
			this.controls.prepend(this.settings.ppPlayPauseBtn);
			this.controls.find('.ppjs__fullscreen-button > button').html(this.settings.ppMaxiScrnBtn + this.settings.ppMiniScrnBtn);

			// Add featured image as video poster.
			if (featured) {
				this.layers.find('.ppjs__poster').empty().append('<img src="'+ featured + '"/>').show();
			}
		} else {
			
			// Add forward and backward buttons to audio control section.
			this.controls.find('.ppjs__time').wrapAll('<div class="ppjs__audio-timer" />');
			this.controls.find('.ppjs__time-rail').wrap('<div class="ppjs__audio-time-rail" />');
			tempMarkup = jQuery('<div />', { class: 'ppjs__audio-controls' });
			tempMarkup.html(this.settings.ppAudioControlBtns);
			this.controls.prepend(tempMarkup);
			if ( this.props.isWide ) {
				episodeTitle = this.transcript.find('.episode-single__title').text();
				this.controls.find('.ppjs__episode-title').text(episodeTitle);
			}

			if (!this.podcast.hasClass('postview')) {
				if (featured) {
					this.player.find('.ppjs__img-btn').attr('src', featured);
					this.player.addClass('hasCover');
				} else {
					this.player.removeClass('hasCover');
				}
			}
		}

		if (this.msgMediaObj) {
			this.msgControls.find('.ppjs__time').wrapAll('<div class="ppjs__audio-timer" />');
			this.msgControls.find('.ppjs__time-rail').wrap('<div class="ppjs__audio-time-rail" />');
			tempMarkup = jQuery('<div />', { class: 'ppjs__audio-controls' });
			tempMarkup.html(this.settings.ppAudioControlBtns);
			this.msgControls.prepend(tempMarkup);
			if ( this.props.isWide ) {
				if (rdata && 'undefined' !== typeof(rdata.msgtext)) {
					this.msgControls.find('.ppjs__episode-title').text(rdata.msgtext);
				}
			}
		}
	}

	/**
	 * Modify mediaelement layers markup
	 * 
	 * @since 1.3
	 */
	modLayersMarkup() {

		// Add SVG icon markup to media layers elements.
		this.layers.find( '.ppjs__overlay-play > .ppjs__overlay-button' ).html( this.settings.ppPlayCircle );
		this.layers.find( '.ppjs__overlay > .ppjs__overlay-loading' ).html( this.settings.ppVidLoading );
	}
}

export default MediaElements;
