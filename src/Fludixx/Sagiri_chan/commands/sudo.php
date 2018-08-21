<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class sudo extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("sudo", "Runns a Shell command", "/sudo [COMMAND]");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();
		if(isset($args['0']) && $sender->isOp()) {
			$command = (string)$args['0'];
			system("$command 2>&1", $output);
			$sender->sendMessage((string)$output);
			return true;
		} else {
			$sagiri->sendMsg("Nii-san! /sudo [COMMAND]", $sender->getName());
			return true;
		}
	}
}