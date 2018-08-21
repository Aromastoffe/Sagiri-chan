<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;

class report extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("report", "Reports a Player to Admins", "/report [PLAYER] [REASON]");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();
		if(isset($args['0']) && isset($args['1'])) {
			$playerr = $this->api->getServer()->getPlayer($args['0']);
			if(!$playerr) {
				$sagiri->sendMsg("Nii-san ich konnte diesen Spieler nicht finden! Tipp: du musst nicht den Namen ausschreiben", $sender->getName());
				return true;
			}
			if($playerr->getName() == $sender->getName()) {
				$sagiri->sendMsg("Dummer Nii-san! Du kannst dich nicht selber Reporten!", $sender->getName());
				return true;
			}
			$sagiri->sendMsg("Du hast ".$playerr->getName()." wegen $args[1] erfolgreich Reportet!", $sender->getName
			());
			$players = $this->api->getServer()->getOnlinePlayers();
			foreach($players as $player) {
				if($player->hasPermission("sagiri.administrative")) {
					$player->sendMessage(f::YELLOW.$playerr->getName().f::WHITE." wurde wegen ".f::YELLOW."$args[1]"
						.f::WHITE." von ".f::YELLOW.$sender->getName().f::WHITE." Reportet! ".f::GREEN."/jumpto $args[0]");
				}
				return true;
			}
		} else {
			$sagiri->sendMsg("Nii-san! /report [PLAYER] [REASON]", $sender->getName());
			return true;
		}
	}
}