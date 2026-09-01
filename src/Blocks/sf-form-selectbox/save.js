/**
 * Form Selectbox Block - Save
 */

import { useBlockProps } from '@wordpress/block-editor';
import { slugify } from '../../Utils/Slugify';
import { sanitizeWithDash } from '../../Utils/sanitizeWithDash';

export default function save( { attributes } ) {
	const { label, name, options, isRequired } = attributes;
	const blockProps = useBlockProps.save( {
		className: 'rtsc__inner__form__field',
	} );
	const fieldId = 'rtsc-' + slugify( label );
	const selectboxProps = {
		id: fieldId,
		className: 'rtsc__inner__form__field__selectbox',
		name: sanitizeWithDash( name ),
		required: isRequired,
	};

	return (
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
	);
}
