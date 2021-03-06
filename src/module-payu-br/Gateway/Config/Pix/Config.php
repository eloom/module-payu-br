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

namespace Eloom\PayUBr\Gateway\Config\Pix;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;

class Config extends \Magento\Payment\Gateway\Config\Config {

	const KEY_ACTIVE = 'active';

	const KEY_INSTRUCTIONS = 'instructions';

	const EXPIRATION = 'expiration';

	private $serializer;

	public function __construct(ScopeConfigInterface $scopeConfig,
	                            $methodCode = null,
	                            $pathPattern = self::DEFAULT_PATH_PATTERN,
	                            Json $serializer = null) {
		parent::__construct($scopeConfig, $methodCode, $pathPattern);
		$this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
	}

	public function isActive($storeId = null) {
		return (bool)$this->getValue(self::KEY_ACTIVE, $storeId);
	}

	public function getInstructions($storeId = null) {
		return $this->getValue(self::KEY_INSTRUCTIONS, $storeId);
	}

	public function getExpiration($storeId = null) {
		return (int)trim($this->getValue(self::EXPIRATION, $storeId));
	}
}