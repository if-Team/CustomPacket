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
    private static $ipCache;
    
    const CACHE_VALID_TIME_LIMIT = 1800;
    const BLOCK_TIME_SECONDS = 600;
    
    public function __construct(Server $server){
        self::$ipCache = [];
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
            if(!isset(self::$ipCache[$packet->address])){
                $player = CPAPI::matchPlayer($packet->address);
            } elseif(self::$ipCache[$packet->address]['expirirationTime'] <= time()){
                unset(self::$ipCache[$packet->address]); //Expired!
                $player = CPAPI::matchPlayer($packet->address);
            } else {
                $player = $this->server->getPlayerExact(self::$ipCache[$packet->address]['username']);
            }
            if($player === null){
                $this->server->getLogger()->warning("[CustomPacket] Invalid packet from unconnected client $packet->address");
                $packet->printDump();
                /*
                $this->server->getLogger()->notice("[CustomPacket] Blocking address $packet->address for ".self::BLOCK_TIME_SECONDS." seconds");
                CPAPI::blockAddress($packet->address, (int) self::BLOCK_TIME_SECONDS);
                */
                return true;
            }
            self::$ipCache[$packet->address] = ['username' => $player, 'expirirationTime' => (int) (time() + self::CACHE_VALID_TIME_LIMIT)];
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