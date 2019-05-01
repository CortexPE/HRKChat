<?php

/***
 *        __  ___                           __
 *       / / / (_)__  _________ ___________/ /_  __  __
 *      / /_/ / / _ \/ ___/ __ `/ ___/ ___/ __ \/ / / /
 *     / __  / /  __/ /  / /_/ / /  / /__/ / / / /_/ /
 *    /_/ /_/_/\___/_/   \__,_/_/   \___/_/ /_/\__, /
 *                                            /____/
 *
 * HRKChat - Chat & nametag formatter that respects Role Hierarchy
 * Copyright (C) 2019-Present CortexPE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace CortexPE\HRKChat\placeholder;


use CortexPE\Hierarchy\member\BaseMember;
use CortexPE\HRKChat\HRKChat;
use CortexPE\HRKChat\placeholder\exception\PlaceholderCollisionError;

class PlaceholderManager {
	/** @var HRKChat */
	protected $plugin;
	/** @var string */
	protected $prefix = "{{";
	/** @var string */
	protected $suffix = "}}";
	/** @var int */
	protected static $cacheExpiration = 10;
	/** @var Placeholder[] */
	protected $placeholders = [];

	public function __construct(HRKChat $plugin, array $config) {
		$this->plugin = $plugin;
		self::$cacheExpiration = $config["cacheExpiration"];
		$this->prefix = $config["prefix"];
		$this->suffix = $config["suffix"];
	}

	/**
	 * @return int
	 */
	public static function getCacheExpiration(): int {
		return self::$cacheExpiration;
	}

	public function registerPlaceholder(Placeholder $placeholder): void {
		if($this->isRegistered(($n = $placeholder->getName()))){
			throw new PlaceholderCollisionError("Placeholder with same name, '{$n}' has already been registered.");
		}
		$this->placeholders[$n] = $placeholder;
	}

	public function isRegistered(string $placeholderName): bool {
		return isset($this->placeholders[$placeholderName]);
	}

	public function unregisterPlaceholder(Placeholder $placeholder): void {
		unset($this->placeholders[$placeholder->getName()]);
	}

	public function unregisterPlaceholderByName(string $placeholderName): void {
		unset($this->placeholders[$placeholderName]);
	}

	/**
	 * Replaces placeholders with their respective values
	 *
	 * @param string $message
	 * @param BaseMember $player
	 *
	 * @return string
	 */
	public function processString(string $message, BaseMember $player): string {
		foreach($this->placeholders as $name => $placeholder) {
			$message = str_replace($this->prefix . $name . $this->suffix, $placeholder->getValue($player), $message);
		}

		return $message;
	}

	/**
	 * @return string
	 */
	public function getPrefix(): string {
		return $this->prefix;
	}

	/**
	 * @return string
	 */
	public function getSuffix(): string {
		return $this->suffix;
	}
}