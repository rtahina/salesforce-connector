/**
 * Form Input Block - Save
 */

import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { slugify } from '../../Utils/Slugify';

export default function save( { attributes } ) {
	const { label, type, name, placeholder, isRequired } = attributes;
	const blockProps = useBlockProps.save( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );
	const inputProps = {
		type: type,
		id: fieldId,
		className: 'rtsc__inner__form__field__text',
		name: name,
		placeholder: placeholder,
		required: isRequired,
	};

	return (
		<div { ...blockProps }>
			<label for={ fieldId }>{ label }</label>
			<input { ...inputProps } />
		</div>
	);
}
