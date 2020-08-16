import props from './variables';

class LoadmoreEpisodes {

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.podcast = props[id].podcast;
		this.settings = props[id].settings;
		this.instance = props[id].instance;
		this.isStyleSupport = props.isStyleSupport;
		this.data = window.podcastPlayerData || {};
		this.loadbtn = this.podcast.find('.episode-list__load-more');

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		this.loadbtn.click(this.loadEpisodes.bind(this));
	}

	/**
	 * Create markup for additional set of episodes (if any).
	 * 
	 * @since 1.3
	 */
	loadEpisodes() {
		const pid = `pp-podcast-${this.instance}`;
		const from = this.data[pid].rdata.from;
		let details = this.data[pid].load_info;
		const excerptLength = this.data[pid].rdata.elen;
		const nextList = Math.min(details.displayed + details.step, details.loaded);
		let i = details.displayed + 1;
		let overallMarkup = jQuery( '<div />' );

		let epititle = '';
		if ( 'posts' === from ) {
			epititle = 'fetch-post-title';
		}

		for( ; i <= nextList; i++  ) {

			let id = `ppe-${this.instance}-${i}`;
			let episode = this.data[pid][id];

			if ( 'undefined' !== typeof(episode) ) {
				let {title, description, author, date, link, featured} = episode;

				if ( !featured ) {
					featured = details.args.imgurl;
					featured = featured ? featured : '';
				}

				let linkMarkup = jQuery('<a />', { href: link, class: epititle }).html( title );
				let titleMarkup = jQuery('<div />', { class: 'pod-entry__title' }).html( linkMarkup );
				let dateMarkup = jQuery('<div />', { class: 'pod-entry__date' }).text( date );
				let authorMarkup = jQuery('<div />', { class: 'pod-entry__author' }).html( author );
				let markup;

				if (this.podcast.hasClass('postview')) {
					const style = details.args.display;
					const fullText = description ? jQuery(description).text() : '';
					const pplay = jQuery('<div />', { class: 'pod-entry__play' }).html( this.settings.ppPlayCircle + this.settings.ppPauseBtn );
					let imgMarkup, eHtml = '';
					if (style && this.isStyleSupport(style, 'playbtn')) {
						imgMarkup = '';
					} else {
						imgMarkup = featured ? jQuery('<img />', { class: 'pod-entry__image', src: featured }) : '';
						imgMarkup = imgMarkup ? `<div class="pod-entry__thumb">${imgMarkup[0].outerHTML}</div>` : '';
					}
					if (style && this.isStyleSupport(style, 'excerpt')) {
						const excerpt = fullText ? fullText.split(' ').splice(0,excerptLength).join(' ') : '';
						const eMarkup = excerpt ? jQuery('<div />', { class: 'pod-entry__excerpt' }).text( excerpt + '...' ) : '';
						eHtml = eMarkup ? eMarkup[0].outerHTML : '';
					}
					markup = `
					<div id="${id}" class="episode-list__entry pod-entry" data-search-term="${title.toLowerCase()}">
						<div class="pod-entry__featured">
							${pplay[0].outerHTML}
							${imgMarkup}
						</div>
						<div class="pod-entry__content">
							${titleMarkup[0].outerHTML}${eHtml}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
						</div>
					</div>
					`;
				} else {
					markup = `
					<div id="${id}" class="episode-list__entry pod-entry" data-search-term="${title.toLowerCase()}">
						<div class="pod-entry__content">
							${titleMarkup[0].outerHTML}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
						</div>
					</div>
					`;
				}
				overallMarkup.append(jQuery(markup));
			}
		}

		this.loadbtn.parent().before(overallMarkup.html());

		// Update number of post displayed in the podcast player.
		details.displayed = nextList;

		// Fetch more episodes using Ajax.
		this.fetchEpisodes();
	}

	/**
	 * Fetch more episodes from the server using Ajax.
	 * 
	 * @since 1.3
	 */
	fetchEpisodes() {
		const pid  = `pp-podcast-${this.instance}`;
		const load = this.data[pid].rdata;
		if ( 'feedurl' === load.from ) {
			this.fetchFromFeed();
		} else if ( 'posts' === load.from ) {
			this.fetchFromPosts();
		}
	}

	/**
	 * Fetch more episodes from the RSS feed.
	 * 
	 * @since 2.0
	 */
	fetchFromFeed() {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let data = {
				action  : 'pp_fetch_episodes',
				security: ajax.security,
				instance: this.instance,
				loaded  : load.loaded,
				maxItems: load.maxItems,
				feedUrl : load.src,
				step    : load.step,
				sortby  : load.sortby,
				filterby: load.filterby,
				args    : load.args
			};

		// If all required episodes have already been loaded.
		if ( load.loaded >= load.maxItems ) {

			// If all loaded episodes have already been displayed.
			if ( load.displayed >= load.loaded ) {
				this.loadbtn.slideUp( 'slow' );
			}

			// No need to run ajax request.
			return;
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = jQuery.parseJSON( response );

				// Update total number of episodes fetched.
				load.loaded = details.loaded;

				// Update episodes collection object.
				jQuery.extend( true, this.data[pid], details.episodes );
			},
			error: () => {
				this.loadbtn.hide();
			}
		} );
	}

	/**
	 * Fetch more episodes from the Posts.
	 * 
	 * @since 2.0
	 */
	fetchFromPosts() {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let data = {
				action  : 'pp_fetch_posts',
				security: ajax.security,
				instance: this.instance,
				offset  : load.offset,
				loaded  : load.loaded,
				args    : load.args,
			};

		// If all required episodes have already been loaded.
		if ( 0 === load.offset ) {
			this.loadbtn.slideUp( 'slow' );

			// No need to run ajax request.
			return;
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = jQuery.parseJSON( response );
				if (jQuery.isEmptyObject(details)) {
					load.offset = 0;
					this.loadbtn.slideUp( 'slow' );
				} else {
					// Update total number of episodes fetched.
					load.loaded = details.loaded;

					// Update episodes collection object.
					jQuery.extend( true, this.data[pid], details.episodes );
					load.offset += load.step;
				}
			},
			error: () => {
				this.loadbtn.hide();
			}
		} );
	}
}

export default LoadmoreEpisodes;
