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
		$accepted = $c->get("accepted");
		$vdc = $c->get("vdiscord");
		if($vdc != false) {
			$sagiri->sendMsg("Du bist mit: ".f::UNDERLINE.f::YELLOW."$vdc".f::RESET.f::WHITE." eingeloggt!",
				$player->getName());
		}
		$player->allowMovementCheats();
		if(!$c->get("rank")) {
			$this->api->giveRank("none", $pname);
		}
		if(!is_file("/cloud/groups/none.yml")) {
			@mkdir("/cloud/groups");
			$none = new Config("/cloud/groups/none.yml", 2);
			$none->set("chatformat", "§8[§7Player§8] §f{name}§8:§7 {msg}");
			$none->set("nametag", "§8[§7PLAYER§8]§f {name}");
			$none->set("permissions", array());
			$none->save();
		}
		$this->api->reloadPermisons($player);
		$rank = new Config("/cloud/groups/".$c->get("rank").".yml", 2);
		$nametag = (string)$rank->get("nametag");
		$nametag = str_replace("{name}", $pname, $nametag);
		$player->setNameTag($nametag);
	}
}