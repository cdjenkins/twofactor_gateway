<?php

declare(strict_types=1);

/**
 * @author Chris Jenkins <chris.j@guruinventions.com>
 *
 * Plivo - Config for Two-factor Gateway for Plivo
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

use function array_intersect;
use OCA\TwoFactorGateway\AppInfo\Application;
use OCA\TwoFactorGateway\Exception\ConfigurationException;
use OCP\IConfig;

class PlivoConfig implements IProviderConfig {

	/** @var IConfig */
	private $config;
	
	private const AUTH_ID_KEY = 'plivo_auth_id';
	private const AUTH_TOKEN_KEY = 'plivo_auth_token';
	private const CALLBACK_URL = 'plivo_callback_url';
	private const SRC_NUMBER_KEY = 'plivo_src_number';
	
	private const EXPECTED_KEYS = [
		self::AUTH_ID_KEY,
		self::AUTH_TOKEN_KEY,
		self::CALLBACK_URL,
		self::SRC_NUMBER_KEY
	];
	
	private $questions = [
			self::AUTH_ID_KEY => 'Please enter your plivo authentication id (Auth ID): ',
			self::AUTH_TOKEN_KEY => 'Please enter your plivo authentication token (Auth Token): ',
			self::CALLBACK_URL => 'Please enter your plivo callback url: ',
			self::SRC_NUMBER_KEY => "Please enter your plivo phone number (in E.164 format '+12345678901'): "
		];

	public function __construct(IConfig $config) {
		$this->config = $config;
	}
	
	private function getInternalValue(string $key): string {
		$val = $this->config->getAppValue(Application::APP_NAME, $key, null);
		if (is_null($val)) {
			throw new ConfigurationException();
		}
		return $val;
	}
	
	public function getValue(string $key): string {
		return $this->getInternalValue($key);
	}

	public function setValue(string $key, string $value) {
		$this->config->setAppValue(Application::APP_NAME, $key, $value);
	}
	
	public function isComplete(): bool {
		$set = $this->config->getAppKeys(Application::APP_NAME);
		return count(array_intersect($set,static::EXPECTED_KEYS)) === count(static::EXPECTED_KEYS);
	}
	
	public function remove() {
		foreach (static::EXPECTED_KEYS as $key) {
			$this->config->deleteAppValue(Application::APP_NAME, $key);
		}
	}
	
	public function &getQuestions(): array {
		return $this->questions;
	}
	
	public function setAnswers() {
		foreach ($this->questions as $key => $answer) {
			$this->setValue($key,$answer);
		}
	}
}
