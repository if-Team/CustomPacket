<?

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CallbackTask;

class MainLoader extends PluginBase {
    
    private static $interface;
    
    
    public function onEnable(){
        self::$interface = new SocketInterface($this->getServer());
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array(self::$interface, "process")), 1);
        $this->getServer()->getLogger()->info("[CustomPacket] Registered CustomSocket tick schedule.");
    }
    
    public static function getInterface(){
        return self::$interface;
    }
    
}