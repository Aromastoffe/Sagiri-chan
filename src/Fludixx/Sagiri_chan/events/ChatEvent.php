<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;

class ChatEvent
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function ChatEvent(PlayerChatEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$msg = $event->getMessage();
		$c = new Config("/cloud/users/$name.yml", 2);
		$rank = new Config("/cloud/groups/".$c->get("rank").".yml", 2);
		$chatformat = (string)$rank->get("chatformat");
		$chatformat = str_replace("{name}",  $name, $chatformat);
		$chatformat = str_replace("{msg}",  $msg, $chatformat);
		$event->setFormat($chatformat);
	}
}