<?php
namespace Fludixx\Sagiri_chan\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use Fludixx\Sagiri_chan\tasks;

class rank extends Command
{
	private $api;

	public function __construct(sagiri $api)
	{
		parent::__construct("rank", "Rank Command", "/rank [PLAYER] set [RANK]");
		$this->api = $api;
	}

	public function execute(CommandSender $sender, string $label, array $args): bool
	{
		$sagiri = sagiri::getInstance();
		if(empty($args[0])) {
			$this->api->sendMsg("/rank [PLAYER] set [RANK]", $sender->getName());
			return true;
		} else {
			if(empty($args[1])) {
				$this->api->sendMsg("/rank [PLAYER] set [RANK]", $sender->getName());
				return true;
			} else {
				if(empty($args[2])) {
					$this->api->sendMsg("/rank [PLAYER] set [RANK]", $sender->getName());
					return true;
				} else {
					if($args[1] == "set") {
						if(is_file("/cloud/groups/{$args[2]}.yml")) {
							$player = $this->api->getServer()->getPlayer($args[1]);
							if(!$player) {
								$c = new Config("/cloud/users/{$args[2]}.yml", 2);
							} else {
								$c = new Config("/cloud/users/{$player->getName()}.yml", 2);
							}
							$c->set("rank", $args[2]);
							$c->save();
							if($player instanceof Player) {
								$player->kick("Kicked by ".sagiri::NAME."\n".f::WHITE."Dein Rang wurde zu: "
									.f::YELLOW."$args[2]".f::WHITE." geändert!");
							}
							$this->api->sendMsg("Rang wurde erfolgreich gesetzt!", $sender->getName());
							return true;
						} else {
							$this->api->sendMsg("Hmm... Ich konnte keinen Rang namens {$args[2]} finden.",
								$sender->getName());
						}
					} else {
						$this->api->sendMsg("/rank [PLAYER] set [RANK]", $sender->getName());
						return true;
					}
				}
			}
		}
	}
}
