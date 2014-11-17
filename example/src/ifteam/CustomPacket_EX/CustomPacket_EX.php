<?php

namespace ifteam\CustomPacket_EX;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use ifteam\CustomPacket\CustomPacket;
use ifteam\CustomPacket\event\ReceiveJSONPacketEvent;

class CustomPacket_EX extends PluginBase implements Listener {
	private $custompacket;
	public function onEnable() {
		if ($this->checkCustomPacket ()) {
			$this->custompacket = CustomPacket::getInstance ();
			$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		} else {
			$this->getLogger ()->critical ( "CustomPacket 플러그인이 없습니다!" );
			$this->getLogger ()->critical ( "플러그인이 비활성화 처리됩니다." );
		}
	}
	public function checkCustomPacket() {
		if (class_exists ( 'ifteam\\CustomPacket\\CustomPacket' ))
			return true;
		return false;
	}
	public function onPacket(ReceiveJSONPacketEvent $event) {
		echo "AA";
		echo "메시지: $event->getPacket()";
		echo "( 아이피: $event->getIp() 포트: $event->getPort() )";
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			if ($player->getAddress () == $event->getIp () and $player->isOp ())
				$this->custompacket->sendPacket ( "WELCOME!", $event->getIp (), $event->getPort () );
		}
	}
}

?>