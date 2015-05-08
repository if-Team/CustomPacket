<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CallbackTask;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class MainLoader extends PluginBase implements Listener {
    private static $interface;
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	$this->saveDefaultConfig();
        self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CustomPacketTask($this), 1);
        $this->getServer()->getLogger()->info("[CustomPacket] Registered CustomSocket tick schedule.");
    }
    public function onCommand(CommandSender $sender,Command $command, $label, Array $args){
    	if(! $sender->isOp()) return false;
    	if(! isset($args[0]) or ! is_numeric($args[0])){
    		$sender->sendMessage("[CustomPacket] type : /custompacket <port>");
    		return true;
    	}
    	$this->getConfig()->set("port", $args[0]);
    	$this->getConfig()->save();
    	self::$interface->shutdown();
    	self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131));
    	$sender->sendMessage("[CustomPacket] Settings have been applied successfully");
    	return true;
    }
    public function update(){
    	self::$interface->process();
    }
    public static function getInterface(){
        return self::$interface;
    }
}