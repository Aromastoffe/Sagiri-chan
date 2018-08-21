<?php
namespace Fludixx\Sagiri_chan\tasks;

use pocketmine\scheduler\Task;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;

class motdChanger extends Task
{
	public $api;

	public function __construct(sagiri $api)
	{
		/**
		 * @param sagiri $api
		 * @param int $id
		 */
		$this->api = $api;
	}

	public function onRun(int $tick)
	{
		$c = new Config("/cloud/sagiri.yml", 2);
		$ran = $c->get("ran");
		$motds = (array)$c->get("motd");
		$sname = $c->get("name");
		$this->api->getServer()->getNetwork()->setName($sname.f::DARK_GRAY." - ".f::RESET.$motds[$ran]);
		$ran++;
		if($ran == 2) {$ran = 0;}
		$c->set("ran", $ran);
		$c->save();
	}
}