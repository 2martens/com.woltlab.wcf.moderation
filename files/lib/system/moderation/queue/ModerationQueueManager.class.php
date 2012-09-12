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
	 * Adds a report for specified content.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param	string		$message
	 * @param	array		$additionalData
	 */
	public function addReport($objectType, $objectID, $message, array $additionalData = array()) {
		if (!isset($this->report['objectNames'][$objectType])) {
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
		if (!isset($this->moderatedContent['objectNames'][$objectType])) {
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
