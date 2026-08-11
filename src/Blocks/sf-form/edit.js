/**
 * Form Block - Edit
 */

import { __ } from '@wordpress/i18n';
import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { Fragment } from 'react';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, content } = attributes;
	const blockProps = useBlockProps();
	const ALLOWED_BLOCKS = [ 'rtsc/sf-form-input' ];
	const DEFAULT_CONTENT = [
		[
			'rtsc/sf-form-input',
			{
				lable: 'Your Email Address',
				isRequired: true,
			},
		],
	];

	return (
		<Fragment>
			<section { ...blockProps }>
				<div className="rtsc__inner">
					<div className="rtsc__inner__content">
						<RichText
							tagName="h2"
							className="rtsc__inner__content__heading"
							value={ heading }
							onChange={ ( value ) =>
								setAttributes( { heading: value } )
							}
							placeholder={ __(
								'Enter a heading...',
								'rtahina-salesforce-connector'
							) }
						/>
						<RichText
							tagName="p"
							className="rtsc__inner__content__description"
							value={ content }
							onChange={ ( value ) =>
								setAttributes( { content: value } )
							}
							placeholder={ __(
								'Enter a description...',
								'rtahina-salesforce-connector'
							) }
						/>

						<div class="rtsc__inner__form__wrapper">
							<form
								action=""
								method=""
								className="rtsc__inner__form"
							>
								<InnerBlocks
									allowedBlocks={ ALLOWED_BLOCKS }
									template={ DEFAULT_CONTENT }
									className="rtsc__inner__form__fields"
								/>
							</form>
						</div>
					</div>
				</div>
			</section>
		</Fragment>
	);
}
