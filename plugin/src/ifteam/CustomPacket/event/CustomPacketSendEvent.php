<?

namespace ifteam\CustomPacket\event;

use pocketmine\event\Cancellable;

class CustomPacketSendEvent extends CustomPacketEvent implements Cancellable{
    
    public static $handlerList = null;
    public static $eventPool = [];
    public static $nextEvent = 0;
    
}