<?

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\scheduler\CallbackTask;
use ifteam\CustomPacket\event\CustomPacketReceiveEvent;
use pocketmine\event\Listener; //Test code

class MainLoader extends PluginBase implements Listener{
    
    private $interface;
    
    public function onEnable(){
        $this->interface = new SocketInterface(Server::getInstance());
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask(array($this->interface, "process")), 1);
        $this->getServer()->getLogger()->info("[CustomPacket] Registered CustomSocket tick schedule.");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function test_onRecv(CustomPacketReceiveEvent $ev){
        $this->getServer()->getLogger()->notice("Sample event message");
        $ev->getPacket()->printDump();
        $pk = new DataPacket($ev->getPacket()->address, $ev->getPacket()->port, $ev->getPacket()->data." SENDING BACK!");
        $this->getServer()->getPluginManager()->getPlugin("CustomPacket")->sendPacket($pk);
    }
    
    public function sendPacket(DataPacket $packet){
        $this->interface->sendPacket($packet);
    }
    
}