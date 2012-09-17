<?php
namespace wcf\system\moderation\queue;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Moderation queue implementation for reports.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueReportManager extends AbstractModerationQueueManager {
	/**
	 * @see	wcf\system\moderation\queue\AbstractModerationQueueManager::$definitionName
	 */
	protected $definitionName = 'com.woltlab.wcf.moderation.report';
	
	/**
	 * Returns true, if given item was already reported.
	 *
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function isAlreadyReported($objectType, $objectID) {
		$objectTypeID = $this->getObjectTypeID($objectType);
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_moderation_queue
			WHERE	objectTypeID = ?
				AND objectID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
				$objectTypeID,
				$objectID
		));
		$row = $statement->fetchArray();
		
		return ($row['count'] == 0 ? false : true);
	}
	
	/**
	 * Returns the reported object.
	 *
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	wcf\data\IUserContent
	 */
	public function getReportedObject($objectType, $objectID) {
		return $this->getProcessor($objectType)->getReportedObject($objectID);
	}
	
	/**
	 * Adds a report for specified content.
	 *
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param	string		$message
	 * @param	array		$additionalData
	 */
	public function addReport($objectType, $objectID, $message, array $additionalData = array()) {
		if (!$this->isValid($objectType)) {
			throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.moderation.report'");
		}
		
		$additionalData['message'] = $message;
		$this->addEntry($this->report['objectNames'][$objectType], $objectID, $additionalData);
	}
}
