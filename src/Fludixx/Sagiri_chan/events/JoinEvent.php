<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;

class JoinEvent
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$sagiri = sagiri::getInstance();
		$player = $event->getPlayer();
		$pname = $player->getName();
		$c = new Config("/cloud/users/$pname.yml");
		$vdc = $c->get("vdiscord");
		if($vdc != false) {
			$sagiri->sendMsg("Du bist mit: ".f::YELLOW."$vdc".f::WHITE." eingeloggt!", $player->getName());
		}
	}
}