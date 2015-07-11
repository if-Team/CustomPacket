<?php

namespace ifteam\CustomPacket;

use ifteam\CustomPacket\Info;
use ifteam\CustomPacket\DataPacket;

class InvalidPacket extends DataPacket{ //DO NOT CREATE THIS WITH ANY PURPOSE
	
	public function pid(){
		return Info::PKHEAD_INVALID;
	}
}