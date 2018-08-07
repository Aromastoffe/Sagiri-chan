<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;

class MuteListener
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}
	public function onWrite(PlayerChatEvent $event) {
		$msg = $event->getMessage();
		str_replace("noob", "Pro", $msg);
		str_replace("ez", "pretty Good", $msg);
		str_replace("@shrug", "¯\\_(ツ)_/¯", $msg);
		$sagiri = sagiri::getInstance();
		if($sagiri->getMuted($event->getPlayer()->getName())) {
			$pname = $event->getPlayer()->getName();
			$c = new Config("/cloud/users/$pname.yml", 2);
			$reason = $c->get("reason");
			$mutedby = $c->get("mutedby");
			$muteduntil = $c->get("muteduntil");
			$rmuteduntil = date('r', (int)$muteduntil);
			$date = date("U");
			if((int)$date >= (int)$muteduntil) {
				$pname = $event->getPlayer()->getName();
				$this->api->getLogger()->info(sagiri::PREFIX."Unmuting $pname due to: \"Mute Expired\"");
				$sagiri->setMuted($event->getPlayer()->getName(), false);
				$event->setCancelled(false);
				return true;
			}
			else {
				$event->getPlayer()->sendMessage(sagiri::PREFIX . "Du wurdest von " . f::RED . "$mutedby" . f::WHITE . " gemuted!\n Grund: "
					. f::RED
					. "$reason\n" . f::WHITE . "Gemuted bis zum: " . f::RED . "$rmuteduntil");
				$event->setCancelled(true);
				return false;
			}
		} else {
			$event->setCancelled(false);
		}
	}

}