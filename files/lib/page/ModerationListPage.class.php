<?php
namespace wcf\page;
use wcf\system\exception\IllegalLinkException;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

/**
 * List of moderation queue entries.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	page
 * @category 	Community Framework
 */
class ModerationListPage extends SortablePage {
	/**
	 * list of available definition ids
	 * @var	array<integer>
	 */
	public $availableDefinitionIDs = array();
	
	/**
	 * list of definition ids
	 * @var	array<integer>
	 */
	public $definitionIDs = array();
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$listClassName
	 */
	public $listClassName = 'wcf\data\moderation\queue\ModerationQueueList';
	
	/**
	 * @see	wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('mod.general.canUseModeration');
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->availableDefinitionIDs = ModerationQueueManager::getInstance()->getDefinitionIDs();
		if (isset($_REQUEST['definitionIDs']) && is_array($_REQUEST['definitionIDs'])) {
			$this->definitionIDs = ArrayUtil::toIntegerArray($_REQUEST['definitionIDs']);
			foreach ($this->definitionIDs as $definitionID) {
				if (!in_array($definitionID, $this->availableDefinitionIDs)) {
					throw new IllegalLinkException();
				}
			}
		}
	}
	
	/**
	 * @see	wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		// filter by object type id
		$objectTypeIDs = ModerationQueueManager::getInstance()->getObjectTypeIDs( (empty($this->definitionIDs) ? $this->availableDefinitionIDs : $this->definitionIDs) );
		$this->objectList->getConditionBuilder()->add("moderation_queue.objectTypeID IN (?)", array($objectTypeIDs));
		
		// filter by affected user
		/*
		$this->objectList->sqlJoins = ", wcf".WCF_N."_moderation_queue_to_user moderation_queue_to_user";
		$this->objectList->getConditionBuilder()->add("moderation_queue_to_user.queueID = moderation_queue.queueID");
		$this->objectList->getConditionBuilder()->add("moderation_queue_to_user.userID = ?", array(WCF::getUser()->userID));
		*/
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'definitionIDs' => $this->definitionIDs
		));
	}
}
