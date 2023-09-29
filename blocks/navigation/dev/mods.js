/**
 * Block editor script.
 *
 * @package    Classic Menu in Navigation Block
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since  1.0.0
 */

( ( wp ) => {
	'use strict';

	// Variables

		const
			{ addFilter } = wp.hooks,
			Editor        = wp.blockEditor,
			Comp          = wp.components,
			Fragment      = wp.element.Fragment,
			Element       = wp.element.createElement,
			HOComponent   = wp.compose.createHigherOrderComponent;


	// Processing

		addFilter(
			'blocks.registerBlockType',
			'classic-menu-in-navigation-block/mods/add-attributes',
			( settings, name ) => {

				// Requirements check

					if ( 'core/navigation' !== name ) {
						return settings;
					}


				// Processing

					// Adding post type attribute.
					settings.attributes.menuLocation = {
						type    : 'string',
						default : '',
					};


				// Output

					return settings;
			}
		);

		addFilter(
			'editor.BlockEdit',
			'classic-menu-in-navigation-block/mods/add-controls',
			( BlockEdit ) => {

				// Variables

					const withInspectorControls = HOComponent( ( BlockEdit ) => { return ( props ) => {

						// Requirements check

							if ( 'core/navigation' !== props.name ) {
								return Element( BlockEdit, props );
							}


						// Variables

							const
								{ menuLocations, texts } = ClassicMenuInNavigationBlockData,
								{ menuLocation }         = props.attributes


						// Output

							return Element( Fragment, {},
								Element( BlockEdit, props ),
								Element( Editor.InspectorControls, {},
									Element( Comp.PanelBody,
										{
											title       : texts.panel.title,
											initialOpen : false,
										},

										// Description/help.
										Element( 'p', {},
											texts.panel.description + ' ',
											Element( 'a', { href : texts.link.url }, texts.link.label )
										),

										// Menu location select.
										Element( Comp.SelectControl,
											{
												label    : texts.control.location.label,
												help     : texts.control.location.description,
												value    : menuLocation,
												onChange : ( newValue ) => props.setAttributes( { menuLocation: newValue } ),
												options  : menuLocations,
											}
										),

										// Notice.
										Element( 'p', {},
											Element( Comp.Dashicon, { icon : texts.notice.dashicon } ),
											Element( 'em', {}, ' ' + texts.notice.content )
										)
									)
								)
							);

					} } );


				// Output

					return withInspectorControls( BlockEdit );
			}
		);

} )( window.wp );
