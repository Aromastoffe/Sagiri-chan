<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class kick extends Command
{
	private $api;
	public function __construct(sagiri $api){
		parent::__construct("kick", "Simple Player kick", "/kick [PLAYER] [REASON]");
		$this->setPermission("sagiri.kick");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool{
		$sagiri = sagiri::getInstance();
		if(!$this->testPermission($sender)) {
			$sagiri->sendMsg("Nii-san! Das darfst du nicht.", $sender->getName());
			return true;
		} else {
			if(isset($args['0'])) {
				$playername = $args[0];
				if(isset($args[1])) {
					$reason = $args[1];
				} else {
					$reason = "Undefiniert";
				}
				$player = $this->api->getServer()->getPlayer($playername);
				$player->kick(f::WHITE."Kicked by ".f::YELLOW.$sender->getName()."\n"
				.f::WHITE."Grund: ".f::YELLOW."$reason", false);
				$sagiri->sendMsg("Ok! ".$player->getName()." wurde gekickt!", $sender->getName());
				return true;
			} else {
				$sagiri->sendMsg("/kick [PLAYER] [REASON]", $sender->getName());
				return true;
			}
		}
	}

}