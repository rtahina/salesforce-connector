/**
 * Form Input Block - Save
 */

import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { slugify } from '../../Utils/Slugify';

export default function save( { attributes } ) {
	const { label, isRequired } = attributes;
	const blockProps = useBlockProps.save( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );
	const inputProps = {
		id: fieldId,
		className: 'rtsc__inner__form__field__text',
		name: 'default-input',
		required: isRequired,
	};

	return (
		<div { ...blockProps }>
			<label for={ fieldId }>{ label }</label>
			<input type="text" { ...inputProps } />
		</div>
	);
}
