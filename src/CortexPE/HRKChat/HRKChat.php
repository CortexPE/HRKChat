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

namespace CortexPE\HRKChat;

use CortexPE\Hierarchy\member\BaseMember;
use CortexPE\HRKChat\placeholder\Placeholder;
use CortexPE\HRKChat\placeholder\PlaceholderManager;
use pocketmine\plugin\PluginBase;

class HRKChat extends PluginBase {
	/** @var PlaceholderManager */
	protected static $placeholderManager;

	public function onEnable(): void {
		$this->saveResource("config.yml");

		self::$placeholderManager = new PlaceholderManager(
			$this,
			$this->getConfig()->get("placeholder")
		);

		// Default placeholder(s)
		self::$placeholderManager->registerPlaceholder(
			new Placeholder("hrk.displayName",
				function (BaseMember $member): string {
					return $member->getPlayer()->getDisplayName();
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
	public static function getPlaceholderManager(): PlaceholderManager {
		return self::$placeholderManager;
	}
}
