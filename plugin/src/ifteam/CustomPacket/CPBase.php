<?php

namespace ifteam\CustomPacket;

use pocketmine\plugin\PluginBase;

class CPBase extends PluginBase {
	const API_VERSION = 0.1;
	private function cpapi_getVersion(){
		return self::API_VERSION;
	}
}
?>
