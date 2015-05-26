<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class MainLoader extends PluginBase implements Listener {
    /** @var SocketInterface */
    private static $interface = null;

    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	$this->saveDefaultConfig();
        $secure = $this->getConfig()->get("secureKey", 'none');
        if($secure !== 'none' and ($valid = strlen($secure) === 16)){
            $this->getLogger()->notice("[CustomPacket] CustomPacket is running in secure mode.");
        } else {
            if(isset($valid) and !$valid){
                $this->getLogger()->critical("[CustomPacket] Key must be 16 alphabet/numbers.");
                throw new \Exception("Key must be 16 alphabet/numbers, ".$secure." given");
            } else {
                $this->getLogger()->info("[CustomPacket] CustomPacket is running in normal mode, secure mode disabled");
                $secure = false;
            }
        }

        self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131), $secure);

        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CustomPacketTask($this), 1);
        $this->getLogger()->info("Registered CustomSocket tick schedule.");
    }

    public function onCommand(CommandSender $sender,Command $command, $label, Array $args){
    	if(!$sender->isOp()){
            return false;
        }

    	if(!isset($args[0]) or !is_numeric($args[0])){
    		$sender->sendMessage("Usage: /custompacket <port>");
    		return true;
    	}

    	$this->getConfig()->set("port", intval($args[0]));
    	$this->getConfig()->save();

    	self::$interface->shutdown();
    	self::$interface = new SocketInterface($this->getServer(), $this->getConfig()->get("port", 19131));

        $sender->sendMessage("[CustomPacket] Settings have been applied successfully");
    	return true;
    }

    public function update(){
    	self::$interface->process();
    }

    /**
     * @return SocketInterface
     */
    public static function getInterface(){
        return self::$interface;
    }
}