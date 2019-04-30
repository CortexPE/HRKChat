<?php


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