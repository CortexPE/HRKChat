<?php


namespace CortexPE\HRKChat\event;

use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

/**
 * Call this event to trigger a nametag refresh for a player
 *
 * @package CortexPE\HRKChat\event
 */
class PlayerNametagRefreshEvent extends PlayerEvent {
	public function __construct(Player $player) {
		$this->player = $player;
	}
}