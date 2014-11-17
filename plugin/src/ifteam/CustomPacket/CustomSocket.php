<?php

namespace ifteam\CustomPacket;

use raklib\server\UDPServerSocket;
use pocketmine\Server;
use ifteam\CustomPacket\event\ReceiveJSONPacketEvent;
use ifteam\CustomPacket\event\SendJSONPacketEvent;
use ifteam\CustomPacket\event\ReceivePacketEvent;
use ifteam\CustomPacket\event\SendPacketEvent;

class CustomSocket extends \Thread {
	/** @var CustomPacket */
	public $stop = false;
	public $socket = null;
	/**
	 * @param $port
	 * @param $interface
	 */
	public function __construct($port) {
		$this->socket = new UDPServerSocket(Server::getInstance()->getLogger(), $port);
		$this->start ();
	}
	public function close() {
		$this->stop = true;
		$this->socket->close();
	}
	public function run() {
		while ( $this->stop !== true ) {
			$data = $ip = $port = null;
			if( $this->socket->readPacket ( $data, $ip, $port ) > 0)
				$this->receivePacket($data, $ip, $port);
		}
		unset ( $this->socket, $this->stop );
		exit ( 0 );
	}
    /**
     * ReceivePacket and callEvent ReceivePacketEvent
     * or ReceiveJSONPacketEvent (is_numeric check)
     *
     * @param int|string $data            
     * @param int $ip            
     * @param int $port            
     *
     */
    public function receivePacket($data, $ip, $port) {
        if(is_numeric($data)){
            $event = new ReceivePacketEvent($this, $data, $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
        }else{
            $event = new ReceiveJSONPacketEvent($this, JSON_Decode($data), $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
        }
    }
    /**
     * SendPacket and callEvent SendPacketEvent
     * or SendJSONPacketEvent (is_numeric check)
     *
     * @param int|string $packet
     * @param string $ip            
     * @param int $port            
     *
     */
    public static function sendPacket($packet, $ip, $port) {
        if(is_numeric($packet)){
            $event = new SendPacketEvent($this, $packet, $ip, $port);
            $this->getServer ()->getPluginManager ()->callEvent ($event);
            if(!$event->isCancelled()){
                $packet = $event->getPacket();
                $this->socket->writePacket($packet, $ip, $port);
            }
        }else{
            $event = new SendJSONPacketEvent($this, $packet, $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
            if(!$event->isCancelled()){
                $packet = JSON_Encode($event->getPacket());
                $this->socket->writePacket($packet, $ip, $port);
            }
        }
    }
}
?>
