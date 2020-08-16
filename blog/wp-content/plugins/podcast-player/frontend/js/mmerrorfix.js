/* global MediaElementPlayer */
(function ( window ) {
	var init = MediaElementPlayer.prototype.init;
	MediaElementPlayer.prototype.init = function () {

		if ( 'pp-podcast-episode' === this.node.className ) {
			this.options.classPrefix = 'ppjs__';
		}
		init.call( this );
	};

})( window );