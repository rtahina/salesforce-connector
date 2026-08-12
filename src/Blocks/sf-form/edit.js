/**
 * Form Block - Edit
 */

import { __ } from '@wordpress/i18n';
import { RichText, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { Fragment } from 'react';
import { errorNotice, removeNotice } from '../../utils/notices';
import { lockSaving, unlockSaving } from '../../utils/lock';
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { heading, content } = attributes;
	const blockProps = useBlockProps();
	const ALLOWED_BLOCKS = [
		'rtsc/sf-form-input',
		'rtsc/sf-form-checkbox',
		'rtsc/sf-form-selectbox',
	];
	const DEFAULT_CONTENT = [
		[
			'rtsc/sf-form-input',
			{
				type: 'email',
				name: 'email',
				label: 'Your Email Address',
				placeholder: 'Enter a valid email address',
				isRequired: true,
			},
		],
	];

	const innerBlocks = useSelect(
		( select ) => select( 'core/block-editor' ).getBlocks( clientId ),
		[ clientId ]
	);

	useEffect( () => {
		removeNotice( 'rtsc-form-empty' );
		if ( innerBlocks.length === 0 ) {
			lockSaving( 'rtsc-form-required-block' );
			errorNotice(
				'The SalesForce form must have at least one field',
				'rtsc-form-empty'
			);
		} else {
			unlockSaving( 'rtsc-form-required-block' );
		}

		return () => {
			unlockSaving( 'rtsc-form-required-block' );
		};
	}, [ innerBlocks.length ] );

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
