<?

namespace ifteam\CustomPacket\event;

use pocketmine\event\Cancellable;

class CustomPacketPreReceiveEvent extends CustomPacketEvent implements Cancellable{
    
    public static $handlerList = null;
    public static $eventPool = [];
    public static $nextEvent = 0;
    
}