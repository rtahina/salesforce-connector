/**
 * Form Checkbox Block - Edit
 */

import {
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
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
	const { label, name, isChecked, isRequired } = attributes;
	const blockProps = useBlockProps( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );
	const checkboxProps = {
		type: 'checkbox',
		id: fieldId,
		className: 'rtsc__inner__form__field__checkbox',
		name: name,
		checked: isChecked,
		required: isRequired,
	};

	return (
		<Fragment>
			{ isSelected && (
				<InspectorControls>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Checkbox Name',
							'rtahina-salesforce-connector'
						) }
						value={ name }
						onChange={ ( name ) => setAttributes( { name } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Checkbox Label',
							'rtahina-salesforce-connector'
						) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
					<ToggleControl
						__next40pxDefaultSize
						label="Is This Checked by Default"
						help={
							isChecked ? 'Is checked.' : 'Is not checked.'
						}
						checked={ isChecked }
						onChange={ ( value ) => {
							setAttributes( { isChecked: value } );
						} }
					/>
					<ToggleControl
						__next40pxDefaultSize
						label="Is This Field Required"
						help={
							isRequired ? 'Is required.' : 'Is not required.'
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
				<input { ...checkboxProps } />
			</div>
		</Fragment>
	);
}
