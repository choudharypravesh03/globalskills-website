const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
	MediaUpload,
	PanelColorSettings
} = wp.editor;
const { apiFetch } = wp;
const {
	Dashicon,
	SelectControl,
	PanelBody,
	Button,
	Disabled,
	Placeholder,
	RangeControl,
	TextControl,
	TextareaControl,
	ToggleControl,
	Toolbar
} = wp.components;
const ServerSideRender = wp.serverSideRender;
const { BlockControls, InspectorControls } = wp.blockEditor

import MultipleCheckboxControl from './mcc';

class PodcastPlayer extends Component {
	constructor() {
		super( ...arguments );

		let chkEditing = ( ! this.props.attributes.feedURL && 'feed' === this.props.attributes.fetchMethod ) || ( ! this.props.attributes.audioSrc && 'link' === this.props.attributes.fetchMethod );

		this.state = {
			editing: chkEditing,
			fontFamilies: [],
			postTypes: [],
			taxonomies: [],
			termsList: [],
		};

		const mejsSettings = window.ppmejsSettings || {};
		this.isPremium = mejsSettings.isPremium;
		this.fetching = false;
		this.toggleAttribute = this.toggleAttribute.bind( this );
		this.onSubmitURL = this.onSubmitURL.bind( this );
	}

	apiDataFetch(data, path) {
		if (this.fetching) {
			setTimeout( this.apiDataFetch.bind(this, data, path), 200 );
			return;
		}
		this.fetching = true;
		apiFetch( {
			path: '/podcastplayer/v1/' + path,
		} )
		.then( ( items ) => {
			let itemsList = Object.keys(items);
			itemsList = itemsList.map(item => {
				return {
					label: items[item],
					value: item,
				};
			});
			this.setState({ [data]: itemsList });
			this.fetching = false;
		} )
		.catch( () => {
			this.setState({ [data]: [] });
			this.fetching = false;
		} );
	}

	componentDidMount() {
		if (!this.isPremium) return;
		const {attributes} = this.props;
		const {postType} = attributes;
		this.apiDataFetch('postTypes', 'posttypes');
		this.apiDataFetch('fontFamilies', 'fontfamily');
		if (postType) {
			this.updateTaxonomy();
			this.updateTerms();
		}
	}

	componentDidUpdate( prevProps ) {
		if (!this.isPremium) return;
		const { postType: oldPostType, taxonomy: oldTaxonomy, fontFamily: oldFontFamily } = prevProps.attributes;
		const { postType, taxonomy, fontFamily } = this.props.attributes;
		if (oldPostType !== postType) { this.updateTaxonomy() }
		if (oldTaxonomy !== taxonomy) { this.updateTerms() }
		if (oldFontFamily !== fontFamily) { this.updateFonts() }
	}

	updateTaxonomy() {
		const { attributes } = this.props;
		const { postType } = attributes;
		if (!postType) {
			this.setState( { taxonomies: [], termsList: [] } );
		} else {
			this.apiDataFetch('taxonomies', 'taxonomies/' + postType);
		}
	}

	updateTerms() {
		const { attributes } = this.props;
		const { taxonomy } = attributes;
		if (!taxonomy) {
			this.setState( { termsList: [] } );
		} else {
			this.apiDataFetch('termsList', 'terms/' + taxonomy);
		}
	}

	updateFonts() {
		const { fontFamily } = this.props.attributes;
		const { fontFamilies } = this.state;
		if (fontFamily) {
			const family = fontFamilies.filter(font => fontFamily === font.value);
			if (family.length) {
				const font = family[0].label;
				const fontName = font.split( ' ' ).join( '+' );
				if ( 0 === jQuery( 'link#podcast-player-fonts-css-temp' ).length ) {
					const gfontUrl = '//fonts.googleapis.com/css?family=' + fontName;
					const gfontlink = jQuery( '<link>', {
						id: 'podcast-player-fonts-css-temp',
						href: gfontUrl,
						rel: 'stylesheet',
						type: 'text/css'
					} );
					jQuery( 'link:last' ).after( gfontlink );
				} else {
					const elem = jQuery('link#podcast-player-fonts-css-temp');
					const href = elem.attr('href');
					elem.attr( 'href', href + '%7C' +fontName );
				}
			}
		}
	}

