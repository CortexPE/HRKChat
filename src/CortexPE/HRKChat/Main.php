<?php

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
