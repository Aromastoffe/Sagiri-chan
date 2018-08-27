<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;

class MuteListener
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}
	public function onWrite(PlayerChatEvent $event) {
		$msg = $event->getMessage();
		str_replace("noob", "Pro", $msg);
		str_replace("ez", "pretty Good", $msg);
		str_replace("@shrug", "¯\\_(ツ)_/¯", $msg);
		$sagiri = sagiri::getInstance();
		if($sagiri->getMuted($event->getPlayer()->getName())) {
			$pname = $event->getPlayer()->getName();
			$c = new Config("/cloud/users/$pname.yml", 2);
			$date = date("U");
			$reason = $c->get("reason");
			$mutedby = $c->get("mutedby");
			$muteduntil = $c->get("muteduntil");
			$ip = $event->getPlayer()->getAddress();
			$land = file_get_contents("https://ipapi.co/$ip/country");
			$c = new Config("/cloud/users/$pname.yml", 2);
			date_default_timezone_set('Europe/Berlin');
			$readTime = date('l, mS F Y  H:i', (int)$muteduntil);
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
			$rmuteduntil = $readTime;
			if($land == "Undefined" && substr($ip, 0, 3) != "127" && substr($ip, 0, 3) != "192") {
				$rmuteduntil = "Were Sorry! :( Something went wrong...";
			}
			if((int)$date >= (int)$muteduntil) {
				$pname = $event->getPlayer()->getName();
				$this->api->getLogger()->info(sagiri::PREFIX."Unmuting $pname due to: \"Mute Expired\"");
				$sagiri->setMuted($event->getPlayer()->getName(), false);
				$event->setCancelled(false);
				return true;
			}
			else {
				$event->getPlayer()->sendMessage(sagiri::PREFIX . "Du wurdest von " . f::RED . "$mutedby" . f::WHITE . " gemuted!\n Grund: "
					. f::RED
					. "$reason\n" . f::WHITE . "Gemuted bis zum: " . f::RED . "$rmuteduntil");
				$event->setCancelled(true);
				return false;
			}
		} else {
			$event->setCancelled(false);
		}
	}

}