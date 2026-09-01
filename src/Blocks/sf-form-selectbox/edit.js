/**
 * Form Selectbox Block - Edit
 */

import {
	Button,
	TextControl,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	RichText,
	InnerBlocks,
	useBlockProps,
	InspectorControls,
} from '@wordpress/block-editor';
import { Fragment, useState } from 'react';
import { slugify } from '../../Utils/Slugify';
import { arrayToString } from '../../Utils/arrayToString';

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { label, name, options, isRequired } = attributes;
	const blockProps = useBlockProps( {
		className: 'rtsc__inner__form__field',
	} );
	const [ optionsString, setOptionsString ] = useState(
		arrayToString( options )
	);
	const fieldId = 'rtsc-' + slugify( label );
	const selectboxProps = {
		id: fieldId,
		className: 'rtsc__inner__form__field__selectbox',
		name: name,
		required: isRequired,
	};

	const handleSaveButtonClick = ( data ) => {
		let tmpOptions = [];
		const optionsRow = optionsString.split( /\r?\n/ );
		optionsRow.map( ( option, index ) => {
			let optionLabel = '';
			let optionValue = '';
			option = option.trim();
			const isSplitted = option.includes( ':' );
			if ( isSplitted ) {
				[ optionLabel, optionValue ] = option.split( ':' );
				optionLabel = optionLabel.trim();
				optionValue = optionValue.trim();
			} else {
				optionLabel = option;
				optionValue = option;
			}

			tmpOptions.push( {
				label: optionLabel,
				value: optionValue,
			} );
		} );

		setAttributes( { options: tmpOptions } );
	};

	return (
		<Fragment>
			{ isSelected && (
				<InspectorControls>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Selectbox Name',
							'rtahina-salesforce-connector'
						) }
						value={ name }
						help="Spaces will be replaced by dashes (-)."
						onChange={ ( name ) => setAttributes( { name } ) }
					/>
					<TextControl
						__next40pxDefaultSize
						label={ __(
							'The Selectbox Label',
							'rtahina-salesforce-connector'
						) }
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
					<TextareaControl
						__next40pxDefaultSize
						label={ __(
							'The List Options',
							'rtahina-salesforce-connector'
						) }
						help={ __(
							'Enter each option line by line. Ex: Red:red',
							'rtahina-salesforce-connector'
						) }
						value={ optionsString }
						onChange={ ( optionsString ) => {
							setOptionsString( optionsString );
						} }
					/>
					<Button
						__next40pxDefaultSize
						variant="primary"
						onClick={ () => {
							handleSaveButtonClick( optionsString );
						} }
					>
						Save Options
					</Button>
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
				<select { ...selectboxProps }>
					{ Array.isArray( options ) && options.length > 0 ? (
						options.map( ( item, index ) => (
							<option value={ item.value }>{ item.label }</option>
						) )
					) : (
						<option value="">No options available</option>
					) }
				</select>
			</div>
		</Fragment>
	);
}
