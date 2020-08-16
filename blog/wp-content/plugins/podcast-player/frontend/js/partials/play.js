import props from './variables';

class PlayEpisode {

	/**
	 * Currently clicked list item.
	 */
	listItem;

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.id = id;
		this.podcast = props[id].podcast;
		this.list = props[id].list;
		this.episode = props[id].episode;
		this.player = props[id].player;
		this.mediaObj = props[id].mediaObj;
		this.instance = props[id].instance;
		this.modalObj = props[id].modal;
		this.singleWrap = props[id].singleWrap;
		this.data = window.podcastPlayerData || {};
		this.msgMediaObj = props[id].msgMediaObj;
		this.msgControls = this.msgMediaObj ? jQuery(this.msgMediaObj.controls) : false;
		this.msgMedia = this.msgMediaObj ? this.msgMediaObj.media : false;
		this.msgFreqCounter = 0;
		this.controls = jQuery(this.mediaObj.controls);
		this.plBtn = this.controls.find( '.ppjs__playpause-button > button' );
		this.playingAmsg = false;
		this.playAmsg = false;
		this.played = false;
		this.settings = props[id].settings;
		this.isPremium = this.settings.isPremium;

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		const _this = this;
		if (! _this.podcast.hasClass('postview')) {
			_this.list.on('click', '.episode-list__entry, .episode-list__search-entry', function(e) {
				e.preventDefault();
				_this.listItem = jQuery(this);
				_this.play();
			});
		} else {
			_this.list.on('click', '.pod-entry__title a, .pod-entry__featured', function(e) {
				const $this = jQuery(this);
				if ( $this.hasClass('fetch-post-title') ) return;
				const pid = `pp-podcast-${_this.instance}`;
				const info = _this.data[pid].load_info;
				let hideDescription = info ? (info.args ? info.args.hddesc : false) : false;
				hideDescription = hideDescription ? hideDescription : false;
				const isModalView = (! $this.hasClass('pod-entry__featured') && ! hideDescription) || _this.mediaObj.isVideo;
				e.preventDefault();
				_this.listItem = $this.closest('.pod-entry');
				_this.playModal(isModalView);
			});
		}

