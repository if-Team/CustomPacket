<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\Server;
use ifteam\CustomPacket\event\ReceivePacketEvent;
use ifteam\CustomPacket\event\SendPacketEvent;
use ifteam\CustomPacket\Packet\CustomPacket;
use pocketmine\scheduler\CallbackTask;

class MainLoader extends PluginBase implements Listener {

    /** @var CustomSocket */
    private $socket;
    private $option;
    private $socketManager;
	
	public $stream = null;
    
    private static $instance = null;
    
    public function onEnable() {
    	if(self::$instance == null)
    		self::$instance = $this;
        $defaultOption = [
            "interface" => "0.0.0.0",
            "port" => 19131
        ];
        $this->option = (new Config($this->getDataFolder() . "SocketOption.yml", Config::YAML, $defaultOption))->getAll();
        $this->socket = new CustomSocket($this, $this->option["interface"], $this->option["port"]);
		//$this->socketManager = new SocketManager($this->socket, $this);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    public function onDisable(){
        $this->socket->close();
    }
    
    /** @return CustomPacket */
    /*
	public static function getInstance(){
    	return self::$instance;
    }
	*/
   
    /** @return int */
    public function getDefaultPort() {
        return $this->option["port"];
    }

	public function callEvent($type, array $data){
		switch($type){
			case 'recv':
				$this->getServer()->getPluginManager()->callEvent(new ReceivePacketEvent(new CustomPacket($data['rawstring']), $data['ip'], $data['port']));
				break;
			case 'send':
				$this->getServer()->getPluginManager()->callEvent(new SendPacketEvent($data['pk'], $data['ip'], $data['port']));
				break;
		}
		ob_start();
		var_dump($data);
		$dump = ob_get_clean();
		$this->getServer()->getLogger()->debug("[CustomPacket Event debug message]\n".$dump);
	}
}
?>
