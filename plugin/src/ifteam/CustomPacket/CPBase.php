<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;

class CPBase extends PluginBase {
    
    public final function cpapi_getVersion(){
        return ModPEProtocol::API_VERSION;
    }
    
    public final function cpapi_getProtocolVersion(){
        return ModPEProtocol::PROTOCOL_VERSION;
    }
}
?>
