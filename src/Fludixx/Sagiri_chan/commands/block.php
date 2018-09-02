<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class block extends Command
{
	private $api;
	public function __construct(sagiri $api){
		parent::__construct("block", "Time bans a Player with Sagiri-chan API", "/sban [PLAYER] [TIME IN DAYS] [REASON]");
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
					$reason = implode(" ", $args);
					$reason = explode(" ", $reason);
					unset($reason[0]);
					unset($reason[1]);
					$reason = implode(" ", $reason);
					$sagiri->setBanReason($name, $reason, $sender->getName());
					$sagiri->setBanTemp($name, $days);
					$sagiri->setBanned($name, true);
					$sagiri->sendMsg("Ok! $name wurde $days Tage gebannt!", $sender->getName());
					return true;
				}
			} else {
				$sagiri->sendMsg("/sban [PLAYER] [TIME IN DAYS] [REASON]", $sender->getName());
				return true;
			}
		}
	}

}