<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan;

use Fludixx\Sagiri_chan\tasks\motdChanger;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\plugin\MethodEventExecutor;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\Player;
use pocketmine\form\CustomForm;

use Fludixx\Sagiri_chan\events\{
	MoveEvent, MuteListener, BanListener, JoinEvent, HitCheck, onDataPacket, QueryRegenerateEvent
};

class SagiriAPI extends PluginBase implements Listener{

	const PREFIX = f::BOLD.f::GOLD."BOT".f::RESET.f::YELLOW." Sagiri-chan ".f::DARK_GRAY."» ".f::WHITE;
	const NAME = f::BOLD.f::GOLD."BOT".f::RESET.f::YELLOW." Sagiri-chan";
	const VERSION = 0.2;
	const STABLE = "s";
	const API = 3;
	public $canrun = false;
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
			$sagiri->set("anticheat", true);
			$sagiri->set("server_ports", array());
			$sagiri->set("main_port", $this->getServer()->getPort());
			$sagiri->save();
		}
		@mkdir("/cloud/".$this->getServer()->getPort());
		$sagiri = new Config("/cloud/".$this->getServer()->getPort()."/config.yml", 2);
		if($sagiri->get("name") == false) {
			$sagiri->set("name", "Sagiri-Server-01");
			$sagiri->set("player_counter", false);
			$sagiri->set("players", 0);
			$sagiri->save();
		}
		$sagiri = new Config("/cloud/sagiri.yml", 2);
		$sagiri->set("ran", 0);
		$sagiri->save();
		//$this->getScheduler()->scheduleRepeatingTask(new motdChanger($this), 100);
		$this->registerCommands();
		$this->getServer()->getPluginManager()->registerEvents(new BanListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new MuteListener($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new Joinevent($this), $this);
		$this->getServer()->getPluginManager()->registerEvents(new QueryRegenerateEvent($this), $this);
		//$this->getServer()->getPluginManager()->registerEvents(new onDataPacket($this), $this);
		//$this->getServer()->getPluginManager()->registerEvents(new MoveEvent($this), $this);

		$this->getLogger()->info(self::PREFIX."API Geladen!");
		$sagiri = new Config("/cloud/sagiri.yml", 2);
		$ac = (bool)$sagiri->get("anticheat");
		if($ac) {
			$this->getServer()->getPluginManager()->registerEvents(new HitCheck($this), $this);
			$this->getLogger()->info(self::PREFIX."AntiCheat Geladen!");
		}
		if($this->getFullName() == $this->getFullName()) {$this->getLogger()->info("c.".$this->getFullName()."="."c."
			."Sagiri-API"." v".self::VERSION);$this->getLogger()->info("Prudukt Key: ".self::STABLE."."
			.$this->getName()."-"."v".self::STABLE.self::VERSION);
		if(!($this->getFullName() != "Sagiri-API"." v".self::VERSION))
		{$stringi="I";$api = $this->getServer()->getPluginManager()->getPlugin("Sagiri-AP".$stringi);if
		((string)$this->getFullName()."!" != "Sagiri-API"." v".self::VERSION."!"){$this->getLogger()->error
		(self::PREFIX."Fehler beim laden der API!");$this->canrun=false;}else{$this->canrun=true;}}}

	}

	public function sendDSGVO(Player $player) {
		$player->sendMessage(self::PREFIX."Hey! Bitte Akzeptiere unsere Nutzerbedingungen!");
		$this->getLogger()->info("sendDSGVO was executed!");
		$data = [];
		$data['title'] = "Nutzerbedingungen ".f::BOLD."DSGVO";
		$data['buttons'] = [];

		$data['content'] = "Um dir die Funktionen des Netzwerks bereitstellen zu können und Sicherheitsmaßnahmen gegen Hacker und Regelverstöße durchführen zu können benötigen wir dein Einverständnis folgende Daten zu deinem XBOX Live Account erheben und verarbeiten zu können: \n
- IP Adressen, Verknüpfte Accounts, XBOX Live Username\n
- Spielaktionenn\n
- Spiel- und Chatverhalten\n
Wir sichern deine Daten nach dem Stand der Technik und verkaufen diese nicht an Dritte. Teile deiner Spielaktionen (Statistiken in Mini-Spielen) sind öffentlich zu jedem Account einsehbar zum Zwecke der Bereitstellung von Ranglisten. \n
Mit dem Klick auf “Annehmen” oder des Schliesen dieses Fensters stimmst du der Speicherung und Verarbeitung der Daten auf Basis dieser Erklärung zu. \n
Weiterhin stimmst du zu, die Serverregeln zur Kenntnis zu nehmen und einzuhalten.";

		$data['type'] = "form";
		$data['buttons'][] = ['text' => '§aAnnehmen'];
		$data['buttons'][] = ['text' => '§cAblehnen'];
		$packet = new ModalFormRequestPacket();
		$packet->formId = 6560;
		$packet->formData = json_encode($data);
		$player->sendDataPacket($packet);
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
	public function getCoOp(string $name = "???"):bool {
		$this->getLogger()->info(self::PREFIX."Das Plugin $name fragt nach Sagiri-API...");
		if(!($this->getFullName() != "Sagiri-API")){$stringi="I";if(!$this->canrun){$op=false;$pl = $this->getServer()
			->getPluginManager()->getPlugin("Sagiri-AP".$stringi);if($op){$op=false;}$pl->getLogger()->error(
				self::PREFIX."Ein Unbekannter Fehler ist Aufgetretten! Stelle sicher das du das Originale Plugin verwendest!");return $op;}elseif
		($this->canrun){$op=true;$this->getLogger()->info("Dem Plugin $name wurde Sagiri API Erteilt!");return $op;
		}}else{$stringi="I";if(!$this->canrun){$op=false;$pl = $this->getServer()
			->getPluginManager()->getPlugin("Sagiri-AP".$stringi);if($op){$op=false;}$pl->getLogger()->error(
			self::PREFIX."Ein Unbekannter Fehler ist Aufgetretten! Stelle sicher das du das Originale Plugin verwendest!");return $op;}elseif
		($this->canrun){$op=true;$this->getLogger()->info("Dem Plugin $name wurde Sagiri API Erteilt!");return $op;}}
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
			"\\Fludixx\Sagiri_chan\\commands\\dc" => "dc",
			"\\Fludixx\Sagiri_chan\\commands\\sudo" => "sudo",
			"\\Fludixx\Sagiri_chan\\commands\\lobby" => "lobby"
		];
		foreach($commands as $class => $cmd){
			$map->register("sagiri-chan", new $class($this));
		}
	}


	public function onDisable() : void{
		$this->getLogger()->info("API Disabled");
	}
}
