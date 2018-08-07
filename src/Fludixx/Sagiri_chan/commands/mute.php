<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class mute extends Command
{
	private $api;
	public function __construct(sagiri $api){
		parent::__construct("mute", "Time Mutes a Player with Sagiri-chan API", "/mute [PLAYER] [TIME IN DAYS] [REASON]");
		$this->setPermission("sagiri.administrative");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool{
		$sagiri = sagiri::getInstance();
		if(!$this->testPermission($sender)) {
			$sagiri->sendMsg("Nii-san! Das darfst du nicht.", $sender->getName());
			return true;
		} else {
			if(isset($args['0']) && $args['1'] && $args['2']) {
				if(!is_numeric($args['1'])) {
					$sagiri->sendMsg("Nii-san! $args[1] ist keine Nummer! (Banzeit in Tagen)", $sender->getName());
					return true;
				} else {
					$name = (string)$args['0'];
					$days = (int)$args['1'];
					$reason = (string)$args['2'];
					$sagiri->setMuteReason($name, $reason, $sender->getName());
					$sagiri->setMuteTemp($name, $days);
					$sagiri->setMuted($name, true);
					$sagiri->sendMsg("Ok! $name wurde $days Tage gemuted!", $sender->getName());
					return true;
				}
			} else {
				$sagiri->sendMsg("/mute [PLAYER] [TIME IN DAYS] [REASON]", $sender->getName());
				return true;
			}
		}
	}

}