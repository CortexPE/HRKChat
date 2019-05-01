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
use pocketmine\utils\Utils;

class Placeholder {
	/** @var string */
	protected $name;
	/** @var callable */
	protected $callback;
	/** @var string */
	protected $lastValue = "";
	/** @var int */
	protected $lastUpdate = 0;

	public function __construct(string $name, callable $callback) {
		/***
		 * Read the placeholder docs:
		 * - https://github.com/CortexPE/HRKChat/wiki/Placeholder-registration
		 * - https://github.com/CortexPE/HRKChat/wiki/Placeholder-naming-standard
		 * - https://github.com/CortexPE/HRKChat/wiki/Placeholder-callback-standard
		 */
		if(!preg_match("/^(?:[A-Za-z0-9_]+\.)+[A-Za-z0-9_]{3,}$/", $name)) {
			throw new \InvalidArgumentException("Placeholder name does not meet specific naming standards.");
		}
		Utils::validateCallableSignature(function (BaseMember $player): string {
			return '';
		}, $callback);

		$this->name = $name;
		$this->callback = $callback;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param BaseMember $player
	 *
	 * @return string
	 */
	public function getValue(BaseMember $player): string {
		if((time() - $this->lastUpdate) > PlaceholderManager::getCacheExpiration()) {
			return ($this->lastValue = ($this->callback)($player));
		}

		return $this->lastValue;
	}
}