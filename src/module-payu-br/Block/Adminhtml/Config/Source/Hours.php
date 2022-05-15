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

namespace Eloom\PayUBr\Block\Adminhtml\Config\Source;

class Hours implements \Magento\Framework\Option\ArrayInterface {

	public function toOptionArray() {
		return [
			['value' => '1', 'label' => __('1 hour')],
			['value' => '2', 'label' => __('2 hours')],
			['value' => '3', 'label' => __('3 hours')],
			['value' => '4', 'label' => __('4 hours')]
		];
	}
}