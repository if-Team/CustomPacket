<?php

namespace ifteam\CustomPacket\event;


class CustomPacketReceiveEvent extends CustomPacketEvent{
	
	public static $handlerList = null;
	public static $eventPool = [];
	public static $nextEvent = 0;
	
}