<?php

/***
 *        __  ___                           __
 *       / / / (_)__  _________ ___________/ /_  __  __
 *      / /_/ / / _ \/ ___/ __ `/ ___/ ___/ __ \/ / / /
 *     / __  / /  __/ /  / /_/ / /  / /__/ / / / /_/ /
 *    /_/ /_/_/\___/_/   \__,_/_/   \___/_/ /_/\__, /
 *                                            /____/
 *
 * Hierarchy - Role-based permission management system
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


use pocketmine\Player;
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
		Utils::validateCallableSignature(function (Player $player): string {
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
	 * @param Player $player
	 *
	 * @return string
	 */
	public function getValue(Player $player):string {
		if((time() - $this->lastUpdate) < PlaceholderManager::getCacheExpiration()){
			return ($this->lastValue = ($this->callback)($player));
		}
		return $this->lastValue;
	}
}