<?php
/**
* 
* PayU Brasil para Magento 2
* 
* @category     elOOm
* @package      Modulo PayUBr
* @copyright    Copyright (c) 2021 Ã©lOOm (https://eloom.tech)
* @version      1.0.3
* @license      https://eloom.tech/license
*
*/
declare(strict_types=1);

namespace Eloom\PayUBr\Block\Pix;

class Info extends \Eloom\PayU\Block\Info {

	public function getExpirationDate() {
		return $this->getInfo()->getAdditionalInformation('expirationDate');
	}

	public function getQrCodeEmv() {
		return $this->getInfo()->getAdditionalInformation('qrCodeEmv');
	}

	public function getQrCodeImageBase64() {
		return $this->getInfo()->getAdditionalInformation('qrCodeImageBase64');
	}
}
