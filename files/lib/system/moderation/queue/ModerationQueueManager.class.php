<?php
namespace wcf\system\moderation\queue;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ModerationQueueAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\exception\SystemException;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Provides methods to manage moderated content and reports.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueManager extends SingletonFactory {
	/**
	 * list of object types for moderated content
	 * @var	array<array>
	 */
	protected $moderatedContent = array(
		'objectNames' => array(),
		'objects' => array()
	);
	
	/**
	 * list of object types for reports
	 * @var	array<array>
	 */
	protected $report = array(
		'objectNames' => array(),
		'objects' => array()
	);
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$moderatedContent = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.moderation.moderatedContent');
		foreach ($moderatedContent as $objectType) {
			$this->moderatedContent['objectNames'][$objectType->objectType] = $objectType->objectTypeID;
			$this->moderatedContent['objects'][$objectType->objectTypeID] = $objectType;
		}
		
		$report = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.moderation.report');
		foreach ($report as $objectType) {
			$this->report['objectNames'][$objectType->objectType] = $objectType->objectTypeID;
			$this->report['objects'][$objectType->objectTypeID] = $objectType;
		}
	}
	
	/**
	 * Returns true, if given item was already reported.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function isAlreadyReported($objectType, $objectID) {
		$objectTypeID = $this->getObjectTypeID(ModerationQueue::TYPE_REPORT, $objectType);
		
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
	 * Returns true, if given object type is valid. If $objectID is given,
	 * it will be validated against the object type's class.
	 * 
	 * @param	integer		$type
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function isValid($type, $objectType, $objectID = null) {
		$isValid = false;
		switch ($type) {
			case ModerationQueue::TYPE_MODERATED_CONTENT:
				if (isset($this->moderatedContent['objectNames'][$objectType])) {
					$isValid = true;
				}
			break;
			
			case ModerationQueue::TYPE_REPORT:
				if (isset($this->report['objectNames'][$objectType])) {
					$isValid = true;
				}
			break;
			
			default:
				throw new SystemException("Unknown type '".$type."' given");
			break;
		}
		
		if (!$isValid) {
			return false;
		}
		
		if ($objectID === null) {
			return true;
		}
		else {
			return $this->getClassObject($type, $objectType)->isValid($objectID);
		}
	}
	
	/**
	 * Returns the reported object.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	wcf\data\IUserContent
	 */
	public function getReportedObject($objectType, $objectID) {
		return $this->getClassObject(ModerationQueue::TYPE_REPORT, $objectType)->getReportedObject($objectID);
	}
	
	/**
	 * Returns the class object for given object type.
	 * 
	 * @param	integer		$type
	 * @param	string		$objectType
	 * @return	wcf\system\moderation\queue\IModerationQueueProvider
	 */
	protected function getClassObject($type, $objectType) {
		if ($type == ModerationQueue::TYPE_MODERATED_CONTENT) {
			return $this->moderatedContent['objects'][$this->moderatedContent['objectNames'][$objectType]]->getProcessor();
		}
		else {
			return $this->report['objects'][$this->report['objectNames'][$objectType]]->getProcessor();
		}
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
		if (!$this->isValid(ModerationQueue::TYPE_REPORT, $objectType)) {
			throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.moderation.report'");
		}
		
		$additionalData['message'] = $message;
		$this->addEntry($this->report['objectNames'][$objectType], $objectID, $additionalData);
	}
	
	/**
	 * Adds an entry for moderated content.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param	array		$additionalData
	 */
	public function addModeratedContent($objectType, $objectID, array $additionalData = array()) {
		if (!$this->isValid(ModerationQueue::TYPE_MODERATED_CONTENT, $objectType)) {
			throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.moderatedContent.report'");
		}
		
		$this->addEntry($this->moderatedContent['objectNames'][$objectType], $objectID, $additionalData);
	}
	
	/**
	 * Adds an entry to moderation queue.
	 * 
	 * @param	integer		$objectTypeID
	 * @param	integer		$objectID
	 * @param	array		$additionalData
	 */
	protected function addEntry($objectTypeID, $objectID, array $additionalData = array()) {
		$objectAction = new ModerationQueueAction(array(), 'create', array(
			'data' => array(
				'objectTypeID' => $objectTypeID,
				'objectID' => $objectID,
				'userID' => (WCF::getUser()->userID ? WCF::getUser()->userID : null),
				'time' => TIME_NOW,
				'additionalData' => serialize($additionalData)
			)
		));
		$objectAction->executeAction();
	}
	
	/**
	 * Returns object type id.
	 *
	 * @param	integer		$type
	 * @param	string		$objectType
	 * @return	integer
	 */
	public function getObjectTypeID($type, $objectType) {
		if (!$this->isValid($type, $objectType)) {
			throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.moderation.".($type == ModerationQueue::TYPE_MODERATED_CONTENT ? "moderatedContent" : "report")."'");
		}
	
		if ($type == ModerationQueue::TYPE_MODERATED_CONTENT) {
			return $this->moderatedContent['objectNames'][$objectType];
		}
		else {
			return $this->report['objectNames'][$objectType];
		}
	}
	
	/**
	 * Returns a list of object type ids for given type.
	 * 
	 * @param	integer		$type
	 */
	public function getObjectTypeIDs($type) {
		switch ($type) {
			case ModerationQueue::TYPE_MODERATED_CONTENT:
				return array_keys($this->moderatedContent['objects']);
			break;
			
			case ModerationQueue::TYPE_REPORT:
				return array_keys($this->report['objects']);
			break;
			
			default:
				throw new SystemException("Unknown type '".$type."' given");
			break;
		}
	}
}
