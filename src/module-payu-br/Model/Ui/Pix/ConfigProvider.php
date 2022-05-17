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

namespace Eloom\PayUBr\Model\Ui\Pix;

use Eloom\PayUBr\Gateway\Config\Pix\Config as PixConfig;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\View\Asset\Repository;
use Magento\Store\Model\StoreManagerInterface;

class ConfigProvider implements ConfigProviderInterface {

	const CODE = 'eloom_payments_payu_pix';

	protected $assetRepo;

	private $config;

	protected $storeManager;

	protected $escaper;

	public function __construct(Repository              $assetRepo,
	                            Escaper                 $escaper,
	                            PixConfig               $pixConfig,
	                            StoreManagerInterface $storeManager) {
		$this->assetRepo = $assetRepo;
		$this->escaper = $escaper;
		$this->config = $pixConfig;
		$this->storeManager = $storeManager;
	}

	public function getConfig() {
		$store = $this->storeManager->getStore();
		$payment = [];
		$storeId = $store->getStoreId();
		$isActive = $this->config->isActive($storeId);
		if ($isActive) {
			$currency = $store->getCurrentCurrencyCode();
			if ('BRL' != $currency) {
				return ['payment' => [
					self::CODE => [
						'message' =>  sprintf("Currency %s not supported.", $currency)
					]
				]];
			}

			$payment = [
				self::CODE => [
					'isActive' => $isActive,
					'instructions' => $this->getInstructions($storeId),
					'url' => [
						'logo' => $this->assetRepo->getUrl('Eloom_PayUBr::images/pix.svg')
					]
				]
			];
		}

		return [
			'payment' => $payment
		];
	}

	protected function getInstructions($storeId): string {
		return $this->escaper->escapeHtml($this->config->getInstructions($storeId));
	}
}