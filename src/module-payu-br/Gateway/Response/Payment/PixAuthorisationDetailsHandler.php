<?php
/**
* 
* PayU Brasil para Magento 2
* 
* @category     elOOm
* @package      Modulo PayUBr
* @copyright    Copyright (c) 2022 elOOm (https://eloom.tech)
* @version      2.0.0
* @license      https://eloom.tech/license
*
*/
declare(strict_types=1);

namespace Eloom\PayUBr\Gateway\Response\Payment;

use Eloom\PayU\Api\Data\OrderPaymentPayUInterface;
use Eloom\PayU\Gateway\PayU\Enumeration\PayUTransactionState;
use Eloom\PayUBr\Gateway\Config\Pix\Config;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order;

class PixAuthorisationDetailsHandler implements HandlerInterface {

	private $config;

	public function __construct(Config $config) {
		$this->config = $config;
	}

	/**
	 * @inheritdoc
	 */
	public function handle(array $handlingSubject, array $response) {
		$paymentDataObject = SubjectReader::readPayment($handlingSubject);
		$transaction = $response[0]['transaction']->transactionResponse;

		$transactionState = PayUTransactionState::memberByKey($transaction->state);

		$payment = $paymentDataObject->getPayment();
		$payment->setTransactionId($transaction->transactionId);
		$payment->setPayuTransactionState($transactionState->key());

		$payment->setLastTransId($transaction->transactionId);
		$payment->setAdditionalInformation('payuOrderId', $transaction->orderId);
		$payment->setAdditionalInformation('transactionId', $transaction->transactionId);
		$payment->setAdditionalInformation('expirationDate', $transaction->extraParameters->EXPIRATION_DATE);
		$payment->setAdditionalInformation('qrCodeEmv', $transaction->extraParameters->QRCODE_EMV);
		$payment->setAdditionalInformation('qrCodeImageBase64', $transaction->extraParameters->QRCODE_IMAGE_BASE64);

		/**
		 * Limpa dados do Cartão, se houver
		 */
		$payment->addData(
			[
				OrderPaymentPayUInterface::CC_NUMBER_ENC => null,
				OrderPaymentPayUInterface::CC_CID_ENC => null,
				OrderPaymentInterface::CC_TYPE => null,
				OrderPaymentInterface::CC_OWNER => null,
				OrderPaymentInterface::CC_LAST_4 => null,
				OrderPaymentInterface::CC_EXP_MONTH => null,
				OrderPaymentInterface::CC_EXP_YEAR => null
			]
		);
		$payment->setAdditionalInformation('installments', null);
		$payment->setAdditionalInformation('installmentAmount', null);
		$payment->setAdditionalInformation('ccBank', null);
		$payment->setIsTransactionPending(true);
		$payment->setIsTransactionClosed(false);
		$payment->setShouldCloseParentTransaction(false);

		$payment->getOrder()
			->setState(Order::STATE_NEW)
			->setStatus(Order::STATE_PENDING_PAYMENT)
			->setCanSendNewEmailFlag(true);
	}
}