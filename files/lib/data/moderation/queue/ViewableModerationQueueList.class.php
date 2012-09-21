<?php
namespace wcf\data\moderation\queue;
use wcf\system\moderation\queue\ModerationQueueManager;

/**
 * Represents a viewable list of moderation queue entries.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	data.moderation.queue
 * @category 	Community Framework
 */
class ViewableModerationQueueList extends ModerationQueueList {
	/**
	 * @see	wcf\data\DatabaseObjectList::__construct()
	 */
	public function __construct() {
		parent::__construct();
		
		$this->sqlSelects = "assigned_user.username AS assignedUsername, reporting_user.username AS reportingUsername";
		$this->sqlJoins = "LEFT JOIN wcf".WCF_N."_user assigned_user ON (assigned_user.userID = moderation_queue.assignedUserID)";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user reporting_user ON (reporting_user.userID = moderation_queue.userID)";
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		parent::readObjects();
		
		if (!empty($this->objects)) {
			$objects = array();
			foreach ($this->objects as &$object) {
				$object = new ViewableModerationQueue($object);
				
				if (!isset($objects[$object->objectTypeID])) {
					$objects[$object->objectTypeID] = array();
				}
				
				$objects[$object->objectTypeID][] = $object;
			}
			unset($object);
			
			foreach ($objects as $objectTypeID => $queueItems) {
				ModerationQueueManager::getInstance()->populate($objectTypeID, $queueItems);
			}
		}
	}
}
