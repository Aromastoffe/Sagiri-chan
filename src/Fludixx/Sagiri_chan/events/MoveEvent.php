<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;


class MoveEvent
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onMove(PlayerMoveEvent $event)
	{
		$prefix = sagiri::NAME;
		$sagiri = sagiri::getInstance();
		$player = $event->getPlayer();
		$name = $player->getName();
		$c = new Config("/cloud/users/$name.yml", 2);
		$accepted = $c->get("accepted");
		if(!$accepted) {
			$event->setCancelled(true);
			$this->api->sendDSGVO($player);
		}
	}
}