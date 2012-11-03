<?php
namespace wcf\system\event\listener;
use wcf\system\event\IEventListener;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;

/**
 * Resets computed assignments for moderation queues.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.event.listener
 * @category	Community Framework
 */
class ModerationQueueToUserListener implements IEventListener {
	/**
	 * @see	wcf\system\event\IEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// remove assignments
		$sql = "DELETE FROM	wcf".WCF_N."_moderation_queue_to_user
			WHERE		userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($eventObj->user->userID));
		
		// reset moderation count
		ModerationQueueManager::getInstance()->resetModerationCount($eventObj->user->userID);
	}
}
