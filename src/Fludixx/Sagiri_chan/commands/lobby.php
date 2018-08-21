<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class lobby extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("lobby", "Teleport to Spawn", "/lobby");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();
		if($sender instanceof Player) {
			$sender->setGamemode(0);
			$sender->teleport($this->api->getServer()->getDefaultLevel()->getSafeSpawn());
		}
		return false;
	}
}