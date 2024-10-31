/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { Component } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody, TextControl, ToggleControl} = wp.components;

/**
 * Inspector controls
 */
export default class Inspector extends Component {

    render() {
        const { attributes, setAttributes } = this.props;

        return (
            <InspectorControls key="inspector">
                <PanelBody title={ __( 'Settings', 'securiti-policy-notice' ) } >
                    <TextControl
                        label={  __('Iframe URL', 'securiti-policy-notice') }
                        value={ attributes.iframeSrc }
                        onChange={ ( value ) => { setAttributes( {iframeSrc: value } ) } }
                    />
                    <ToggleControl
                        label={ __('Allow fullscreen', 'securiti-policy-notice') }
                        checked={ attributes.allowFullscreen }
                        onChange={ ( value ) => { setAttributes( {allowFullscreen: value } ) } }
                    />
                    <ToggleControl
                        label={ __('Add lazyload attribute', 'securiti-policy-notice') }
                        checked={ attributes.useLazyload }
                        onChange={ ( value ) => { setAttributes( {useLazyload: value } ) } }
                    />
                </PanelBody>
                <PanelBody title={ __( 'Style options', 'securiti-policy-notice' ) } >
                    <TextControl
                        label={ __('Width', 'securiti-policy-notice') }
                        value={ attributes.iframeWidth }
                        onChange={ ( value ) => { setAttributes( {iframeWidth: value } ) } }
                    />
                    <TextControl
                        label={ __('Height', 'securiti-policy-notice') }
                        value={ attributes.iframeHeight }
                        onChange={ ( value ) => { setAttributes( {iframeHeight: value } ) } }
                    />
                    <ToggleControl
                        label={ __('Use !important', 'securiti-policy-notice') }
                        checked={ attributes.useImportant }
                        onChange={ ( value ) => { setAttributes( {useImportant: value } ) } }
                    />
                </PanelBody>
            </InspectorControls>
        );
    }

}
