<?php
namespace sns;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\Player;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

	public $talked = [];
	public $warnings = [];
	
	public $config;
	public $tag = "[SimpleNoSpam]";

	public function onEnable(){
		$this->getLogger()->info(TextFormat::GREEN.$this->tag." Loading plugin..");
		$this->getServer()->getPluginManager()->registerEvents($this ,$this);
		@mkdir($this->getDataFolder());
		if(!file_exists($this->getDataFolder() . "/config.yml")){
			$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
				"#SimpleNoSpam by BalAnce and XFuryMCPE",
				"#Choose whether or not to replace a message that has a swear word or not",
				"replace-words" => "false",
				"#Seconds players have to wait before saying each message",
				"spam-interval" => 3,
				"#Amount of warnings to give before kicking a player",
				"warnings-before-kick" => 3,
				"Message to replace messages with that have swear words",
				"replacement-message" => "I eat dicks!",
				"Words blocked in the swear word filter",
				"blocked-words" => array(
					"4r5e", "5h1t", "5hit", "a55", "anal", "anus", "ar5e", "arrse", "arse", "ass", "ass-fucker", "asses", "assfucker", "assfukka", "asshole", "assholes", "asswhole", "a_s_s", "b!tch", "b00bs", "b17ch", "b1tch", "ballbag", "balls", "ballsack", "bastard", "beastial", "beastiality", "bellend", "bestial", "bestiality", "bi+ch", "biatch", "bitch", "bitcher", "bitchers", "bitches", "bitchin", "bitching", "bloody", "blow job", "blowjob", "blowjobs", "boiolas", "bollock", "bollok", "boner", "boob", "boobs", "booobs", "boooobs", "booooobs", "booooooobs", "breasts", "buceta", "bugger", "bum", "bunny fucker", "butt", "butthole", "buttmuch", "buttplug", "c0ck", "c0cksucker", "carpet muncher", "cawk", "chink", "cipa", "cl1t", "clit", "clitoris", "clits", "cnut", "cock", "cock-sucker", "cockface", "cockhead", "cockmunch", "cockmuncher", "cocks", "cocksuck", "cocksucked", "cocksucker", "cocksucking", "cocksucks", "cocksuka", "cocksukka", "cok", "cokmuncher", "coksucka", "coon", "cox", "crap", "cum", "cummer", "cumming", "cums", "cumshot", "cunilingus", "cunillingus", "cunnilingus", "cunt", "cuntlick", "cuntlicker", "cuntlicking", "cunts", "cyalis", "cyberfuc", "cyberfuck", "cyberfucked", "cyberfucker", "cyberfuckers", "cyberfucking", "d1ck", "damn", "dick", "dickhead", "dildo", "dildos", "dink", "dinks", "dirsa", "dlck", "dog-fucker", "doggin", "dogging", "donkeyribber", "doosh", "duche", "dyke", "ejaculate", "ejaculated", "ejaculates", "ejaculating", "ejaculatings", "ejaculation", "ejakulate", "f u c k", "f u c k e r", "f4nny", "fag", "fagging", "faggitt", "faggot", "faggs", "fagot", "fagots", "fags", "fanny", "fannyflaps", "fannyfucker", "fanyy", "fatass", "fcuk", "fcuker", "fcuking", "feck", "fecker", "felching", "fellate", "fellatio", "fingerfuck", "fingerfucked", "fingerfucker", "fingerfuckers", "fingerfucking", "fingerfucks", "fistfuck", "fistfucked", "fistfucker", "fistfuckers", "fistfucking", "fistfuckings", "fistfucks", "flange", "fook", "fooker", "fuck", "fucka", "fucked", "fucker", "fuckers", "fuckhead", "fuckheads", "fuckin", "fucking", "fuckings", "fuckingshitmotherfucker", "fuckme", "fucks", "fuckwhit", "fuckwit", "fudge packer", "fudgepacker", "fuk", "fuker", "fukker", "fukkin", "fuks", "fukwhit", "fukwit", "fux", "fux0r", "f_u_c_k", "gangbang", "gangbanged", "gangbangs", "gaylord", "gaysex", "goatse", "God", "god-dam", "god-damned", "goddamn", "goddamned", "hardcoresex", "hell", "heshe", "hoar", "hoare", "hoer", "homo", "hore", "horniest", "horny", "hotsex", "jack-off", "jackoff", "jap", "jerk-off", "jism", "jiz", "jizm", "jizz", "kawk", "knob", "knobead", "knobed", "knobend", "knobhead", "knobjocky", "knobjokey", "kock", "kondum", "kondums", "kum", "kummer", "kumming", "kums", "kunilingus", "l3i+ch", "l3itch", "labia", "lmfao", "lust", "lusting", "m0f0", "m0fo", "m45terbate", "ma5terb8", "ma5terbate", "masochist", "master-bate", "masterb8", "masterbat*", "masterbat3", "masterbate", "masterbation", "masterbations", "masturbate", "mo-fo", "mof0", "mofo", "mothafuck", "mothafucka", "mothafuckas", "mothafuckaz", "mothafucked", "mothafucker", "mothafuckers", "mothafuckin", "mothafucking", "mothafuckings", "mothafucks", "mother fucker", "motherfuck", "motherfucked", "motherfucker", "motherfuckers", "motherfuckin", "motherfucking", "motherfuckings", "motherfuckka", "motherfucks", "muff", "mutha", "muthafecker", "muthafuckker", "muther", "mutherfucker", "n1gga", "n1gger", "nazi", "nigg3r", "nigg4h", "nigga", "niggah", "niggas", "niggaz", "nigger", "niggers", "nob", "nob jokey", "nobhead", "nobjocky", "nobjokey", "numbnuts", "nutsack", "orgasim", "orgasims", "orgasm", "orgasms", "p0rn", "pawn", "pecker", "penis", "penisfucker", "phonesex", "phuck", "phuk", "phuked", "phuking", "phukked", "phukking", "phuks", "phuq", "pigfucker", "pimpis", "piss", "pissed", "pisser", "pissers", "pisses", "pissflaps", "pissin", "pissing", "pissoff", "poop", "porn", "porno", "pornography", "pornos", "prick", "pricks", "pron", "pube", "pusse", "pussi", "pussies", "pussy", "pussys", "rectum", "retard", "rimjaw", "rimming", "s hit", "s.o.b.", "sadist", "schlong", "screwing", "scroat", "scrote", "scrotum", "semen", "sex", "sh!+", "sh!t", "sh1t", "shag", "shagger", "shaggin", "shagging", "shemale", "shi+", "shit", "shitdick", "shite", "shited", "shitey", "shitfuck", "shitfull", "shithead", "shiting", "shitings", "shits", "shitted", "shitter", "shitters", "shitting", "shittings", "shitty", "skank", "slut", "sluts", "smegma", "smut", "snatch", "son-of-a-bitch", "spac", "spunk", "s_h_i_t", "t1tt1e5", "t1tties", "teets", "teez", "testical", "testicle", "tit", "titfuck", "tits", "titt", "tittie5", "tittiefucker", "titties", "tittyfuck", "tittywank", "titwank", "tosser", "turd", "tw4t", "twat", "twathead", "twatty", "twunt", "twunter", "v14gra", "v1gra", "vagina", "viagra", "vulva", "w00se", "wang", "wank", "wanker", "wanky", "whoar", "whore", "willies", "willy", "xrated", "xxx"
				),
				"#Message sent when a player tries to spam",
				"spam-block-message" => "Please don't spam! You may talk again in {spamtime} seconds!",
				"#Message sent to a player that tries to swear",
				"swear-block-message" => "Please don't swear!",
				"#Messsage sent to a player when they're kicked for exceeding warning limit",
				"kick-message" => "You have been kicked for using all of your warnings!"
			));
			$this->config->save();
			$this->getLogger()->info(TextFormat::GREEN.$this->tag." New configuration file saved!");
		}
		else{
			$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
			$this->getLogger()->info(TextFormat::GREEN.$this->tag." Configurations loaded!");
		}
		$this->getLogger()->info(TextFormat::GREEN.$this->tag." Plugin loaded!");
	}
	
	public function onJoin(PlayerJoinEvent $e){
		$p = $e->getPlayer();
		$this->warnings[$p->getName()] = 0;
	}
	
	public function onChat(PlayerChatEvent $e){
		$p = $e->getPlayer();
		$m = $e->getMessage();
		if($p->hasPermission("simplenospam.bypass")){
			return;
		}
		
		if(!isset($this->talked[$p->getName()])){
			$this->talked[$p->getName()] = time();
		}
		
		if(isset($this->talked[$p->getName()])){
			$time = ($this->talked[$p->getName()] + $this->config->get("spam-interval")) - time();
			if($time > 0){
				$p->sendMessage($this->config->get("spam-block-message"));
				$e->setCancelled();
				return;
			}
			if($time <= 0){
				$this->talked[$p->getName()] = time();
			}
		}
		foreach($this->config->get("blocked-words") as $bw){
			if(strpos($msg, $bw) != false){
				if($this->config->get("replace-words") == true){
					$e->setMessage($this->config->get("replacement-message"));
					$p->sendMessage($this->config->get("swear-block-message"));
					$this->talked[$p->getName()] = time();
					$this->warnings[$p->getName()] = $this->warnings[$p->getName()] + 1;
					if($this->warnings[$p->getName()] >= $this->config->get("warnings-before-kick")){
						$p->close($this->config->get("kick-message"), $this->config->get("kick-message"));
					}
				}
				else{
					$e->setCancelled();
					$p->sendMessage($this->config->get("swear-block-message"));
					$this->talked[$p->getName()] = time();
					$this->warnings[$p->getName()] = $this->warnings[$p->getName()] + 1;
				}
			}
		}
	}
	
	public function onQuit(PlayerQuitEvent $e){
		$p = $e->getPlayer();
		if(isset($this->talked[$p->getName()])){
			unset($this->talked[$p->getName()]);
		}
		if(isset($this->warnings[$p->getName()])){
			unset($this->warnings[$p->getName()]);
		}
	}
}
