let podcastVariables = {
	/**
	 * Enable scroll on the element that scrolls the document.
	 * 
	 * @since 1.2.3
	 */
	isStyleSupport(style, item) {
		const supported = window.ppmejsSettings.stSup || false;
		if (! supported || ! style) return false;
		return supported[style].includes(item);
	}
};
export default podcastVariables;