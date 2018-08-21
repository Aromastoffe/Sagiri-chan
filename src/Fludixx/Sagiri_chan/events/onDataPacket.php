<?php
declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;

class onDataPacket
	implements Listener
{

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function onGetPacket(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if ($packet instanceof ModalFormResponsePacket) {
			$this->api->getLogger()->info($player->getName()." recivied a ModalFormResponsePacket!");
			$pdata = json_decode($packet->formData);
			if ($packet->formId == 6560) {
				switch ($pdata) {
					case 0:
						$pname = $player->getName();
						$c = new Config("/cloud/users/$pname.yml");
						$c->set("accepted", true);
						$c->save();
						break;
					case 1:
						$player->kick("Kicked by ".sagiri::NAME."\n".f::WHITE."Grund: ".f::BOLD.f::YELLOW."Nutzerbedingungen wurden abgelehnt!", false);
				}
			}
		}
	}
}