<?php
namespace Fludixx\Sagiri_chan\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;

class KickOnFly extends Task
{
	public $api;
	public $player;

	public function __construct(sagiri $api, Player $player)
	{
		/**
		 * @param sagiri $api
		 * @param Player $player
		 */
		$this->api = $api;
		$this->player = $player;
	}

	public function onRun(int $tick)
	{
		$this->player->kick(f::WHITE."Kicked by ".sagiri::NAME.f::RED."\nGrund: ".f::WHITE.f::BOLD."Fly");
		$players = $this->api->getServer()->getOnlinePlayers();
		$name = $this->player->getName();
		foreach($players as $player) {
			if($player->hasPermission("sagiri.administrative")) {
				$sagiri = sagiri::getInstance();
				$sagiri->sendMsg("$name wurde wegen Fly von Sagiri gekickt!");
			}
		}
	}
}