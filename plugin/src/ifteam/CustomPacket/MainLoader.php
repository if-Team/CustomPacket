<?

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class MainLoader extends PluginBase{
    
    private $interface;
    
    public function onEnable(){
        $this->interface = new SocketInterface(Server::getInstance());
    }
    
}