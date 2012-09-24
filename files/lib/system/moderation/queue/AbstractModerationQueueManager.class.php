<?php
namespace wcf\system\moderation\queue;
use wcf\data\moderation\queue\ModerationQueueList;

use wcf\data\moderation\queue\ModerationQueueAction;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Default implementation for moderation queue managers.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
abstract class AbstractModerationQueueManager extends SingletonFactory implements IModerationQueueManager {
	/**
	 * definition name
	 * @var	string
	 */
	protected $definitionName = '';
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::assignQueues()
	 */
	public function assignQueues($objectTypeID, array $queues) {
		ModerationQueueManager::getInstance()->getProcessor($this->definitionName, null, $objectTypeID)->assignQueues($queues);
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::isValid()
	 */
	public function isValid($objectType, $objectID = null) {
		return ModerationQueueManager::getInstance()->isValid($this->definitionName, $objectType);
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::getObjectTypeID()
	 */
	public function getObjectTypeID($objectType) {
		return ModerationQueueManager::getInstance()->getObjectTypeID($this->definitionName, $objectType);
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::getProcessor()
	 */
	public function getProcessor($objectType, $objectTypeID = null) {
		return ModerationQueueManager::getInstance()->getProcessor($this->definitionName, $objectType, $objectTypeID);
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::populate()
	 */
	public function populate($objectTypeID, array $objects) {
		ModerationQueueManager::getInstance()->getProcessor($this->definitionName, null, $objectTypeID)->populate($objects);
	}
	
	/**
	 * Adds an entry to moderation queue.
	 *
	 * @param	integer		$objectTypeID
	 * @param	integer		$objectID
	 * @param	integer		$containerID
	 * @param	array		$additionalData
	 */
	protected function addEntry($objectTypeID, $objectID, $containerID = 0, array $additionalData = array()) {
		$objectAction = new ModerationQueueAction(array(), 'create', array(
			'data' => array(
				'objectTypeID' => $objectTypeID,
				'objectID' => $objectID,
				'containerID' => $containerID,
				'userID' => (WCF::getUser()->userID ? WCF::getUser()->userID : null),
				'time' => TIME_NOW,
				'additionalData' => serialize($additionalData)
			)
		));
		$objectAction->executeAction();
	}
	
	/**
	 * Removes an entry from moderation queue.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	integer		$objectID
	 */
	protected function removeEntry($objectTypeID, $objectID) {
		$this->removeEntries($objectTypeID, array($objectID));
	}
	
	/**
	 * Removes a list of entries from moderation queue.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	array<integer>	$objectIDs
	 */
	protected function removeEntries($objectTypeID, $objectIDs) {
		$queueList = new ModerationQueueList();
		$queueList->getConditionBuilder()->add("moderation_queue.objectTypeID = ?", array($objectTypeID));
		$queueList->getConditionBuilder()->add("moderation_queue.objectID IN (?)", array($objectIDs));
		$queueList->sqlLimit = 0;
		$queueList->readObjects();
		
		if (count($queueList)) {
			$objectAction = new ModerationQueueAction($queueList->getObjects(), 'delete');
			$objectAction->executeAction();
		}
	}
}
