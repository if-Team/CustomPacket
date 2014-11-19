<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;

class CustomSocket extends \Thread {
	/** @var CustomPacket */
	public $stop = false;
	public $socket = null;
	/**
	 * @param $port
	 * @param $interface
	 */
	public function __construct(MainLoader $plugin, $interface, $port) {
		$this->plugin = $plugin;
		$this->socket = socket_create ( AF_INET, SOCK_DGRAM, SOL_UDP );
		if (@socket_bind ( $this->socket, $interface, $port ) === true) {
			socket_set_option ( $this->socket, SOL_SOCKET, SO_REUSEADDR, 0 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_RCVBUF, 1500 );
			@socket_set_option ( $this->socket, SOL_SOCKET, SO_SNDBUF, 1024 * 1024 * 2 );
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
		socket_close ( $this->socket );
	}
	public function run() {
		while ( $this->stop !== true ) {
			$data = $ip = $port = null;
			// if (@socket_recvfrom ( $this->socket, $data, 128, 0, $ip, $port ) > 0) {
			if (@socket_recvfrom ( $this->socket, $data, 128, 0, $ip, $port ) == 0) { // TESTCODE
				$data = "14141"; // TESTCODE
				$ip = "192.168.1.1"; // TESTCODE
				$port = 65333; // TESTCODE
					               
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
}
?>
