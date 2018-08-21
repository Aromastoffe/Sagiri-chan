<?php

declare(strict_types=1);

namespace Fludixx\Sagiri_chan\events;

use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;

class HitCheck
	implements Listener
{
	public $api;

	public function __construct(sagiri $api)
	{
		$this->api = $api;
	}

	public function HitCheck(EntityDamageByEntityEvent $event)
	{
		$player = $event->getEntity();
		$oplayer = $event->getDamager();
		if ($player instanceof Player
			&& $oplayer instanceof Player) {
			$ping = $player->getPing();
			if ($ping >= 800) {
				$player->kick(f::WHITE . "Kicked by " . sagiri::NAME . "\n" . f::RED .
					"Grund: " . f::WHITE . "Player Ping > 800",
					false);
				return 0;
			} else {
				if (!$oplayer->isOp()) {
					$pos1 = new Vector3($oplayer->getX(), $oplayer->getY(), $oplayer->getZ());
					$inbetween = $player->distance($pos1);
					if($inbetween >= 4) {
						$event->setCancelled(true);
						$this->api->getLogger()->info("Action Blocked");
					}
				}
			}
		}
	}
}