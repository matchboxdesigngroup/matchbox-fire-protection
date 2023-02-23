import { matchboxWelcome, ajaxurl } from 'window'; /* eslint-disable-line import/no-unresolved */
import jQuery from 'jquery'; /* eslint-disable-line import/no-unresolved */

const dismiss = document.querySelector('.notice-matchbox-fire-protection-welcome');

if (dismiss) {
	const data = {
		action: 'matchbox_dismiss_welcome',
		nonce: matchboxWelcome.nonce,
	};

	jQuery(dismiss).on('click', 'button', () => {
		jQuery.ajax({
			method: 'post',
			data,
			url: ajaxurl,
		});
	});
}
