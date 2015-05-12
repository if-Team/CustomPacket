<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;
use ifteam\CustomPacket\event\CustomPacketPreReceiveEvent;
use ifteam\CustomPacket\event\CustomPacketReceiveEvent;
use ifteam\CustomPacket\event\CustomPacketSendEvent;

class SocketInterface{
    
    private $internalThreaded;
    private $externalThreaded;
    private $server;
    private $socket;
    
    const CACHE_VALID_TIME_LIMIT = 1800;
    const BLOCK_TIME_SECONDS = 600;
    
    public function __construct(Server $server, $port){
        $this->internalThreaded = new \Threaded();
        $this->externalThreaded = new \Threaded();
        $this->server = $server;
        $this->socket = new CustomSocket($this->internalThreaded, $this->externalThreaded, $this->server->getLogger(), $port, $this->server->getIp() === "" ? "0.0.0.0" : $this->server->getIp());
    }
    
    public function process(){
        $work = false;
        $this->pushInternalQueue([chr(Info::SIGNAL_TICK)]);
        if($this->handlePacket()){
            $work = true;
            while($this->handlePacket());
        }
        return $work; //For future use. Not now.
    }
    
    public function handlePacket(){
    	if(($packet = $this->readMainQueue()) instanceof DataPacket){
    		Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketPreReceiveEvent(clone $packet));
    		if(!$ev->isCancelled()) Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketReceiveEvent(clone $packet));
    		return true;
    	}
    	return false;
    }
    
    public function shutdown(){
        $this->pushInternalQueue([chr(Info::SIGNAL_SHUTDOWN)]);
    }
    
    public function sendPacket(DataPacket $packet){
        Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketSendEvent($packet));
        if(!$ev->isCancelled()) $this->pushInternalQueue([chr(Info::PACKET_SEND), $packet]);
    }
    
    public function blockAddress($address, $seconds){
        $this->pushInternalQueue([chr(Info::SIGNAL_BLOCK), [$address, time() + $seconds]]);
    }
    
    public function unblockAddress($address){
        $this->pushInternalQueue([chr(Info::SIGNAL_UNBLOCK), $address]);
    }
    
    /**
     * @deprecated
     */
     
    public function pushMainQueue(DataPacket $packet){
        $this->exteranlThreaded[] = $packet;
    }
    
    public function readMainQueue(){
        return $this->externalThreaded->shift();
    }
    
    public function pushInternalQueue(array $buffer){
        $this->internalThreaded[] = $buffer;
    }
    
    public function readInternalQueue(){
        return $this->internalThreaded->shift();
    }
    
}
