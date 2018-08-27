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

class Knockback
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
			mt_srand(ip2long($player->getAddress())+ip2long($oplayer->getAddress()));
			$knockback = new Vector3(1 * (-sin($oplayer->yaw / 180 * M_PI) * cos($oplayer->pitch / 180 * M_PI)),
				-sin($oplayer->pitch / 180 * M_PI) + 0.5,
				1 * (cos($oplayer->yaw / 180 * M_PI) * cos($oplayer->pitch / 180 * M_PI)));
			$player->setMotion($knockback);
		}
	}
}