<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;
use ifteam\CustomPacket\ModPEProtocol as Protocol;
use ifteam\CustomPacket\Event\ReceivePacketEvent;

class CustomSocket extends \Thread {
	/** @var CustomPacket */
	protected $stop = false;
	protected $socket = null;
	protected $plugin;
	/**
	 * @param $port
	 * @param $interface
	 */
	public function __construct(MainLoader $plugin, $interface, $port) {
		$this->plugin = $plugin;
		$this->socket = socket_create ( AF_INET, SOCK_DGRAM, SOL_UDP );
		if (@socket_bind ( $this->socket, $interface, $port ) === true) {
			socket_set_option ( $this->socket, SOL_SOCKET, SO_REUSEADDR, 0 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_RCVBUF, 1024 * 1024 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_SNDBUF, 1024 * 1024 * 8 );
		} else {
			$plugin->getLogger ()->critical ( "FAILED TO BIND TO " . $interface . ":" . $port . "!", true, true, 0 );
			$plugin->getLogger ()->critical ("Perhaps a server is already running on that port?", \true, \true, 0);
			$plugin->getServer ()->shutdown ();
		}
		socket_set_nonblock ( $this->socket );
		$this->start ();
	}
	public function close() {
		$this->stop = true;
		$this->sendPacket(Protocol::SIGNAL_NOTHING, '127.0.0.1', 19131)
		socket_close ( $this->socket );
	}
	public function run() {
		while ($this->stop){
			$data = $ip = $port = null;
			// if (@socket_recvfrom ( $this->socket, $data, 128, 0, $ip, $port ) > 0) {
			if ($this->recvPacket($data, $ip, $port) == 1) { // TESTCODE
				$data = "TEST"; // TESTCODE
				$ip = "192.168.1.1"; // TESTCODE
				//$port = 65333; // TESTCODE
				
				$this->plugin->getServer()->getPluginManager()->callEvent(new ReceivePacketEvent(new Packet($data), $ip, $port));
				
				// @TODO : http://stackoverflow.com/questions/9824714/shared-resources-between-php-children-threads
				// @TODO : http://php.net/manual/en/book.sem.php
				//$id = msg_get_queue ( 12340 );
				//msg_send ( $id, 8, "$data-$ip-$port", false, false, $err );
				break;
			}
		}
		unset ( $this->socket, $this->stop );
		exit ( 0 );
	}
	public function sendPacket($buffer, $address, $port){
		if(!filter_var($address, FILTER_VALIDATE_IP)) return false;
		return @socket_sendto($this->socket, $buffer, strlen($buffer), 0, $address, $port);
	}
	public function recvPacket(&$buffer, &$address, &$port){
		return @socket_recvfrom($this->socket, $buffer, , 1024*1024, 0, $address, $port)
	}
}
?>
