<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;


class BanListener
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onConnect(PlayerPreLoginEvent $event) {
	$prefix = sagiri::NAME;
	$sagiri = sagiri::getInstance();
	$player = $event->getPlayer();
	$pname = $player->getName();
	$this->api->getLogger()->info(sagiri::PREFIX."Logged in $pname");
	$banned = $sagiri->getBanned($player->getName());
	$ip = $player->getAddress();
	$land = file_get_contents("https://ipapi.co/$ip/country");
	$c = new Config("/cloud/users/$pname.yml", 2);
		$banner = $c->get("bannedby");
		$banneduntil = $c->get("banneduntil");
		$reason = $c->get("reason");
		date_default_timezone_set('Europe/Berlin');
		$readTime = date('l, mS F Y  H:i', (int)$banneduntil);
		if($land == "DE" || $land == "AT" || $land == "IT" || $land == "CH" || substr($ip, 0, 3) == "127") {
			// TAGE
			str_replace("Monday", "Montag", $readTime);
			str_replace("Tuesday", "Dienstag", $readTime);
			str_replace("Wednesday", "Mittwoch", $readTime);
			str_replace("Thursday", "Donnerstag", $readTime);
			str_replace("Friday", "Freitag", $readTime);
			str_replace("Saturday", "Samstag", $readTime);
			str_replace("Sunday", "Sonntag", $readTime);
			// MONATE
			str_replace("January", "Januar", $readTime);
			str_replace("February", "Februar", $readTime);
			str_replace("March", "März", $readTime);
			str_replace("May", "Mai", $readTime);
			str_replace("June", "Juni", $readTime);
			str_replace("July", "Juli", $readTime);
			str_replace("December", "Dezember", $readTime);
		}
		$rbanneduntil = $readTime;
		if($land == "Undefined" && substr($ip, 0, 3) != "127" && substr($ip, 0, 3) != "192") {
			$rbanneduntil = "Were Sorry! :( Something went wrong...";
		}
	if($banned) {
		$date = date("U");
		$this->api->getLogger()->info(sagiri::PREFIX."Compararing $date >= $banneduntil");
		if((int)$date >= (int)$banneduntil) {
			$this->api->getLogger()->info(sagiri::PREFIX."Unbanning $pname due to: \"Ban Expired\"");
			$sagiri->setBanned($player->getName(), false);
		} else {
			$this->api->getLogger()->info(sagiri::PREFIX."$pname stays banned.");
			$event->setKickMessage(f::RED .
				"Du wurdest gebannt von: " . f::YELLOW.f::BOLD . "$banner\n".f::RESET
				.f::RED . "Grund: " . f::YELLOW . f::BOLD . "$reason\n".f::RESET
				. f::RED . "Gebannt bis zum: " . f::BOLD . f::YELLOW . "$rbanneduntil");
			$event->setCancelled(true);
		}
	}
	}
}