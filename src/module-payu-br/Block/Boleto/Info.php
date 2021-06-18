<?php
/**
* 
* PayU Brasil para Magento 2
* 
* @category     Ã©lOOm
* @package      Modulo PayUBr
* @copyright    Copyright (c) 2021 Ã©lOOm (https://eloom.tech)
* @version      1.0.0
* @license      https://eloom.tech/license
*
*/
declare(strict_types=1);

namespace Eloom\PayUBr\Block\Boleto;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

class Info extends \Eloom\PayU\Block\Info {

	public function getPaymentLink() {
		return $this->getInfo()->getAdditionalInformation('paymentLink');
	}

	public function getPdfLink() {
		return $this->getInfo()->getAdditionalInformation('pdfLink');
	}

	public function getBarCode() {
		return $this->getInfo()->getAdditionalInformation('barCode');
	}
}
