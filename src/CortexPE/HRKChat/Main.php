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

namespace CortexPE\HRKChat;

use CortexPE\HRKChat\placeholder\Placeholder;
use CortexPE\HRKChat\placeholder\PlaceholderManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {
	/** @var PlaceholderManager */
	protected $placeholderManager;

	public function onEnable(): void {
		$this->saveResource("config.yml");

		$this->placeholderManager = new PlaceholderManager(
			$this,
			$this->getConfig()->get("placeholder")
		);

		// Default placeholder(s)
		$this->placeholderManager->registerPlaceholder(
			new Placeholder("displayName",
				function (Player $player): string {
					return $player->getName();
				}
			)
		);

		$this->getServer()->getPluginManager()->registerEvents(
			new EventListener($this, $this->getConfig()->getAll()),
			$this
		);
	}

	/**
	 * @return PlaceholderManager
	 */
	public function getPlaceholderManager(): PlaceholderManager {
		return $this->placeholderManager;
	}
}