	toggleAttribute( propName ) {
		return () => {
			const value = this.props.attributes[ propName ];
			const { setAttributes } = this.props;

			setAttributes( { [ propName ]: ! value } );
		};
	}

	onSubmitURL( event ) {
		event.preventDefault();

		const { fetchMethod, feedURL, audioSrc } = this.props.attributes;
		if ( 'feed' === fetchMethod ) {
			if ( feedURL ) {
				this.setState( { editing: false } );
			}
		} else if ( 'link' === fetchMethod ) {
			if ( audioSrc ) {
				this.setState( { editing: false } );
			}
		}
	}

	navMenuSelect() {
		let ppData = window.podcastPlayerData.menu || {};
		ppData = Array.from(ppData);
		ppData.push( { label: '- Select Menu -', value: '' } );
		return ppData.map( ( item ) => {
			return {
				label: item.label,
				value: item.value,
			};
		} );
	}

	render() {
		const {
			feedURL,
			sortBy,
			filterBy,
			number,
			excerptLength,
			podcastMenu,
			coverImage,
			description,
			accentColor,
			displayStyle,
			aspectRatio,
			cropMethod,
			gridColumns,
			fetchMethod,
			postType,
			taxonomy,
			terms,
			podtitle,
			audioSrc,
			audioTitle,
			audioLink,
			headerDefault,
			hideHeader,
			hideTitle,
			hideCover,
			hideDesc,
			hideSubscribe,
			hideSearch,
			hideAuthor,
			hideContent,
			hideLoadmore,
			hideDownload,
			ahideDownload,
			hideSocial,
			ahideSocial,
			audioMsg,
			playFreq,
			msgStart,
			msgTime,
			msgText,
			fontFamily,
		} = this.props.attributes;
		const { postTypes, taxonomies, termsList, fontFamilies } = this.state;
		const { setAttributes } = this.props;
		const navMenu = this.navMenuSelect();
		const styles  = window.podcastPlayerData.style || { label: 'Default', value: '' };
		const ifStyleSupport = (style, item) => {
			const supported = window.podcastPlayerData.stSup || false;
			if (! supported || ! style) return false;
			return supported[style] ? supported[style].includes(item) : false;
		}
		const aspectOptions = [
			{ value: '', label: __( 'No Cropping', 'podcast-player' ) },
			{ value: 'land1', label: __( 'Landscape (4:3)', 'podcast-player' ) },
			{ value: 'land2', label: __( 'Landscape (3:2)', 'podcast-player' ) },
			{ value: 'port1', label: __( 'Portrait (3:4)', 'podcast-player' ) },
			{ value: 'port2', label: __( 'Portrait (2:3)', 'podcast-player' ) },
			{ value: 'wdscrn', label: __( 'Widescreen (16:9)', 'podcast-player' ) },
			{ value: 'squr', label: __( 'Square (1:1)', 'podcast-player' ) },
		];
		const cropOptions = [
			{ value: 'topleftcrop', label: __( 'Top Left Cropping', 'podcast-player' ) },
			{ value: 'topcentercrop', label: __( 'Top Center Cropping', 'podcast-player' ) },
			{ value: 'centercrop', label: __( 'Center Cropping', 'podcast-player' ) },
			{ value: 'bottomcentercrop', label: __( 'Bottom Center Cropping', 'podcast-player' ) },
			{ value: 'bottomleftcrop', label: __( 'Bottom Left Cropping', 'podcast-player' ) },
		];
		const onFetchChange = (value) => {
			setAttributes( { fetchMethod: value } );
			if ('post' === value) {
				this.setState( { editing: false } );
			} else {
				this.setState( { editing: true } );
			}
		}
		const onChangePostType = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: '' });
			setAttributes({ postType: value });
		};
		const onChangeTaxonomy = value => {
			setAttributes({ terms: [] });
			setAttributes({ taxonomy: value });
		};
		const termCheckChange = (value) => {
			const index = terms.indexOf(value);
			if (-1 === index) {
				setAttributes({ terms: [...terms, value] });
			} else {
				setAttributes({ terms: terms.filter(term => term !== value) });
			}
		};
		const onHourChange = e => {
			const hr = e.target.value;
			const min = msgTime[1] ? msgTime[1] : 0;
			const sec = msgTime[2] ? msgTime[2] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};
		const onMinuteChange = e => {
			const min = e.target.value;
			const hr  = msgTime[0] ? msgTime[0] : 0;
			const sec = msgTime[2] ? msgTime[2] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};
		const onSecondChange = e => {
			const sec = e.target.value;
			const hr  = msgTime[0] ? msgTime[0] : 0;
			const min = msgTime[1] ? msgTime[1] : 0;
			setAttributes({ msgTime: [hr, min, sec] });
		};

		if ( this.state.editing ) {
			return (
				<Fragment>
					<Placeholder
						icon="rss"
						label="RSS"
					>
						<form onSubmit={ this.onSubmitURL }>
							{
								'feed' === fetchMethod &&
								<TextControl
									placeholder={ __( 'Enter URL here…', 'podcast-player' ) }
									value={ feedURL }
									onChange={ ( value ) => setAttributes( { feedURL: value } ) }
									className={ 'components-placeholder__input' }
								/>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									placeholder={ __( 'Enter Valid Audio/Video File Link (i.e, mp3, ogg, m4a etc.)', 'podcast-player' ) }
									value={ audioSrc }
									onChange={ ( value ) => setAttributes( { audioSrc: value } ) }
									className={ 'components-placeholder__input' }
								/>
							}
							<Button isLarge type="submit">
								{ __( 'Use URL', 'podcast-player' ) }
							</Button>
						</form>
					</Placeholder>
					<InspectorControls>
					{
						!! this.isPremium &&
						<PanelBody initialOpen={ true } title={ __( 'Setup Fetching Method', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Fetch Podcast Episodes', 'podcast-player' ) }
								value={ fetchMethod }
								onChange={ onFetchChange }
								options={ [
									{ value: 'feed', label: __( 'from Feed', 'podcast-player' ) },
									{ value: 'post', label: __( 'from Post', 'podcast-player' ) },
									{ value: 'link', label: __( 'from Audio/Video URL', 'podcast-player' ) },
								] }
							/>
						</PanelBody>
					}
					</InspectorControls>
				</Fragment>
			);
		}

		const toolbarControls = [
			{
				icon: 'edit',
				title: __( 'Edit RSS URL', 'podcast-player' ),
				onClick: () => this.setState( { editing: true } ),
			},
		];

		return (
			<Fragment>
				<BlockControls>
					<Toolbar controls={ toolbarControls } />
				</BlockControls>
				<InspectorControls>
					{
						!! this.isPremium &&
						<PanelBody initialOpen={ true } title={ __( 'Setup Fetching Method', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Fetch Podcast Episodes', 'podcast-player' ) }
								value={ fetchMethod }
								onChange={ onFetchChange }
								options={ [
									{ value: 'feed', label: __( 'from Feed', 'podcast-player' ) },
									{ value: 'post', label: __( 'from Post', 'podcast-player' ) },
									{ value: 'link', label: __( 'from Audio/Video URL', 'podcast-player' ) },
								] }
							/>
							{
								(postTypes && 'post' === fetchMethod) &&
								<SelectControl
									label={ __( 'Select Post Type', 'podcast-player' ) }
									value={ postType }
									options={ postTypes }
									onChange={ (value) => onChangePostType(value) }
								/>
							}
							{
								(postType && !! taxonomies.length && 'post' === fetchMethod) &&
								<SelectControl
									label={ __( 'Get items by Taxonomy', 'podcast-player' ) }
									value={ taxonomy }
									options={ taxonomies }
									onChange={ ( value ) => onChangeTaxonomy(value) }
								/>
							}
							{
								(!! termsList.length && 'post' === fetchMethod) &&
								<MultipleCheckboxControl
									listItems={ termsList }
									selected={ terms }
									onItemChange={ termCheckChange }
									label = { __( 'Select Taxonomy Terms', 'podcast-player' ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									label={ __( 'Episode Title', 'podcast-player' ) }
									value={ audioTitle }
									onChange={ ( value ) => setAttributes( { audioTitle: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<TextControl
									label={ __( 'Podcast episode link for social sharing (optional)', 'podcast-player' ) }
									value={ audioLink }
									onChange={ ( value ) => setAttributes( { audioLink: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Episode Download Link', 'podcast-player' ) }
									checked={ !! ahideDownload }
									onChange={ ( value ) => setAttributes( { ahideDownload: value } ) }
								/>
							}
							{
								'link' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Social Share Links', 'podcast-player' ) }
									checked={ !! ahideSocial }
									onChange={ ( value ) => setAttributes( { ahideSocial: value } ) }
								/>
							}
						</PanelBody>
					}
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Change Podcast Content', 'podcast-player' ) }>
							{
								this.isPremium && 'post' === fetchMethod &&
								<TextControl
									label={ __( 'Podcast Title', 'podcast-player' ) }
									value={ podtitle }
									onChange={ ( value ) => setAttributes( { podtitle: value } ) }
								/>
							}
							<MediaUpload
								onSelect={ ( media ) => setAttributes( { coverImage: media.url } ) }
								type="image"
								value={ coverImage }
								render={ ( { open } ) => (
									<Button className="pp-cover-btn" onClick={ open }>
										{ ! coverImage ?
											<div className="no-image">
												<Dashicon icon="format-image" />
												{ __( 'Upload Cover Image', 'podcast-player' ) }
											</div> :
											<img
												className="ppe-cover-image"
												src={ coverImage }
												alt={ __( 'Cover Image', 'podcast-player' ) }
											/>
										}
									</Button>
								) }
							>
							</MediaUpload>
							{
								coverImage &&
								<Button className="remove-pp-cover" onClick={ () => setAttributes( { coverImage: '' } ) }>
									{ __( 'Remove Cover Image', 'podcast-player' ) }
								</Button>
							}
							<TextareaControl
								label={ __( 'Brief Description', 'podcast-player' ) }
								help={ __( 'Change Default Podcast Description', 'podcast-player' ) }
								value={ description }
								onChange={ ( value ) => setAttributes( { description: value } ) }
							/>
							<RangeControl
								label={ __( 'Number of episodes to show at a time', 'podcast-player' ) }
								value={ number }
								onChange={ ( value ) => setAttributes( { number: value } ) }
								min={ 1 }
								max={ 100 }
							/>
							{
								ifStyleSupport(displayStyle, 'excerpt') &&
								<RangeControl
									label={ __( 'Excerpt Length', 'podcast-player' ) }
									value={ excerptLength }
									onChange={ ( value ) => setAttributes( { excerptLength: value } ) }
									min={ 0 }
									max={ 200 }
								/>
							}
							<SelectControl
								label={ __( 'Podcast Custom Menu', 'podcast-player' ) }
								value={ podcastMenu }
								onChange={ ( value ) => setAttributes( { podcastMenu: value } ) }
								options={ navMenu }
							/>
						</PanelBody>
					}
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Show/Hide Player Items', 'podcast-player' ) }>
							{
								!displayStyle &&
								<ToggleControl
									label={ __( 'Show Podcast Header by Default', 'podcast-player' ) }
									checked={ !! headerDefault }
									onChange={ ( value ) => setAttributes( { headerDefault: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Podcast Header', 'podcast-player' ) }
								checked={ !! hideHeader }
								onChange={ ( value ) => setAttributes( { hideHeader: value } ) }
							/>
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide cover image', 'podcast-player' ) }
									checked={ !! hideCover }
									onChange={ ( value ) => setAttributes( { hideCover: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Podcast Title', 'podcast-player' ) }
									checked={ !! hideTitle }
									onChange={ ( value ) => setAttributes( { hideTitle: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Podcast Description', 'podcast-player' ) }
									checked={ !! hideDesc }
									onChange={ ( value ) => setAttributes( { hideDesc: value } ) }
								/>
							}
							{
								!hideHeader &&
								<ToggleControl
									label={ __( 'Hide Custom menu', 'podcast-player' ) }
									checked={ !! hideSubscribe }
									onChange={ ( value ) => setAttributes( { hideSubscribe: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Podcast Search', 'podcast-player' ) }
								checked={ !! hideSearch }
								onChange={ ( value ) => setAttributes( { hideSearch: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Episode Author/Podcaster Name', 'podcast-player' ) }
								checked={ !! hideAuthor }
								onChange={ ( value ) => setAttributes( { hideAuthor: value } ) }
							/>
							{
								'feed' === fetchMethod &&
								<ToggleControl
									label={ __( 'Hide Episode Text Content/Transcript', 'podcast-player' ) }
									checked={ !! hideContent }
									onChange={ ( value ) => setAttributes( { hideContent: value } ) }
								/>
							}
							<ToggleControl
								label={ __( 'Hide Load More Episodes Button', 'podcast-player' ) }
								checked={ !! hideLoadmore }
								onChange={ ( value ) => setAttributes( { hideLoadmore: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Episode Download Link', 'podcast-player' ) }
								checked={ !! hideDownload }
								onChange={ ( value ) => setAttributes( { hideDownload: value } ) }
							/>
							<ToggleControl
								label={ __( 'Hide Social Share Links', 'podcast-player' ) }
								checked={ !! hideSocial }
								onChange={ ( value ) => setAttributes( { hideSocial: value } ) }
							/>
						</PanelBody>
					}
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Podcast Player Styling', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Podcast Player Display Style', 'podcast-player' ) }
								value={ displayStyle }
								onChange={ ( value ) => setAttributes( { displayStyle: value } ) }
								options={ styles }
							/>
							{
								ifStyleSupport(displayStyle, 'thumbnail') &&
								<SelectControl
									label={ __( 'Thumbnail Cropping', 'podcast-player' ) }
									value={ aspectRatio }
									onChange={ ( aspectRatio ) => setAttributes( { aspectRatio } ) }
									options={ aspectOptions }
								/>
							}
							{
								(ifStyleSupport(displayStyle, 'thumbnail') && aspectRatio) &&
								<SelectControl
									label={ __( 'Thumbnail Cropping Position', 'podcast-player' ) }
									value={ cropMethod }
									onChange={ ( cropMethod ) => setAttributes( { cropMethod } ) }
									options={ cropOptions }
								/>
							}
							{
								ifStyleSupport(displayStyle, 'grid') &&
								<RangeControl
									label={ __( 'Grid Columns', 'podcast-player' ) }
									value={ gridColumns }
									onChange={ ( value ) => setAttributes( { gridColumns: value } ) }
									min={ 2 }
									max={ 6 }
								/>
							}
							{
								!!this.isPremium &&
								<SelectControl
									label={ __( 'Select Font Family', 'podcast-player' ) }
									value={ fontFamily }
									options={ fontFamilies }
									onChange={ ( value ) => setAttributes( { fontFamily: value } ) }
								/>
							}
							<PanelColorSettings
								title={ __( 'Podcast Player Color Scheme', 'podcast-player' ) }
								initialOpen={ false }
								colorSettings={ [
									{
										value: accentColor,
										onChange: ( value ) => setAttributes( { accentColor: value } ),
										label: __( 'Accent Color', 'podcast-player' ),
									},
								] }
							>
							</PanelColorSettings>
						</PanelBody>
					}
					{
						'link' !== fetchMethod &&
						<PanelBody initialOpen={ false } title={ __( 'Sort & Filter Options', 'podcast-player' ) }>
							<SelectControl
								label={ __( 'Sort Podcast Episodes By', 'podcast-player' ) }
								value={ sortBy }
								onChange={ ( value ) => setAttributes( { sortBy: value } ) }
								options={ [
									{ value: 'sort_title_desc', label: __( 'Title Descending', 'podcast-player' ) },
									{ value: 'sort_title_asc', label: __( 'Title Ascending', 'podcast-player' ) },
									{ value: 'sort_date_desc', label: __( 'Date Descending', 'podcast-player' ) },
									{ value: 'sort_date_asc', label: __( 'Date Ascending', 'podcast-player' ) },
								] }
							/>
							<TextControl
								label={ __( 'Show episodes only if title contains following', 'podcast-player' ) }
								value={ filterBy }
								onChange={ ( value ) => setAttributes( { filterBy: value } ) }
							/>
						</PanelBody>
					}
					{
						!!this.isPremium &&
						<PanelBody initialOpen={ false } title={ __( 'Custom Audio Message', 'podcast-player' ) }>
							<TextControl
								label={ __( 'Enter URL of mp3 audio file to be played', 'podcast-player' ) }
								value={ audioMsg }
								onChange={ ( value ) => setAttributes( { audioMsg: value } ) }
							/>
							<RangeControl
								label={ __( 'Replay Frequency', 'podcast-player' ) }
								help={ __( 'After how many episodes the audio should be replayed', 'podcast-player' ) }
								value={ playFreq }
								onChange={ ( value ) => setAttributes( { playFreq: value } ) }
								min={ 0 }
								max={ 100 }
							/>
							<SelectControl
								label={ __( 'When to start playing the audio message', 'podcast-player' ) }
								value={ msgStart }
								onChange={ ( value ) => setAttributes( { msgStart: value } ) }
								options={ [
									{ value: 'start', label: __( 'Start of the Episode', 'podcast-player' ) },
									{ value: 'end', label: __( 'End of the Episode', 'podcast-player' ) },
									{ value: 'custom', label: __( 'Custom Time', 'podcast-player' ) },
								] }
							/>
							{
								( msgStart && 'custom' === msgStart ) &&
								<div className="components-base-control">
									<label className="components-base-control__label">{ __( 'Start playing audio at (time in hh:mm:ss)' ) }</label>
									<div className="components-datetime__time-field components-datetime__time-field-time">
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 10 }
											value={ msgTime[0] }
											onChange={ onHourChange }
										/>
										<span
											className="components-datetime__time-separator"
											aria-hidden="true">:</span>
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 59 }
											value={ msgTime[1] }
											onChange={ onMinuteChange }
										/>
										<span
											className="components-datetime__time-separator"
											aria-hidden="true">:</span>
										<input
											className="components-datetime__time-field-hours-input"
											type="number"
											step={ 1 }
											min={ 0 }
											max={ 59 }
											value={ msgTime[2] }
											onChange={ onSecondChange }
										/>
									</div>
								</div>
							}
							<TextControl
								label={ __( 'Message to be displayed while playing audio.', 'podcast-player' ) }
								value={ msgText }
								onChange={ ( value ) => setAttributes( { msgText: value } ) }
							/>
						</PanelBody>
					}
				</InspectorControls>
				<Disabled>
					<ServerSideRender
						block="podcast-player/podcast-player"
						attributes={ this.props.attributes }
					/>
				</Disabled>
			</Fragment>
		);
	}
}

export default PodcastPlayer;
