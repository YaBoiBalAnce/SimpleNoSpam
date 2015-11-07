<?php
namespace sns;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
class main extends PluginBase implements Listener{
	public $talked = array();
	private $config;
	public function onEnable(){
		$this->getLogger()->info("Enabled Plugin!");
		$this->getServer()->getPluginManager()->registerEvents($this ,$this);
		@mkdir($this->getDataFolder());
		$this->config = new Config($this->getDataFolder()."mobinfo.yml", Config::YAML, array(
				"replace words" => "true",
				"Replacement Message" => "Im a Fuckboi!",
				"Blocked words" => array(
						"fuck","dick", "cunt","fag","faggit","fuk","fgt"
				),
				"interval" => 5
				
		
		
		
		));
		$this->config->save();
	}
	
	public function onTalk(PlayerChatEvent $ev){
		if ($ev->getPlayer()->hasPermission("simplenospam.bypass")){
			$ev->setCancelled(false);
			return true;
		}
		if (in_array($ev->getPlayer()->getName(), $this->talked)){
			$config = $this->config->getAll();
			$ev->setCancelled();
			$ev->getPlayer()->sendMessage("[AntiSpam] Not allowed to talk again for ".$config['interval']." seconds");
		}
		else if (!in_array($ev->getPlayer()->getName(), $this->talked)){
			$bw = $this->config->getAll();
			$msg = explode(" ",$ev->getMessage());
			foreach ($msg as $word){
				foreach ($bw['Blocked words'] as $blw){
					if ($blw === strtolower($word)){
						if ($bw['replace words'] === "true"){
							$ev->setMessage($bw['Replacement Message']);
							$ev->getPlayer()->sendMessage("[AnitSpam] You are not allowed to say the word ".$blw);
							return true;
						}else{
							$ev->setCancelled();
							$ev->getPlayer()->sendMessage("[AnitSpam] You are not allowed to say the word ".$blw);
							return true;
						}
					}
				}
			}
		 	array_push($this->talked, $ev->getPlayer()->getName());
		 	$task = new allowtalk($this, $ev->getPlayer());
		 	$this->getServer()->getScheduler()->scheduleDelayedTask($task, 20* $bw['interval']);
		 	return;
		}
		
		
	}
	
	
}