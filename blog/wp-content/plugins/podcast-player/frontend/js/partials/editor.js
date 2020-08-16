import props from './variables';
import Podcast from './podcast';
import Modal from './modal';

( $ => {

	'use strict';

	const settings = window.ppmejsSettings || {};
	const modal = settings.isPremium ? new Modal() : '';
	document.addEventListener( 'animationstart', playerAdded, false ); // Standard + firefox
	document.addEventListener( 'webkitAnimationStart', playerAdded, false ); // Chrome + Safari

	function playerAdded(e) {
		if ('playerAdded' !== e.animationName) {
			return;
		}

		if (! $(e.target).hasClass('pp-podcast') ) {
			return;
		}

		if ($(e.target).hasClass('pp-podcast-added')) {
			return;
		}

		const podcast = $(e.target);
		const id = podcast.attr('id');
		const mediaObj = new MediaElementPlayer( id + '-player', settings );
		const list = podcast.find('.pod-content__list');
		const episode = podcast.find('.pod-content__episode');
		const episodes = list.find('.episode-list__wrapper');
		const single = episode.find('.episode-single__wrapper');
		const singleWrap = podcast.find('.pp-podcast__single');
		const player = podcast.find('.pp-podcast__player');
		props[id] = {
			podcast, mediaObj, settings, list, episode,
			episodes, single, player, modal, singleWrap,
			instance: id.replace( 'pp-podcast-', '' ),
		};

		podcast.addClass('pp-podcast-added');

		new Podcast(id);
	}
})(jQuery);
