<?php
namespace Fludixx\Sagiri_chan\tasks;

use pocketmine\scheduler\Task;
use Fludixx\Sagiri_chan\SagiriAPI as sagiri;
use pocketmine\utils\Config;

class IDVerify extends Task
{
	public $api;
	public $id;

	public function __construct(sagiri $api, int $id)
	{
		/**
		 * @param sagiri $api
		 * @param int $id
		 */
		$this->api = $api;
		$this->id = $id;
	}

	public function onRun(int $tick)
	{
		$c = new Config("/cloud/$this->id.yml", 2);
		$mcname = $c->get("mcname");
		$dcname = $c->get("discordname");
		@unlink("/cloud/$this->id.yml");
		$cp = new Config("/cloud/users/$mcname.yml");
		$cp->set("vdiscord", $dcname);
		$cp->save();
		$this->api->getScheduler()->cancelTask($this->getTaskId());
	}
}