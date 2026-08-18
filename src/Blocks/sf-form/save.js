/**
 * Form Block - Save
 */

import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { heading, content, submitButtonLabel } = attributes;
	const blockProps = useBlockProps.save( {
		'aria-labelledby': 'rtsc-form-heading',
	} );

	return (
		<section { ...blockProps }>
			<div className="rtsc__inner">
				<div className="rtsc__inner__content">
					<RichText.Content
						tagName="h2"
						id="rtsc-form-heading"
						className="rtsc__inner__content__heading"
						value={ heading }
					/>
					<RichText.Content
						tagName="p"
						className="rtsc__inner__content__description"
						value={ content }
					/>
					<div class="rtsc__inner__form__wrapper">
						<form action="" method="" className="rtsc__inner__form">
							<div className="rtsc__inner__form__fields">
								<InnerBlocks.Content />
							</div>
							<button type="submit">{ submitButtonLabel }</button>
						</form>
					</div>
				</div>
			</div>
		</section>
	);
}
