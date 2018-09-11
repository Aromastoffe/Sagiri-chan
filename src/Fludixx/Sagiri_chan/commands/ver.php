<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class ver extends Command
{
	private $api;
	public function __construct(sagiri $api){
		parent::__construct("ver", "/ver", "/ver");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool{
		$sender->sendMessage(sagiri::PREFIX.sagiri::STABLE.sagiri::VERSION);
		$sender->sendMessage("Plugins using API: ".f::YELLOW.$this->api->loaded);
		return true;
	}

}