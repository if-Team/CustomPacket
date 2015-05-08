<?php 

namespace ifteam\CustomPacket;

use pocketmine\scheduler\PluginTask;
class CustomPacketTask extends PluginTask {
	function __construct(MainLoader $owner) {
		parent::__construct ( $owner );
	}
	public function onRun($currentTick) {
		/**
		 * @var $owner MainLoader
		 */
		$owner = $this->getOwner ();
		$owner->update ();
	}
}

?>