<?php
declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;
use \pocketmine\event\server\QueryRegenerateEvent as qre;

class QueryRegenerateEvent
	implements Listener
{

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onQueryRegenerateEvent(qre $event)
	{
		$players = $event->getPlayerCount();
		$port = $this->api->getServer()->getPort();
		$server = new Config("/cloud/servers/$port/config.yml", 2);
		$server->set("players", $players);
		$server->save();
		$sagiri = new Config("/cloud/sagiri.yml", 2);
		if ($this->api->getServer()->getPort() == $sagiri->get("main_port")) {
			$players = $event->getPlayerCount();
			$c = new Config("/cloud/servers/" . $this->api->getServer()->getPort() . "/config.yml", 2);
			$name = (string)$c->get("name");
			if ($c->get("player_counter") == false) {
				return false;
			} else {
				$sagiri = new Config("/cloud/sagiri.yml", 2);
				$ports = $sagiri->get("server_ports");
				$allplayers = 0;
				foreach ($ports as $port) {
					$server = new Config("/cloud/servers/$port/config.yml");
					$players = (int)$server->get("players");
					$allplayers = $allplayers + $players;

				}
				$event->setPlayerCount($allplayers);
			}
		}
	}
}