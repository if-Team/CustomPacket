<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;
use pocketmine\utils\Utils;

class DataPacket{ //Note: need to be abstract in future
	
	public $address, $port, $data;
	
	public function __construct($address, $port, $data){
		$this->address = $address;
		$this->port = $port;
		$this->data = $this->pid().$data;
	}
	
	public function pid(){
		return Info::PKHEAD_DATA;
	}
	
	public function printDump(){
		$logger = Server::getInstance()->getLogger();
		$logger->info("[CustomPacket] Start packet information dump...");
		$logger->info("");
		$logger->info("Source address: ". $this->address);
		$logger->info("Source port:	". $this->port);
		$logger->info("Packet length:  ". strlen($this->data) - 1);
		$logger->info("Packet header:  ". ord($this->pid()))
		$logger->info("");
		$logger->info("Printing hexdump");
		$logger->info(str_repeat("=", 73));
		$dump = Utils::hexdump(substr($this->data, 1));
		foreach(explode("\n", substr($dump, 0, strlen($dump) - 1)) as $line) $logger->info($line);
		$logger->info(str_repeat("=", 73));
		$logger->info("");
		$logger->info("[CustomPacket] End packet information dump...");
		
	}
}