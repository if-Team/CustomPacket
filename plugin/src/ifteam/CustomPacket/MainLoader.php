<?

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\scheduler\CallbackTask;

class MainLoader extends PluginBase{
    
    private $interface;
    
    public function onEnable(){
        $this->interface = new SocketInterface(Server::getInstance());
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this->interface, "process")), 1);
        $this->getServer()->getLogger()->info("[CustomPacket] Registered CustomSocket tick schedule.");
    }
    
}