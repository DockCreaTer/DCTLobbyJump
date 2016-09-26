<?php

namespace PPjump;

use pocketmine\block\PressurePlate;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;
use pocketmine\Server;
use pocketmine\utils\Config;

class pressurejump extends PluginBase implements Listener{

    public function onEnable(){
		    public function onEnable(){
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      if(!file_exists($this->getDataFolder())){
        $this->getLogger()->info("§b建立PressureJump資料夾中!");
        @mkdir($this->saveDataFolder());
	  }
    public function onDisable(){
        $this->getLogger()->info("§a壓力板彈跳(PresurreJump)已關閉");
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