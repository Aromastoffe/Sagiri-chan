<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class unblock extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("unblock", "Unbans a Player with Sagiri-chan API", "/suban [PLAYER]");
		$this->setPermission("sagiri.administrative");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();
		if (!$this->testPermission($sender)) {
			$sagiri->sendMsg("Nii-san! Das darfst du nicht.", $sender->getName());
			return true;
		} else {
			if(isset($args['0'])) {
				$sagiri->setBanned((string)$args['0'], false);
				$sagiri->sendMsg("Ok! Ich habe $args[0] für dich entbannt Nii-san!", $sender->getName());
				return true;
			}
		}
	}
}