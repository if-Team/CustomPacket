<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use ifteam\CustomPacket\raknet\RakLibInterface;

class MainLoader extends PluginBase implements Listener {
	/** @var SocketInterface */
	private static $interface = null;

	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();

		/*self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131));

		$this->getServer()->getScheduler()->scheduleRepeatingTask(new CustomPacketTask($this), 1);
		$this->getLogger()->info("Registered CustomSocket tick schedule.");*/
		$this->getLogger()->info("Shutting down RakLib to apply CustomPacket patch");
		$i = $this->getServer()->getNetwork()->getInterfaces();
		end($i)->shutdown();
		$this->getServer()->getNetwork()->unregisterInterface(end($i));
		$this->getLogger()->info("Reigstering modded Raklib with CustomPacket features");
		$this->getServer()->getNetwork()->registerInterface(new RakLibInterface($this->getServer()));
	}

	public function onCommand(CommandSender $sender,Command $command, $label, Array $args){
		if(!$sender->isOp()){
			return false;
		}

		if(!isset($args[0]) or !is_numeric($args[0])){
			$sender->sendMessage("[CustomPacket] type : /custompacket <port>");
			return true;
		}

		$this->getConfig()->set("port", intval($args[0]));
		$this->getConfig()->save();

		self::$interface->shutdown();
		self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131));

		$sender->sendMessage("[CustomPacket] Settings have been applied successfully");
		return true;
	}

	public function update(){
		self::$interface->process();
	}

	/**
	 * @return SocketInterface
	 */
	public static function getInterface(){
		return self::$interface;
	}
}
