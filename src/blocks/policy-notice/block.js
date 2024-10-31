import Inspector from './inspector';
import icons from './icon'


const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { Placeholder } = wp.components;
const { registerBlockType } = wp.blocks;


registerBlockType( 'securiti/securiti-policy-notice', {

	title: __('Privacy Policy Generator & Notice Management', 'securiti-policy-notice'),
	description: __('Generate GDPR & CCPA Compliant Privacy Policies and Notices onto your website in minutes. Update legal pages for blogs, ecommerce and marketing websites.', 'securiti-policy-notice'),
	keywords: [ __( 'privacy notice' ), __( 'policy' ), __( 'securiti' )],
	category: 'embed',
	icon: icons.privacy_notice,

	supports: {
		anchor: true,
		className: false,
		customClassName: true,
		align: ['full'],
	},

	attributes: {
		iframeSrc: {
			type: 'string',
		},
		iframeWidth: {
			type: 'string',
		},
		iframeHeight: {
			type: 'string',
		},
		allowFullscreen: {
			type: 'boolean',
		},
		useLazyload: {
			type: 'boolean',
		},
		useImportant: {
			type: 'boolean',
		}
	},

	edit: function( props ) {

		const { attributes } = props;

		let customClassName = [attributes.className];
		if(attributes.align == 'full') customClassName.push('alignfull');

        const iframeStyle = {
            width: attributes.iframeWidth || '100%',
            maxWidth: attributes.iframeWidth || '100%',
            height: attributes.iframeHeight || '320px'
        };

        let allow = {};
        if(attributes.allowFullscreen) allow.allowFullscreen = true;

        const block = attributes.iframeSrc
			? <iframe
				id={ attributes.anchor }
				className={ customClassName.join(' ') }
				src={ attributes.iframeSrc }
				style={ iframeStyle }
				frameBorder="0"
				{ ...allow }
				></iframe>
			: <Placeholder
				icon="admin-site-alt"
				label={ __('Please fill the Iframe URL', 'securiti-policy-notice') }
				/>;

        return (
			<Fragment>
				<Inspector { ...props } />
				{ block }
			</Fragment>
		);
	},

	save: function( props ) {

		const { attributes } = props;

		let customClassName = [attributes.className];
		if(attributes.align == 'full') customClassName.push('alignfull');

        const iframeStyle = {
            width: attributes.iframeWidth || '100%',
            maxWidth: attributes.iframeWidth || '100%',
            height: attributes.iframeHeight || '320px'
        };

        if(attributes.useImportant){
        	for(let i in iframeStyle){ iframeStyle[i] += ' !important'; }
		}

        let allow = {};
        if( attributes.allowFullscreen ) allow.allowFullscreen = true;

        return (
            <Fragment>
                <iframe
                    style={ iframeStyle }
					id={ attributes.anchor }
                    src={ attributes.iframeSrc }
					class={ customClassName.join(' ') }
					loading={ attributes.useLazyload ? 'lazy' : false}
                    frameBorder="0"
                    { ...allow }
                ></iframe>
            </Fragment>
        );
	},

} );
