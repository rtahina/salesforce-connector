import { dispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

/**
 * Shows error notice
 */
export const errorNotice = (message, noticeId) => {
    dispatch( noticesStore ).createErrorNotice( message, {
		id: noticeId,
		isDismissible: true
	} );
}

/**
 * Shows warning notice
 */
export const warningNotice = (message, noticeId) => {
    dispatch( noticesStore ).createWarningNotice( message, {
		id: noticeId,
		isDismissible: true
	} );
}

/**
 * Shows success notice
 */
export const successNotice = (message, noticeId) => {
    dispatch( noticesStore ).createErrorNotice( message, {
		id: noticeId,
		isDismissible: true
	} );
}

/**
 * Remove a notice
 */
export const removeNotice = (noticeId) => {
    dispatch('core/notices').removeNotice(noticeId)
}