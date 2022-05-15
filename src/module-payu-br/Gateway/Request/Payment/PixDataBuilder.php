<?php
/**
* 
* PayU Brasil para Magento 2
* 
* @category     elOOm
* @package      Modulo PayUBr
* @copyright    Copyright (c) 2022 Ã©lOOm (https://eloom.tech)
* @version      2.0.0
* @license      https://eloom.tech/license
*
*/
declare(strict_types=1);

namespace Eloom\PayUBr\Gateway\Request\Payment;

use Eloom\PayU\Gateway\PayU\Enumeration\PaymentMethod;
use Eloom\PayU\Gateway\Request\Payment\AuthorizeDataBuilder;
use Eloom\PayUBr\Gateway\Config\Pix\Config;
use Magento\Framework\HTTP\Header;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;

class PixDataBuilder implements BuilderInterface {

	const COOKIE = 'cookie';

	const USER_AGENT = 'userAgent';

	const PAYMENT_METHOD = 'paymentMethod';

	const EXPIRATION_DATE = 'expirationDate';

	private $config;

	private $cookieManager;

	private $httpHeader;

	public function __construct(Config                 $config,
	                            CookieManagerInterface $cookieManager,
	                            Header                 $httpHeader) {
		$this->config = $config;
		$this->cookieManager = $cookieManager;
		$this->httpHeader = $httpHeader;
	}

	public function build(array $buildSubject) {
		$paymentDataObject = SubjectReader::readPayment($buildSubject);
		$payment = $paymentDataObject->getPayment();
		$storeId = $payment->getOrder()->getStoreId();

		$expiration = new \DateTime('now +' . $this->config->getExpiration($storeId) . ' hour');

		return [AuthorizeDataBuilder::TRANSACTION => [
			self::PAYMENT_METHOD => PaymentMethod::memberByKey('pix')->getCode(),
			self::COOKIE => $this->cookieManager->getCookie('PHPSESSID'),
			self::USER_AGENT => $this->httpHeader->getHttpUserAgent(),
			self::EXPIRATION_DATE => $expiration->format('Y-m-d\TH:i:s')
		]];
	}
}