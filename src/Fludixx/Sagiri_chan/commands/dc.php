<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use Fludixx\Sagiri_chan\tasks;

class dc extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("dc", "Discord Command", "/dc");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();#
		$id = (string)$sagiri->genRandomInterger();
		$sagiri->sendMsg("Hier ist deine ID: $id\nDie ID wird nach 2 Minuten verfallen!", $sender->getName());
		$c = new Config("/cloud/$id.yml", 2);
		$c->set("mcname", $sender->getName());
		$c->save();
		$this->api->getScheduler()->scheduleDelayedTask(new tasks\IDVerify($this->api, (int)$id), 20 * 120);
		return true;
	}
}
