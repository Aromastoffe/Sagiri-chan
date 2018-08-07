<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class jumpto extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("jumpto", "Jump to an Player", "/jumpto [PLAYER]");
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
				$player = $this->api->getServer()->getPlayer($args['0']);
				if(!$player) {
					$sagiri->sendMsg("Nii-san! So einen Spieler habe ich nicht gefunden.");
					return true;
				} else {
					if($sender instanceof Player) {
						$pos = $player->getPosition()->asPosition();
						$sender->setGamemode(3);
						$sender->teleport($pos);
						$sagiri->sendMsg("Ok Nii-san!");
						return true;
					} else {
						$sagiri->sendMsg("Nii-san! Du musst ein Spieler sein.");
						return true;
					}
				}
			}
		}
	}
}