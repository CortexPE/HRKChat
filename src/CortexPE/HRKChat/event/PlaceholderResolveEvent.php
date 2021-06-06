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

namespace CortexPE\HRKChat\event;


use CortexPE\Hierarchy\member\BaseMember;
use pocketmine\event\Event;

class PlaceholderResolveEvent extends Event {
	/** @var BaseMember */
	protected $member;
	/** @var string */
	protected $placeholderName;
	/** @var string */
	protected $value = null;

	public function __construct(BaseMember $member, string $placeholderName) {
		$this->member = $member;
		$this->placeholderName = $placeholderName;
	}

	/**
	 * @return BaseMember
	 */
	public function getMember(): BaseMember {
		return $this->member;
	}

	/**
	 * @return string
	 */
	public function getPlaceholderName(): string {
		return $this->placeholderName;
	}

	/**
	 * @return string|null
	 */
	public function getValue(): ?string {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue(string $value): void {
		$this->value = $value;
	}
}