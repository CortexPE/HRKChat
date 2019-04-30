<?php


namespace CortexPE\HRKChat\placeholder;


use CortexPE\HRKChat\Main;
use pocketmine\Player;

class PlaceholderManager {
	/** @var Main */
	protected $plugin;
	/** @var string */
	protected $prefix = "{{";
	/** @var string */
	protected $suffix = "}}";
	/** @var int */
	protected static $cacheExpiration = 10;
	/** @var Placeholder[] */
	protected $placeholders = [];

	public function __construct(Main $plugin, array $config) {
		$this->plugin = $plugin;
		self::$cacheExpiration = $config["cacheExpiration"];
		$this->prefix = $config["prefix"];
		$this->suffix = $config["suffix"];
	}

	/**
	 * @return int
	 */
	public static function getCacheExpiration(): int {
		return self::$cacheExpiration;
	}

	public function registerPlaceholder(Placeholder $placeholder):void {
		$this->placeholders[$placeholder->getName()] = clone $placeholder;
	}

	/**
	 * Replaces placeholders with their respective values
	 *
	 * @param string $message
	 * @param Player $player
	 *
	 * @return string
	 */
	public function processString(string $message, Player $player):string {
		foreach($this->placeholders as $name => $placeholder){
			$message = str_replace($this->prefix . $name . $this->suffix, $placeholder->getValue($player), $message);
		}
		return $message;
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