<?php

namespace ifteam\CustomPacket_EX;

use pocketmine\event\Listener;
use ifteam\CustomPacket\CPBase;
use ifteam\CustomPacket\MainLoader;
use ifteam\CustomPacket\event\ReceiveJSONPacketEvent;

class CustomPacket_EX extends CPBase implements Listener {
	private $custompacket;
	public function onEnable() {
		if ($this->checkCustomPacket ()) {
			$this->custompacket = MainLoader::getInstance ();
			$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
			$this->getLogger()->notice("Loading CustomPacket supported plugin version ". $this->cpapi_getVersion());
		} else {
			$this->getLogger ()->critical ( "CustomPacket Plugin Doesn't exist!" );
			$this->getLogger ()->critical ( "Plug-in Will be Turned Disable." );
		}
	}
	public function checkCustomPacket() {
		if (class_exists ( 'ifteam\\CustomPacket\\MainLoader' ))
			return true;
		return false;
	}
	public function onPacket(ReceiveJSONPacketEvent $event) {
		echo "Test log".PHP_EOL;
		echo "Message: $event->getPacket()".PHP_EOL;
		echo "( Address: $event->getIp() 포트: $event->getPort() )".PHP_EOL;
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			if ($player->getAddress () == $event->getIp () and $player->isOp ()){
				//$this->custompacket->sendPacket ( "WELCOME!", $event->getIp (), $event->getPort () );
			}
		}
	}
}

?>