import props from './variables';

class SearchEpisodes {

	/**
	 * Check if Search results page is open.
	 */
	isSrchOpen = false;
	
	/**
	 * Local search time-out.
	 */
	localTimeOut = null;

	/**
	 * Live Search time-out.
	 */
	serverTimeOut = null;

	/**
	 * Check if spinning wheel is visible.
	 */
	hasLoading = false;

	/**
	 * Save previously entered search term.
	 */
	prevSearchTerm = '';

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.podcast = props[id].podcast;
		this.instance = props[id].instance;
		this.isStyleSupport = props.isStyleSupport;
		this.data = window.podcastPlayerData || {};
		this.list = props[id].list;
		this.episodes = props[id].episodes;
		this.settings = props[id].settings;
		this.loadbtn = this.podcast.find('.episode-list__load-more');
		this.searchBox = this.list.find('.episode-list__search > input');
		this.searchResults = this.list.find('.episode-search');
		this.searchClose = this.list.find('.episode-list__clear-search');

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		this.searchBox.on('keyup', this.initSearch.bind(this));
		this.searchClose.click(this.clearSearch.bind(this));
	}

	/**
	 * Create markup for additional set of episodes (if any).
	 * 
	 * @since 1.3
	 */
	initSearch() {

		let searchTerm = '';

		// Open search results box. if not already opened.
		if ( false === this.isSrchOpen ) {
			this.podcast.addClass('search-opened');
			this.searchResults.addClass('toggled-on');
			this.loadbtn.hide();
			this.searchClose.show();
			this.isSrchOpen = true;
		}

		// Filter already loaded episodes.
		clearTimeout(this.localTimeOut);
		this.localTimeOut = setTimeout(function() {
			searchTerm = jQuery.trim( this.searchBox.val().toLowerCase() );
			if (searchTerm) {
				this.filterEpisodes(searchTerm);
			}
		}.bind(this), 100);

		// Get live search reults from the server.
		clearTimeout(this.serverTimeOut);
		this.serverTimeOut = setTimeout(function() {
			if (! searchTerm) {
				this.clearSearch();
			} else if (this.prevSearchTerm === searchTerm) {
				return;
			} else {
				this.liveSearch(searchTerm);
			}
		}.bind(this), 500);
	}

	/**
	 * Filter already displayed episode by search Term
	 * 
	 * @since 1.3
	 * 
	 * @param {string} searchTerm Current Search Term.
	 */
	filterEpisodes(searchTerm) {

		let episodeList = this.episodes.find('.episode-list__entry');
		episodeList.each(function() {
			if ( jQuery( this ).filter( '[data-search-term *= ' + searchTerm + ']' ).length > 0 || searchTerm.length < 1 ) {
				jQuery( this ).show();
			} else {
				jQuery( this ).hide();
			}
		});
	}

	/**
	 * Fetch search filtered episodes from the server.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} searchTerm 
	 */
	liveSearch(searchTerm) {

		// Display spinning wheel while we wait for results.
		if (false === this.hasLoading) {

			const wheelMarkup = jQuery('<div />', { class: 'episode-search__loading'})
				.html(this.settings.ppVidLoading);
			
			this.searchResults.html(wheelMarkup);
			this.hasLoading = true;
		}

		// Run live Ajax search and show results.
		this.fetchResults(searchTerm);
	}

	/**
	 * Fetch and display search results.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} searchTerm 
	 */
	fetchResults(searchTerm) {
		const pid  = `pp-podcast-${this.instance}`;
		const load = this.data[pid].rdata;
		if ( 'feedurl' === load.from ) {
			this.fetchFromFeed(searchTerm);
		} else if ( 'posts' === load.from ) {
			this.fetchFromPosts(searchTerm);
		}
	}

	/**
	 * Search and Fetch results from the RSS feed.
	 * 
	 * @since 2.0
	 * 
	 * @param {string} searchTerm
	 */
	fetchFromFeed(searchTerm) {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let data = {
			action  : 'pp_search_episodes',
			security: ajax.security,
			instance: 's',
			loaded  : load.displayed,
			maxItems: load.maxItems,
			feedUrl : load.src,
			sortby  : load.sortby,
			filterby: load.filterby,
			search  : searchTerm,
			args    : load.args
		};

		// No need to fetch, if all required episodes have already been displayed.
		if ( load.displayed >= load.maxItems ) {
			this.flushSearchResults();
			return;
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				let details = jQuery.parseJSON( response );
				if ( jQuery.isEmptyObject( details ) ) {
					this.flushSearchResults();
				} else {
					this.data.search = details.episodes;
					this.showSearchResults(details.episodes);
				}
			},
			error: () => {
				this.flushSearchResults();
			}
		} );
	}

	/**
	 * Search and Fetch results from posts.
	 * 
	 * @since 2.0
	 * 
	 * @param {string} searchTerm
	 */
	fetchFromPosts(searchTerm) {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let data = {
				action  : 'pp_search_posts',
				security: ajax.security,
				offset  : load.displayed,
				args    : load.args,
				instance: 's',
				search  : searchTerm
		};

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				let details = jQuery.parseJSON( response );
				if ( jQuery.isEmptyObject( details ) ) {
					this.flushSearchResults();
				} else {
					this.data.search = details.episodes;
					this.showSearchResults(details.episodes);
				}
			},
			error: () => {
				this.flushSearchResults();
			}
		} );
	}

	/**
	 * Clear search results.
	 * 
	 * @since 1.3
	 */
	clearSearch() {

		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;

		this.searchBox.val('');
		this.prevSearchTerm = '';
		this.isSrchOpen = false;

		this.podcast.removeClass('search-opened');
		this.searchResults.removeClass('toggled-on');
		this.searchClose.hide();
		this.episodes.find('.episode-list__entry').show();
		this.flushSearchResults();

		// If not all loaded episodes have been displayed.
		if ( load.displayed < load.loaded ) {
			this.loadbtn.show();
		}
	}

	/**
	 * Flush search results.
	 * 
	 * @since 1.3
	 */
	flushSearchResults() {

		this.searchResults.empty();
		this.hasLoading = false;
	}

	/**
	 * Show search results in the player.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} results Fetched search results.
	 */
	showSearchResults(results) {
		const pid = `pp-podcast-${this.instance}`;
		const details = this.data[pid].load_info;
		const arr = Object.getOwnPropertyNames( results );
		const nextList = arr.length - 1;
		const excerptLength = this.data[pid].rdata.elen;
		let i=0;
		let overallMarkup = jQuery( '<div />' );

		for( ; i <= nextList; i++  ) {

			let id = arr[i];
			let episode = results[id];
			let {title, description, author, date, link, featured} = episode;

			if ( !featured ) {
				featured = details.args.imgurl;
				featured = featured ? featured : '';
			}

			let linkMarkup = jQuery('<a />', { href: link }).html( title );
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
				<div id="${id}" class="episode-list__search-entry pod-entry" data-search-term="${title.toLowerCase()}">
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
				<div id="${id}" class="episode-list__search-entry pod-entry" data-search-term="${title.toLowerCase()}">
					<div class="pod-entry__content">
						${titleMarkup[0].outerHTML}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
					</div>
				</div>
				`;
			}
			overallMarkup.append(jQuery(markup));
			this.searchResults.html( overallMarkup.html() );
			this.hasLoading = false;
		}
	}
}

export default SearchEpisodes;
