<?php

declare(strict_types=1);

/**
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 *
 * Nextcloud - Two-factor Gateway
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\TwoFactorGateway\Service\Gateway\SMS\Provider;

use OCA\TwoFactorGateway\Exception\InvalidSmsProviderException;
use OCP\AppFramework\IAppContainer;

class ProviderFactory {

	/** @var IAppContainer */
	private $container;
	
	private $provider = [
		ClickatellCentral::PROVIDER_ID => ClickatellCentral::class,
		ClickSend::PROVIDER_ID => ClickSend::class,
		ClockworkSMS::PROVIDER_ID => ClockworkSMS::class,
		EcallSMS::PROVIDER_ID => EcallSMS::class,
		HuaweiE3531::PROVIDER_ID => HuaweiE3531::class,
		PlaySMS::PROVIDER_ID => PlaySMS::class,
		Plivo::PROVIDER_ID => Plivo::class,
		PuzzelSMS::PROVIDER_ID => PuzzelSMS::class,
		Ovh::PROVIDER_ID => Ovh::class,
		SpryngSMS::PROVIDER_ID => SpryngSMS::class,
		Sms77Io::PROVIDER_ID => Sms77Io::class,
		VoipMs::PROVIDER_ID => VoipMs::class,
		WebSms::PROVIDER_ID => WebSms::class
	];

	public function __construct(IAppContainer $container) {
		$this->container = $container;
	}

	public function getProvider(string $id): IProvider {
		$provider = $this->provider[$id] ?? '';
		if ($provider === '')
			throw new InvalidSmsProviderException("Provider <$id> does not exist");
		return $this->container->query($provider);
	}
}
