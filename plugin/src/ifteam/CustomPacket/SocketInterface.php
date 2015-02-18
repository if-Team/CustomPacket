<?php

namespace ifteam\CustomPacket;

use pocketmine\Server;
use ifteam\CustomPacket\event\CustomPacketPreReceiveEvent;
use ifteam\CustomPacket\event\CustomPacketReceiveEvent;
use ifteam\CustomPacket\event\CustomPacketSendEvent;
use ifteam\CustomPacket\CPAPI;

class SocketInterface{
    
    private $internalThreaded;
    private $externalThreaded;
    private $server;
    private $socket;
    
    public function __construct(Server $server){
        $this->internalThreaded = new \Threaded();
        $this->externalThreaded = new \Threaded();
        $this->server = $server;
        $this->socket = new CustomSocket($this->internalThreaded, $this->externalThreaded, $this->server->getLogger(), 19131, $this->server->getIp() === "" ? "0.0.0.0" : $this->server->getIp());
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
            Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketPreReceiveEvent(clone $packet, $player));
            if(!$ev->isCancelled()) Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketReceiveEvent(clone $packet, $player));
            return true;
        }
        
        return false;
    }
    
    public function shutdown(){
        $this->pushInternalQueue([chr(Info::SIGNAL_SHUTDOWN)]);
    }
    
    public function sendPacket(DataPacket $packet){
        if(($player = CPAPI::matchPlayer($packet->address)) === null) return false;
        Server::getInstance()->getPluginManager()->callEvent($ev = new CustomPacketSendEvent($packet, $player));
        if(!$ev->isCancelled()) $this->pushInternalQueue([Info::PACKET_SEND, $packet]);
    }
    
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