<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan;

use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\plugin\MethodEventExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;

use Fludixx\Sagiri_chan\events\{
	MuteListener, BanListener,
	JoinEvent, HitCheck};

class SagiriAPI extends PluginBase implements Listener{

	const PREFIX = f::BOLD.f::GOLD."BOT".f::RESET.f::YELLOW." Sagiri-chan ".f::DARK_GRAY."» ".f::WHITE;
	const NAME = f::BOLD.f::GOLD."BOT".f::RESET.f::YELLOW." Sagiri-chan";
	const VERSION = 0.1;
	const API = 3;
	public const OS_ANDROID = 1;
	public const OS_IOS = 2;
	public const OS_MAC = 3;
	public const OS_FIRE = 4;
	public const OS_GEARVR = 5;
	public const OS_HOLOLENS = 6;
	public const OS_WINDOWS = 7;
	private static $instance = null;

	public function onEnable() : void{
		self::$instance = $this;
		$this->getLogger()->info(self::PREFIX."API wird Geladen...");
		// CLOUD SYSTEM
		if(!is_dir("/cloud")) {
			$action = (bool)@mkdir("/cloud");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/users")) {
			$action = (bool)@mkdir("/cloud/users");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/users wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/elo")) {
			$action = (bool)@mkdir("/cloud/elo");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/elo wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/cfg")) {
			$action = (bool)@mkdir("/cloud/cfg");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/cfg wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/maps")) {
			$action = (bool)@mkdir("/cloud/maps");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/maps wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/coins")) {
			$action = (bool)@mkdir("/cloud/coins");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/coins wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_dir("/cloud/other")) {
			$action = (bool)@mkdir("/cloud/other");
			if($action == true ) {$action = f::GREEN."sucess";} else {$action = f::RED."failure";}
			$this->getLogger()->info(self::PREFIX."CLOUDWURZEL/other wurde nicht gefunden! Erstelle... ".$action);
		}
		if(!is_file("/cloud/sagiri.yml")) {
			$this->getLogger()->info(self::PREFIX."Erstelle Sagiri-Configuration... (/cloud/sagiri.yml)");
			$sagiri = new Config("/cloud/sagiri.yml", 2);
			$sagiri->set("name", "Powered by Sagiri!");
			$sagiri->set("anticheat", true);
			$sagiri->save();
		}
		$this->registerCommands();
		$this->getServer()->getPluginManager()->registerEvents(new BanListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new MuteListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Joinevent($this), $this);
		$this->getLogger()->info(self::PREFIX."API Geladen!");
		$sagiri = new Config("/cloud/sagiri.yml", 2);
		$ac = (bool)$sagiri->get("anticheat");
		if($ac) {
			$this->getServer()->getPluginManager()->registerEvents(new HitCheck($this), $this);
			$this->getLogger()->info(self::PREFIX."AntiCheat Geladen!");
		}

	}

	public function getOS(Player $player) : string {
		$osint = $player->getDeviceOS();
		if($osint == self::OS_ANDROID) {return "Android";}
		if($osint == self::OS_IOS) {return "IOS";}
		if($osint == self::OS_WINDOWS) {return "MS Windows";}
		if($osint == self::OS_MAC) {return "MacOS";}
		if($osint == self::OS_GEARVR) {return "VR Headset";}
		if($osint == self::OS_HOLOLENS) {return "MS Hololens";}
			else {return "???";}
	}

