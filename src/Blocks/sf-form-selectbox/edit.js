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

export default function Edit( { attributes, setAttributes, isSelected } ) {
	const { label, name, options, isRequired } = attributes;
	const blockProps = useBlockProps( {
		className: 'rtsc__inner__form__field',
	} );
	const [ optionsString, setOptionsString ] = useState( '' );
	const fieldId = 'rtsc-' + slugify( label );
	const selectboxProps = {
		id: fieldId,
		className: 'rtsc__inner__form__field__selectbox',
		name: name,
		required: isRequired,
	};

	const handleSaveButtonClick = ( data ) => {
		setAttributes( { options: data } );
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
							let tmpOptions = [];
							const optionsRow = optionsString.split( /\r?\n/ );
							optionsRow.map( ( option, index ) => {
								const [ label, value ] = option.split( ':' );
								tmpOptions.push( {
									label: label,
									value: value,
								} );
							} );
							handleSaveButtonClick( tmpOptions );
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
							<option value={ item.value.trim() }>
								{ item.label.trim() }
							</option>
						) )
					) : (
						<option value="">No options available</option>
					) }
				</select>
			</div>
		</Fragment>
	);
}
