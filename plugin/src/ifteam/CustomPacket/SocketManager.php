<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;
use ifteam\CustomPacket\ModPEProtocol as Protocol;
use ifteam\CustomPacket\Event\ReceivePacketEvent;
use ifteam\CustomPacket\Packet\CustomPacket;

class SocketManager{
	/** @var SocketManager */
	protected $stop = false;
	protected $socket = null;
	protected $caller;
	/**
	 * @param $socket
	 * @param $caller
	 */
	public function __construct(CustomSocket $socket, MainLoader $caller) {
		$this->socket = $socket;
		$this->caller = $caller;
		$this->listen();
	}
	public function close() {
		$this->stop = true;
		$this->sendPacket(Protocol::SIGNAL_NOTHING, '127.0.0.1', 19131);
		$this->socket->close();
	}
	public function listen() {
		$this->caller->getServer()->getLogger()->debug('Listening for packets...');
		$data = $ip = $port ="UNNAMED BUFFER";
		// if (@socket_recvfrom ( $this->socket, $data, 128, 0, $ip, $port ) > 0) {
		if ($this->socket->recvPacket($data, $ip, $port, true) !== false) { // TESTCODE
			$data = "TEST"; // TESTCODE
			$ip = "192.168.1.1"; // TESTCODE
			//$port = 65333; // TESTCODE
			
			$this->caller->getServer()->getPluginManager()->callEvent(new ReceivePacketEvent(new CustomPacket($data), $ip, $port));
			
			// @TODO : http://stackoverflow.com/questions/9824714/shared-resources-between-php-children-threads
			// @TODO : http://php.net/manual/en/book.sem.php
			//$id = msg_get_queue ( 12340 );
			//msg_send ( $id, 8, "$data-$ip-$port", false, false, $err );
		} else {
			$this->caller->getServer()->getLogger()->debug("Couldn't get packets.");
			//var_dump($data, $ip, $port);
			echo 'errorcode: ' . socket_last_error() . ', errormsg: ' . socket_strerror(socket_last_error()).PHP_EOL;
			socket_clear_error();
		}
	}
} 
?>