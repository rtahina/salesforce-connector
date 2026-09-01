/**
 * Form Input Block - Save
 */

import { useBlockProps } from '@wordpress/block-editor';
import { slugify } from '../../Utils/Slugify';
import { sanitizeWithDash } from '../../Utils/sanitizeWithDash';

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
		name: sanitizeWithDash( name ),
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
