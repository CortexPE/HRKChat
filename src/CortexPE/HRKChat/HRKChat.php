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
use CortexPE\HRKChat\event\PlaceholderResolveEvent;
use CortexPE\HRKChat\exception\UnresolvedPlaceholderException;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class HRKChat extends PluginBase {
	/** @var string */
	protected $prefix = "{{";
	/** @var string */
	protected $suffix = "}}";
	/** @var string */
	protected $placeholderRegex;

	public function onEnable(): void {
		$this->saveResource("config.yml");

		$config = $this->getConfig()->getAll();
		$this->prefix = $config["placeholder"]["prefix"];
		$this->suffix = $config["placeholder"]["suffix"];
		$this->placeholderRegex = "/(?:" . preg_quote($this->prefix) . ")((?:[A-Za-z0-9_\-]{2,})(?:\.[A-Za-z0-9_\-]+)+)(?:" . preg_quote($this->suffix) . ")/";

		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $config), $this);
	}

	public function resolvePlaceholders(string $msg, BaseMember $member): string {
		if(preg_match_all($this->placeholderRegex, $msg, $matches)) {
			foreach($matches[1] as $k => $match) {
				$ev = new PlaceholderResolveEvent($member, $match);
				$ev->call();
				$val = $ev->getValue();
				if($val === null) {
					throw new UnresolvedPlaceholderException("Unresolved placeholder '{$match}'");
				}
				$msg = str_replace($matches[0][$k], $val, $msg);
			}
		}

		return TextFormat::colorize($msg, "&");
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
