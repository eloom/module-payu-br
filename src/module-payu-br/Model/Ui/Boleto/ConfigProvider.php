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

namespace Eloom\PayUBr\Model\Ui\Boleto;

use Eloom\PayUBr\Gateway\Config\Boleto\Config as BoletoConfig;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Asset\Repository;

class ConfigProvider implements ConfigProviderInterface {

	const CODE = 'eloom_payments_payu_boleto';

	protected $assetRepo;

	private $config;

	private $session;

	protected $escaper;

	public function __construct(Repository              $assetRepo,
	                            SessionManagerInterface $session,
	                            Escaper                 $escaper,
	                            BoletoConfig            $boletoConfig) {
		$this->assetRepo = $assetRepo;
		$this->session = $session;
		$this->escaper = $escaper;
		$this->config = $boletoConfig;
	}

	public function getConfig() {
		$storeId = $this->session->getStoreId();

		$payment = [];
		$isActive = $this->config->isActive($storeId);
		if ($isActive) {
			$payment = [
				self::CODE => [
					'isActive' => $isActive,
					'instructions' => $this->getInstructions($storeId),
					'url' => [
						'logo' => $this->assetRepo->getUrl('Eloom_PayUBr::images/boleto.svg')
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