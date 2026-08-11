/**
 * Form Input Block - Edit
 */

import { TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	RichText,
	InnerBlocks,
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';
import { Fragment } from 'react';
import { slugify } from '../../Utils/Slugify';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { label, isRequired } = attributes;
	const blockProps = useBlockProps( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );

	return (
		<Fragment>
			{ isSelected && (
				<InspectorControls>
					<TextControl
						label={ __(
							'The Input Label',
							'rtahina-salesforce-connector'
						) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
					<ToggleControl
						label="Is This Field Required"
						help={
							isRequired ? 'Is required.' : 'Is not reaquired.'
						}
						checked={ isRequired }
						onChange={ ( value ) => {
							setAttributes( { isRequired: value } );
						} }
					/>
				</InspectorControls>
			) }

			<div { ...blockProps }>
				<label for={ fieldId }>{ label }</label>
				<input type="text" name="" className="" id={ fieldId } />
			</div>
		</Fragment>
	);
}
