define([
	'uiComponent',
	'Magento_Checkout/js/model/payment/renderer-list'
	],
	function (Component, rendererList) {
		'use strict';

		rendererList.push({
			type: 'eloom_payments_payu_boleto',
			component: 'Eloom_PayUBr/js/view/payment/method-renderer/boleto-method'
		});

		return Component.extend({});
	}
);