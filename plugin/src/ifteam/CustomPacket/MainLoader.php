<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\Server;
use ifteam\CustomPacket\event\ReceivePacketEvent;
use ifteam\CustomPacket\event\SendPacketEvent;
use pocketmine\scheduler\CallbackTask;

class MainLoader extends PluginBase implements Listener {

    /** @var CustomSocket */
    private $socket;
    private $option;
    
	public $stream = null;
    
    private static $instance = null;
    
    public function onEnable() {
    	if(self::$instance == null)
    		self::$instance = $this;
        $option = [
            "interface" => "0.0.0.0",
            "port" => 19131
        ];
        $this->option = (new Config($this->getDataFolder() . "SocketOption.yml", Config::YAML, $option))->getAll();
        $this->socket = new CustomSocket($this->getInstance(), $this->option["interface"], $this->option["port"]);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onDisable(){
        $this->socket->close();
        usleep(50000);
        $this->socket->kill();
    }
    
    /** @return CustomPacket */
    public static function getInstance(){
    	return self::$instance;
    }
   
    /** @return int */
    public function getDefaultPort() {
        return $this->option["port"];
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
            $event = new ReceivePacketEvent($data, $ip, $port);
            $this->getServer()->getPluginManager()->callEvent($event);
        }else{
            $event = new ReceiveJSONPacketEvent(JSON_Decode($data), $ip, $port);
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
            $event = new SendPacketEvent($packet, $ip, $port);
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
