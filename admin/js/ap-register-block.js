/**
 * Register Agnoplay video block.
 */

const { MediaUpload, MediaUploadCheck } = wp.blockEditor
const { Button } = wp.components

const agnoLogo = React.createElement("img", {
	src: imagePath, // Set in class-agnoplay-wordpress-admin.phpse
});

wp.blocks.registerBlockType('ap/agnoplay', {
	title: 'Agnoplay',
	icon: agnoLogo,
	category: 'media',
	keywords: ['agno', 'video', 'audio', 'live', 'player'],
	attributes: {
		inputType: {string: 'string', default: 'preconfigured'},
		blockId: {type: 'string'},
		id: {type: 'string'},
		src: {type: 'string'},
		variant: {type: 'string', default: 'video'},
		title: {type: 'string'},
		mediaID: {type: Number},
		mediaURL: {type: 'string'},
		mediaThumb: {type: 'string'},
	},
	edit: function(props) {
		const {
			attributes: {
				blockId,
			},
			clientId,
			setAttributes,
		} = props;

		setAttributes({ blockId: clientId });

		return React.createElement(
			'div',
			null,
			// Title of block in editor
			React.createElement(
				'h3',
				{ className: 'ap-title' },
				'Agnoplay'
			),
			props.attributes.variant === 'live' && React.createElement(
				'div',
				{ className: 'ap-info' },
				'You can configure an active livestream in Agnoplay Live Studio.'
			),
			// Input type
			props.attributes.variant !== 'live' && React.createElement(
				'div',
				{ className: 'ap-input-container' },
				React.createElement(
					'label',
					{
						className: 'ap-label',
						htmlFor: 'agnoplay-input-type'
					},
					'Input type'
				),
				React.createElement(
					'input',
					{
						className: 'ap-radio',
						id: 'ap-radio-preconfigured',
						name: 'ap-input-type',
						type: 'radio',
						value: 'preconfigured',
						checked: props.attributes.inputType === 'preconfigured',
						onChange: function (event) { props.setAttributes({inputType: event.target.value}) }
					}
				),
				React.createElement(
					'label',
					{
						className: 'ap-radio-label',
						htmlFor: 'ap-radio-preconfigured'
					},
					'Preconfigured'
				),
				React.createElement(
					'input',
					{
						className: 'ap-radio',
						id: 'ap-radio-custom',
						name: 'ap-input-type',
						type: 'radio',
						value: 'custom',
						checked: props.attributes.inputType === 'custom',
						onChange: function (event) { props.setAttributes({inputType: event.target.value}) }
					}
				),
				React.createElement(
					'label',
					{
						className: 'ap-radio-label',
						htmlFor: 'ap-radio-custom'
					},
					'Custom configured'
				),
			),
			props.attributes.inputType === 'custom' && React.createElement(
				'div',
				{
					className: 'ap-info',
				},
				'In order to use a custom source, a thumbnail is required to be configured too.'
			),
			// Player variant
			React.createElement(
				'div',
				{ className: 'ap-input-container' },
				React.createElement(
					'label',
					{
						className: 'ap-label',
						htmlFor: 'agnoplay-variant'
					},
					'Select player type'
				),
				React.createElement(
					'select',
					{
						type: 'agnoplay-variant',
						value: props.attributes.variant,
						className: 'ap-input ap-select',
						key: 'variant',
						onChange: function (event) { props.setAttributes({variant: event.target.value}) }
					},
					React.createElement('option', {value: 'video'}, 'Videoplayer'),
					React.createElement('option', {value: 'audio'}, 'Audioplayer'),
					// Only show liveplayer option when the preconfigured input type is selected as custom configuration
					// is not supported by the liveplayer
					props.attributes.inputType === 'preconfigured' && React.createElement('option', {value: 'live'}, 'Liveplayer')
				),
			),
			// Video title
			props.attributes.variant !== 'live' && React.createElement(
				'div',
				{ className: 'ap-input-container' },
				React.createElement(
					'label',
					{
						className: 'ap-label',
						htmlFor: 'agnoplay-video-title'
					},
					'Video title'
				),
				React.createElement(
					'input',
					{
						type: 'agnoplay-video-title',
						value: props.attributes.title,
						className: 'ap-input ap-text',
						key: 'title',
						required: props.attributes.inputType === 'custom',
						placeholder: 'Agnoplay video title',
						onChange: function (event) { props.setAttributes({title: event.target.value}) }
					}
				),
			),
			// Video ID
            (props.attributes.inputType === 'preconfigured' && props.attributes.variant !== 'live') && React.createElement(
			'div',
			{ className: 'ap-input-container' },
			React.createElement(
				'label',
				{
					className: 'ap-label',
					htmlFor: 'agnoplay-video-id',
				},
				'Video ID*'
			),
			React.createElement(
				'input',
				{
					type: 'agnoplay-video-id',
					value: props.attributes.id,
					className: 'ap-input ap-text',
					key: 'videoID',
					required: props.attributes.inputType === 'default',
					placeholder: 'Agnoplay video ID',
					onChange: function (event) { props.setAttributes({id: event.target.value}) }
				}
			),
			),
			// Custom source
			props.attributes.inputType === 'custom' && React.createElement(
			'div',
			{ className: 'ap-input-container' },
			React.createElement(
				'label',
				{
					className: 'ap-label',
					htmlFor: 'agnoplay-custom-src'
				},
				'Custom source*'
			),
			React.createElement(
				'input',
				{
					type: 'agnoplay-custom-src',
					value: props.attributes.src,
					className: 'ap-input ap-text',
					key: 'customSrc',
					required: props.attributes.inputType === 'custom',
					placeholder: 'Agnoplay custom source',
					onChange: function (event) { props.setAttributes({src: event.target.value}) }
				}
			),
			),
			// Thumbnail
			props.attributes.variant !== 'live' && React.createElement(
				'div',
				{ className: 'ap-input-container' },
				React.createElement(
					'label',
					{
						className: 'ap-label',
						htmlFor: 'agnoplay-thumbnail'
					},
					`Thumbnail${props.attributes.inputType === 'custom' ? '*' : ''}`
				),
				React.createElement(MediaUploadCheck,
					null,
					React.createElement(
						MediaUpload,
						{
							allowedTypes: ['image'],
							multiple: false,
							value: props.attributes.mediaID,
							onSelect: function(media) {
								// ID is used for MediaUpload value
								// URL is printed in the shortcode
								// Thumb is used for a preview within the upload button
								props.setAttributes({
									mediaID: media.id,
									mediaURL: media.url,
									mediaThumb: media.sizes.full.url
								});
							},
							render: function({open}) {
								return React.createElement(
									Button,
									{
										className: 'editor-post-featured-image__preview editor-post-featured-image__toggle',
										onClick: open
									},
									!props.attributes.mediaID && 'Choose or upload media',
									props.attributes.mediaID && React.createElement(
										'img',
										{
											src: props.attributes.mediaThumb,
											className: 'ap-preview-image',
										},
									)
								);
							},
						},
					),
				),
				props.attributes.mediaID && React.createElement(MediaUploadCheck,
					null,
					React.createElement(
						Button,
						{
							onClick: function(media) {
								props.setAttributes({
									mediaID: null,
									mediaURL: null,
									mediaThumb: null
								});
							},
							isLink: true,
							isDestructive: true
						},
						'Remove image'
					),
				),
			),
		);
	},
	save: function(props) {
		// Output custom shortcode
		return wp.element.createElement(
			'div',
			{ className: 'agnoplayer-block' },
			`[agnoplay
				inputType="${props.attributes.inputType}"
				blockId="${props.attributes.blockId}"
				id="${props.attributes.id}"
				src="${typeof props.attributes.src !== 'undefined' ? props.attributes.src : ""}"
				title="${typeof props.attributes.title !== 'undefined' ? props.attributes.title : ""}"
				variant="${props.attributes.variant}"
				thumbnail="${typeof props.attributes.mediaURL !== 'undefined' ? props.attributes.mediaURL : "null"}"
			]`
		);
	}
})
