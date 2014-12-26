<?

use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class MainLoader extends PluginBase{
    
    private $internalThreaded;
    private $externalThreaded;
    private $server;
    private $socket;
    
    public function onEnable(){
        $this->internalThreaded = new \Threaded();
        $this->externalThreaded = new \Threaded();
        $this->server = Server::getInstance();
        $this->socket = new SocketInterface($this->internalThreaded, $this->externalThreaded, $this->server->getLogger(), 19131);
    }
    
}