<?php
namespace wcf\system\moderation\queue;
use wcf\system\exception\SystemException;

/**
 * Moderation queue implementation for moderated content.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueModeratedContentManager extends AbstractModerationQueueManager {
	/**
	 * @see	wcf\system\moderation\queue\AbstractModerationQueueManager::$definitionName
	 */
	protected $definitionName = 'com.woltlab.wcf.moderation.moderatedContent';
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueManager::getLink()
	 */
	public function getLink($queueID) {
		return LinkHandler::getInstance()->getLink('ModerationModeratedContent', array('id' => $queueID));
	}
	
	/**
	 * Adds an entry for moderated content.
	 *
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @param	array		$additionalData
	 */
	public function addModeratedContent($objectType, $objectID, array $additionalData = array()) {
		if (!$this->isValid($objectType)) {
			throw new SystemException("Object type '".$objectType."' is not valid for definition 'com.woltlab.wcf.moderation.moderatedContent'");
		}
		
		$this->addEntry(
			$this->getObjectTypeID($objectType),
			$objectID,
			$this->getProcessor($objectType)->getContainerID($objectID),
			$additionalData
		);
	}
}
