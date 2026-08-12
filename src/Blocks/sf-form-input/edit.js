/**
 * Form Input Block - Edit
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
	const { label, type, name, placeholder, isRequired } = attributes;
	const blockProps = useBlockProps( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );
	const TYPES = [
		{ label: 'Email', value: 'email' },
		{ label: 'Date', value: 'date' },
		{ label: 'Input text', value: 'text' },
		{ label: 'Phone Number', value: 'phone' },
		{ label: 'URL', value: 'url' },
	];
	const inputProps = {
		type: type,
		id: fieldId,
		className: 'rtsc__inner__form__field__text',
		name: name,
		placeholder: placeholder,
		required: isRequired,
	};

	return (
		<Fragment>
			{ isSelected && (
				<InspectorControls>
					<SelectControl
						__next40pxDefaultSize
						label={ __(
							'The Input Type',
							'rtahina-salesforce-connector'
						) }
						value={ type }
						options={ TYPES }
						onChange={ ( type ) => setAttributes( { type } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Input Name',
							'rtahina-salesforce-connector'
						) }
						value={ name }
						onChange={ ( name ) => setAttributes( { name } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Input Label',
							'rtahina-salesforce-connector'
						) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Input Placeholder',
							'rtahina-salesforce-connector'
						) }
						value={ placeholder }
						onChange={ ( placeholder ) =>
							setAttributes( { placeholder } )
						}
					/>
					<ToggleControl
						__next40pxDefaultSize
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
				<input { ...inputProps } />
			</div>
		</Fragment>
	);
}
