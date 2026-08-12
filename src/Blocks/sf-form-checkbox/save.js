/**
 * Form Checked Block - Save
 */

import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { slugify } from '../../Utils/Slugify';

export default function save( { attributes } ) {
	const { label, name, isChecked, isRequired } = attributes;
	const blockProps = useBlockProps.save( {
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
		<div { ...blockProps }>
			<input { ...checkboxProps } />
			<label for={ fieldId }>{ label }</label>
		</div>
	);
}
