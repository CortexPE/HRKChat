<?php

namespace CortexPE\HRKChat\event;

use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class NametagUpdateRequestEvent extends PlayerEvent {
	public function __construct(Player $player) {
		$this->player = $player;
	}
}