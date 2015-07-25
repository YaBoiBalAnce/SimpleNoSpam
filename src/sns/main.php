<?php
namespace sns;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
class main extends PluginBase implements Listener{
	public $talked = array();
	public function onEnable(){
		$this->getLogger()->info("Enabled Plugin!");
		$this->getServer()->getPluginManager()->registerEvents($this ,$this);
	}
	
	public function onTalk(PlayerChatEvent $ev){
		if (in_array($ev->getPlayer()->getName(), $this->talked)){
			$ev->setCancelled();
			$ev->getPlayer()->sendMessage("[AntiSpam] Not allowed to talk again for 5 seconds");
		}
		else if (!in_array($ev->getPlayer()->getName(), $this->talked)){
		 	array_push($this->talked, $ev->getPlayer()->getName());
		 	$task = new allowtalk($this, $ev->getPlayer());
		 	$this->getServer()->getScheduler()->scheduleDelayedTask($task, 20*5);
		 	return;
		}
		
		
	}
	
	
}