<?php

namespace PressureJump;

use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;
use pocketmine\Server;
use pocketmine\utils\Config;

class pressurejump extends PluginBase implements Listener{

    public function onEnable() {
        System.out.println("[PressurePush] Enabled");
        Bukkit.getPluginManager()->registerEvents(this, this);
        getConfig()->options()->copyDefaults(true);
        @mkdir->saveDefaultConfig();
    }

   public function onDisable() {
        @mkdir->saveDefaultConfig();
        System.out.println("[PressurePush] Disabled");
    }

	
    public function onCommand(CommandSender $sender, Command $command, $alias, array $args){
          if(!isset($args[0])){
              return false;
          }
          if(!$sender->isOp()){
            $this->getServer()->broadcastMessage($sender->getName(). "使用PJ指令");
            $sender->sendMessage("§4你不是OP");
            return true;
          }else{
            $sender->sendMessage("§6玩家");
          }


    public function 
