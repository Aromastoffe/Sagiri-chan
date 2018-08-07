<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;


class BanListener
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onConnect(PlayerPreLoginEvent $event) {
	$prefix = sagiri::NAME;
	$sagiri = sagiri::getInstance();
	$player = $event->getPlayer();
	$pname = $player->getName();
	$this->api->getLogger()->info(sagiri::PREFIX."Logged in $pname");
	$banned = $sagiri->getBanned($player->getName());
	$c = new Config("/cloud/users/$pname.yml", 2);
		$banner = $c->get("bannedby");
		$banneduntil = $c->get("banneduntil");
		$reason = $c->get("reason");
		$rbanneduntil = date('r', (int)$banneduntil);
	if($banned) {
		$date = date("U");
		$this->api->getLogger()->info(sagiri::PREFIX."Compararing $date >= $banneduntil");
		if((int)$date >= (int)$banneduntil) {
			$this->api->getLogger()->info(sagiri::PREFIX."Unbanning $pname due to: \"Ban Expired\"");
			$sagiri->setBanned($player->getName(), false);
		} else {
			$this->api->getLogger()->info(sagiri::PREFIX."$pname stays banned.");
			$event->setKickMessage(f::RED .
				"Du wurdest gebannt von: " . f::YELLOW.f::BOLD . "$banner\n".f::RESET
				.f::RED . "Grund: " . f::YELLOW . f::BOLD . "$reason\n".f::RESET
				. f::RED . "Gebannt bis zum: " . f::BOLD . f::YELLOW . "$rbanneduntil");
			$event->setCancelled(true);
		}
	}
	}
}