		if (this.msgMedia) {
			this.msgMedia.addEventListener('ended', this.msgMediaEnded.bind(this));
		}
		this.mediaObj.media.addEventListener('ended', this.mediaEnded.bind(this));
		this.plBtn.click(this.playPauseBtn.bind(this));
	}

	/**
	 * Common actions before plating podcast episode.
	 * 
	 * @since 2.0
	 */
	common() {
		const pid = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;
		const id = this.listItem.attr('id');
		let share = this.singleWrap.find('.pod-content__social-share');
		let active, details, ppurl, pptitle, src;

		// Remove active class from previously active episode.
		active = this.list.find('.activeEpisode')
		if ( 0 < active.length ) {
			active.removeClass( 'activeEpisode media-playing' );
		}

		if (this.msgMediaObj) {
			if ( ! this.msgMediaObj.paused ) {
				this.msgMediaObj.pause();
			}
			this.msgMediaObj.currentTime = 0;
		}

		this.played = true;
		this.playingAmsg = false;
		this.player.removeClass('msg-playing');

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		// Generate social sharing links.
		ppurl   = encodeURIComponent(details.link);
		pptitle = encodeURIComponent(details.title);
		src = jQuery("<div>").html(details.src).html().replace("&amp;", "&");

		if (this.isPremium && false !== rdata && 'feedurl' === rdata.from) {
			if ('undefined' !== typeof details.key) {
				const query = {
					ppplayer : rdata.fprint,
					ppepisode: details.key,
				}
				const qstr = jQuery.param( query );
				const plink = rdata.permalink;
				ppurl = plink ? plink + ( plink.indexOf('?') < 0 ? '?' : '&') + qstr: ppurl;
				ppurl = encodeURIComponent(ppurl);
			}
		}

		const fburl = "https://www.facebook.com/sharer.php?u=" + ppurl;
		const twurl = "https://twitter.com/intent/tweet?url=" + ppurl + "&text=" + pptitle;
		const liurl = "https://www.linkedin.com/shareArticle?mini=true&url=" + ppurl;
		const mail  = "mailto:?subject=" + pptitle + "&body=Link:" + ppurl;

		this.listItem.addClass( 'activeEpisode media-playing' );
		this.episode.find( '.episode-single__title' ).html( details.title );
		this.episode.find( '.episode-single__author > .single-author' ).html( details.author );
		this.controls.find('.ppjs__episode-title').html(details.title);
		this.episode.find( '.episode-single__description' ).html( details.description );
		share.find( '.ppsocial__facebook' ).attr( 'href', fburl );
		share.find( '.ppsocial__twitter' ).attr( 'href', twurl );
		share.find( '.ppsocial__linkedin' ).attr( 'href', liurl );
		share.find( '.ppsocial__email' ).attr( 'href', mail );
		share.find( '.ppshare__download' ).attr( 'href', src );
		this.mediaObj.setSrc( src );
		this.mediaObj.load();
		this.playMessage();
		return true;
	}

	/**
	 * Display btn image.
	 * 
	 * @since 2.3
	 */
	btnImage() {
		const pid = `pp-podcast-${this.instance}`;
		const id = this.listItem.attr('id');
		let details;

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		const { featured } = details;

		if (featured) {
			this.player.find('.ppjs__img-btn').attr('src', featured);
			this.player.addClass('hasCover');
		} else {
			this.player.removeClass('hasCover');
		}
	}

	/**
	 * Play/pause media on button click.
	 * 
	 * @since 1.3
	 */
	playPauseBtn() {

		// Playing the podcast for the first time after page load.
		if (false === this.played) {
			this.played = true;
			this.playMessage();
			if (!this.playingAmsg) this.mediaObj.play();
			return;
		}

		if (this.mediaObj.paused) {
			this.mediaObj.play();
			if (this.listItem) this.listItem.addClass('activeEpisode media-playing');
		} else {
			this.mediaObj.pause();
			if (this.listItem) this.listItem.removeClass('activeEpisode media-playing');
		}
	}

	/**
	 * Play episode in player view.
	 * 
	 * @since 1.3
	 */
	play() {

		// If clicked on the currently playing episode.
		if (this.listItem.hasClass( 'activeEpisode' )) {
			this.listItem.removeClass( 'activeEpisode' );
			this.mediaObj.pause();
			this.player.removeClass('msg-playing');
			this.playingAmsg = false;
			if (this.msgMediaObj) {
				this.msgMediaObj.pause();
				this.msgMediaObj.currentTime = 0;
			}
			return;
		}

		if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
			this.modalObj.returnElem();
		}

		// Perform common actions before plating podcast.
		this.common();
		this.btnImage()

		// Auto play the media.
		if (!this.playingAmsg) this.mediaObj.play();

		// Scroll window to top of the single episode for better UX.
		jQuery( 'html, body' ).animate({ scrollTop: this.player.offset().top - 200 }, 400 );
	}

	/**
	 * Play episode in post view.
	 * 
	 * Episodes will be played in a Modal window.
	 * 
	 * @since 2.0
	 * 
	 * @param {bool} isModalView
	 */
	playModal(isModalView) {
		if (! this.modalObj) return;
		// If current episode is already playing. Let's pause it.
		if (this.listItem.hasClass('activeEpisode')) {
			if (isModalView) {
				this.modalObj.modal.removeClass('inline-view').addClass('modal-view');
				this.modalObj.scrollDisable();
				if (!this.playingAmsg) {
					const wrapper = this.modalObj.modal.find('.episode-primary__title');
					let customTitle = wrapper.find('.episode-single__title');
					customTitle.html(this.episode.find( '.episode-single__title' ).html());
					this.mediaObj.play();
					this.modalObj.modal.removeClass('media-paused');
					this.listItem.addClass('media-playing');
				}
			} else {
				if (this.msgMediaObj && this.playingAmsg) {
					if (!this.msgMediaObj.paused) {
						this.msgMediaObj.pause();
						this.modalObj.modal.addClass('media-paused');
						this.listItem.removeClass('media-playing');
					} else {
						this.msgMediaObj.play();
						this.modalObj.modal.removeClass('media-paused');
						this.listItem.addClass('media-playing');
					}
				} else if (!this.mediaObj.paused) {
					this.mediaObj.pause();
					this.modalObj.modal.addClass('media-paused');
					this.listItem.removeClass('media-playing');
				} else {
					this.mediaObj.play();
					this.modalObj.modal.removeClass('media-paused');
					this.listItem.addClass('media-playing');
				}
			}
			return;
		}

		// Perform common actions before playing podcast.
		this.common();

		if (!this.singleWrap.hasClass('activePodcast')) {
			if (this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
			this.modalObj.create(this.singleWrap, this.mediaObj, this.msgMediaObj, isModalView);
			this.singleWrap.addClass('activePodcast');
		} else {
			if (isModalView) {
				const wrapper = this.modalObj.modal.find('.episode-primary__title');
				let customTitle = wrapper.find('.episode-single__title');
				customTitle.html(this.episode.find( '.episode-single__title' ).html());
				this.modalObj.modal.removeClass('inline-view').addClass('modal-view');
				this.modalObj.scrollDisable();
			}
		}

		// Auto play the media.
		if (!this.playingAmsg) this.mediaObj.play();
		this.modalObj.modal.removeClass('media-paused');
	}

	/**
	 * Play appropriate media.
	 * 
	 * @since 2.5.0
	 */
	playMessage() {
		const pid  = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;

		// Set episode src, if custom audio message is not set.
		if (!rdata || 'undefined' === typeof(rdata.audiomsg)) {
			return;
		}

		if (rdata.playfreq <= this.msgFreqCounter || false === this.played) {
			this.msgFreqCounter = 0;
			if ('start' === rdata.msgstart) {
				this.playingAmsg = true;
				this.player.addClass('msg-playing');
				if (this.msgMediaObj) this.msgMediaObj.play();
			} else if ('end' === rdata.msgstart) {
				this.playAmsg = true;
			} else if ('custom' === rdata.msgstart) {
				const time = rdata.msgtime[0] * 60 * 60 + rdata.msgtime[1] * 60 + rdata.msgtime[2];
				this.deferredPlay(time);
			}
		} else {
			this.msgFreqCounter++;
			this.playingAmsg = false;
		}
	}

	/**
	 * Deferred play media message.
	 * 
	 * @since 2.5.0
	 * 
	 * @param int time
	 */
	deferredPlay(time) {
		if (time) {
			const currentTime = this.mediaObj.currentTime;
			if (currentTime && currentTime >= time) {
				this.playingAmsg = true;
				this.mediaObj.pause();
				if (this.msgMediaObj) this.msgMediaObj.play();
				this.player.addClass('msg-playing');
			} else {
				setTimeout(() => { this.deferredPlay(time) }, 1000);
			}
		}
	}

	/**
	 * Actions when current media has ended.
	 * 
	 * @since 2.5.0
	 */
	mediaEnded() {

		if (true === this.playAmsg) {
			this.playingAmsg = false;
			this.player.addClass('msg-playing');
			if (this.msgMediaObj) this.msgMediaObj.play();
		}
	}

	/**
	 * Actions when current media has ended.
	 * 
	 * @since 2.5.0
	 */
	msgMediaEnded() {

		if (true === this.playingAmsg) {
			this.player.removeClass('msg-playing');
			this.playingAmsg = false;
			this.mediaObj.play();
		}
	}
}

export default PlayEpisode;
