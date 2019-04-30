<?php


namespace CortexPE\HRKChat;


use CortexPE\Hierarchy\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class EventListener implements Listener {
	/** @var Main */
	protected $plugin;
	/** @var string[] */
	protected $chatFormats = [];

	public function __construct(Main $plugin, array $config) {
		$this->plugin = $plugin;
		$this->chatFormats = $config["chatFormat"];
	}

	/**
	 * @param PlayerChatEvent $ev
	 *
	 * @priority LOW
	 * @ignoreCancelled true
	 */
	public function onChat(PlayerChatEvent $ev) {
		$member = ($hrk = Loader::getInstance())->getMemberFactory()->getMember(($p = $ev->getPlayer()));

		$roles = $member->getRoles();
		$topRolePosition = PHP_INT_MIN;
		$roleID = $hrk->getRoleManager()->getDefaultRole()->getId();
		foreach($roles as $role) {
			if(
				isset($this->chatFormats[$role->getId()]) &&
				$role->getPosition() > $topRolePosition
			) {
				$topRolePosition = $role->getPosition();
				$roleID = $role->getId();
			}
		}
		$phMgr = $this->plugin->getPlaceholderManager();
		$msg = str_replace(
			$phMgr->getPrefix() . "msg" . $phMgr->getSuffix(),
			$ev->getMessage(),
			$this->chatFormats[$roleID]
		);

		$ev->setFormat($phMgr->processString($msg, $p));
	}
}