<?php

namespace ifteam\CustomPacket;

use pocketmine\Thread;
use pocketmine\Server;
use ifteam\CustomPacket\Event\ReceivePacketEvent;

class CustomSocket extends Thread{
	
	protected $caller, $interface, $port, $socket;
	
	public function __construct($caller, $interface = '0.0.0.0', $port = 19131){
		global $plugin;
		$plugin = $caller;
		$this->interface = filter_var($interface, FILTER_VALIDATE_IP)? $interface : '0.0.0.0';
		$this->port = $port;
		$this->start(PTHREADS_INHERIT_ALL | PTHREADS_ALLOW_GLOBALS);
	}
	
	public function sendPacket($buffer, $address, $port, $scream = false){
		if(!filter_var($address, FILTER_VALIDATE_IP)) return false;
		return $scream ? socket_sendto($this->socket, $buffer, strlen($buffer), 0, $address, $port) : 
									@socket_sendto($this->socket, $buffer, strlen($buffer), 0, $address, $port);
	}
	
	public function recvPacket(&$buffer, &$address, &$port, $scream = false){
		return $scream ? socket_recvfrom($this->socket, $buffer, socket_get_option($this->socket, SOL_SOCKET, SO_RCVBUF), 0, $address, $port) : 
									@socket_recvfrom($this->socket, $buffer, @socket_get_option($this->socket, SOL_SOCKET, SO_RCVBUF), 0, $address, $port);
	}
	
	public function close(){
		socket_close($this->socket);
	}
	
	public function run(){
		global $plugin;
		$this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		if (socket_bind ( $this->socket, $this->interface, $this->port ) === true) {
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_REUSEADDR, 0 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_RCVBUF, 1024 * 1024 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_SNDBUF, 1024 * 1024 * 8 );
		} else {
			$plugin->getServer()->getLogger()->critical ("*** FAILED TO BIND TO " . $this->interface . ":" . $this->port . "!", true, true, 0 );
			$plugin->getServer()->getLogger()->critical ("*** Perhaps a server is already running on that port?", \true, \true, 0);
		}
		socket_set_nonblock ( $this->socket );
		$plugin->getServer()->getLogger()->info("CustomSocket: Done loading. Enthering the loop...");
		while(1){
			$buffer = $address = $port = NULL;
			if($this->recvPacket($buffer, $address, $port) !== false){
				$plugin->getServer()->getLogger()->debug('GOTCHA!! from: '.$address.':'.$port.', data: '.$buffer);
				$plugin->callEvent('recv', array('rawstring' => $buffer, 'ip' => $address, 'port' => $port));
				
				$message = 'You sent "'. $buffer .'" to me! WOW!';
				$plugin->getServer()->getLogger()->debug('Sending back to: '.$address.':'.$port.', data: '.$message.$buffer);
				$this->sendPacket($message, $address, $port, true); //Test code. should be remove in official release.
			}
		}
	}
	
}
?>
