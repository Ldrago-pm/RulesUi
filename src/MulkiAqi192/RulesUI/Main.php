<?php

namespace MulkiAqi192\RulesUI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(is_null($this->getServer()->getPluginManager()->getPlugin("FormAPI"))){
			$this->getLogger()->info("§cYou need FormAPI to use RulesUI Plugin! disabling plugin...");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		} else {
			$this->getLogger()->info("§aFormAPI Found, enabling plugin. Support me on PayPal if you like this plugin! §bhttps://paypal.me/jedimasters");
		}
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");
	}

	public $onJoin = [];

	public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
		
		switch($cmd->getName()){
			case "rules":
			 if($sender instanceof Player){
			 	$this->accrules($sender);
			 }
		}
	return true;
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$this->rules($player);
	}

	public function rules($player){
		$form = new SimpleForm(function (Player $player, int $data = null){
			if($data === null){
				$this->rules($player);
				return true;
			}
			switch($result){
				case 0:
					$player->sendMessage($this->getConfig()->get("rules_accepted_msg"));
				break;

				case 1:
					$player->kick($this->getConfig()->get("kick_msg"));
				break;
			}
		});
		$form->setTitle($this->getConfig()->get("title"));
		$form->setContent($this->getConfig()->get("rules"));
		$form->addButton($this->getConfig()->get("button1"));
		$form->addButton($this->getConfig()->get("button2"));
		$form->sendToPlayer($player);
		return $form;
	}

	public function accrules($player){
		$form = new SimpleForm(function (Player $player, int $data = null){
			if($data === null){
				return true;
			}
		});
		$form->setTitle($this->getConfig()->get("title"));
		$form->setContent($this->getConfig()->get("already_accepted"));
		$form->addButton($this->getConfig()->get("button3"));
		$form->sendToPlayer($player);
		return $form;
	}

}