	public function sendMsg(string $msg = "Message", string $playername, bool $isSagiri = true) : bool {
		if($playername == "CONSOLE" || $playername == "console") {
			$this->getLogger()->info("$msg");
			return true;
		} else {
			$player = $this->getServer()->getPlayer($playername);
			if ($isSagiri == true) {
				$prefix = self::PREFIX;
			} else {
				$prefix = "";
			}
			$player->sendMessage($prefix . "$msg");
			return true;
		}
	}
	public function getTimeStamp() : string {
		$timestap = date("U");
		return $timestap;
	}
	public function sendLevelBrodcast(string $msg = "Level Brodcast", Level $level, bool $isSagiri = true) : int {
		$players = $this->getServer()->getOnlinePlayers();
		$counter = 0;
		if($isSagiri == true) {
			$prefix = self::PREFIX;
		} else {
			$prefix = "";
		}
		foreach($players as $player) {
			if($player->getLevel()->getFolderName() == $level->getFolderName()) {
				$counter++;
				$player->sendMessage($prefix."$msg");
			}
		}
		return $counter;
	}
	public function getPlayerConfig(Player $player) : Config {
		return new Config("/cloud/".$player->getName().".yml", 2);
	}
	public function getRegistered(string $playername = "Steve") {
		return is_file("/cloud/users/$playername.yml");
	}
	public function setBanned(string $playername, bool $banned = true) : bool {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("banned", $banned);
		$c->save();
		$player = $this->getServer()->getPlayer($playername);
		if(!$player) {
			return false;
		} else {
			$banner = $c->get("bannedby");
			$banneduntil = $c->get("banneduntil");
			$reason = $c->get("reason");
			$rbanneduntil = date('r', (int)$banneduntil);
			$player->kick(f::RED . "Du wurdest gebannt von: " . f::YELLOW.f::BOLD . "$banner\n".f::RESET
				.f::RED .
				"Grund: "
				. f::YELLOW . f::BOLD . "$reason\n".f::RESET
				. f::RED . "Gebannt bis zum: " . f::BOLD . f::YELLOW . "$rbanneduntil", false);
		}
		return $banned;
	}
	public function setBanReason(string $playername, string $reason, string $bannedby = "API") {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("reason", $reason);
		$c->set("bannedby", $bannedby);
		$c->save();
	}
	public function setBanTemp(string $playername, float $days) {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("initDays", $days);
		$ntime = date("U");
		$calc = 86400 * (int)$days;
		$banneduntil = bcadd((string)$ntime, (string)$calc, 0);
		$c->set("banneduntil", $banneduntil);
		$c->save();
	}
	public function getBanned(string $playername) : bool {
		$c = new Config("/cloud/users/$playername.yml", 2);
		return (bool)$c->get("banned");
	}

	public function returnPrefix() : string {
		return self::PREFIX;
	}

	public function setMuted(string $playername, bool $muted = true) : bool {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("muted", $muted);
		$c->save();
		$player = $this->getServer()->getPlayer($playername);
		if(!$player) {
			return false;
		} else {
			$mutedby = $c->get("mutedby");
			$reason = $c->get("reason");
			$muteduntil = $c->get("muteduntil");
			$rmuteduntil = date('r', (int)$muteduntil);
			$player->sendMessage(self::PREFIX."Du wurdest von ".f::RED."$mutedby".f::WHITE." gemuted!\n Grund: ".f::RED
				."$reason\n".f::WHITE."Gemuted bis zum: ".f::RED."$rmuteduntil");
			return true;
		}
	}

	public function getMuted(string $playername) : bool {
		$c = new Config("/cloud/users/$playername.yml", 2);
		return (bool)$c->get("muted");
	}

	public function setMuteReason(string $playername, string $reason, string $bannedby = "API") {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("reason", $reason);
		$c->set("mutedby", $bannedby);
		$c->save();
	}
	public function setMuteTemp(string $playername, float $days) {
		$c = new Config("/cloud/users/$playername.yml");
		$c->set("initDays", $days);
		$ntime = date("U");
		$calc = 86400 * (int)$days;
		$banneduntil = bcadd((string)$ntime, (string)$calc, 0);
		$c->set("muteduntil", $banneduntil);
		$c->save();
	}

	public function genRandomInterger() {
		$i1 = mt_rand(1,9);
		$i2 = mt_rand(0,9);
		$i3 = mt_rand(0,9);
		$i4 = mt_rand(0,9);
		return $i1.$i2.$i3.$i4;
	}

	public static function getInstance(){
		return self::$instance;
	}

	private function registerCommands(){
		$map = $this->getServer()->getCommandMap();
		$commands = [
			"\\Fludixx\Sagiri_chan\\commands\\sban" => "sban",
			"\\Fludixx\Sagiri_chan\\commands\\suban" => "suban",
			"\\Fludixx\Sagiri_chan\\commands\\mute" => "mute",
			"\\Fludixx\Sagiri_chan\\commands\\unmute" => "unmute",
			"\\Fludixx\Sagiri_chan\\commands\\report" => "report",
			"\\Fludixx\Sagiri_chan\\commands\\jumpto" => "jumpto",
			"\\Fludixx\Sagiri_chan\\commands\\dc" => "dc"
		];
		foreach($commands as $class => $cmd){
			$map->register("sagiri-chan", new $class($this));
		}
	}


	public function onDisable() : void{
		$this->getLogger()->info("API Disabled");
	}
}
