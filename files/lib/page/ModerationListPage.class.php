<?php
namespace wcf\page;
use wcf\system\exception\IllegalLinkException;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * List of moderation queue entries.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	page
 * @category 	Community Framework
 */
class ModerationListPage extends SortablePage {
	/**
	 * list of available definitions
	 * @var	array<string>
	 */
	public $availableDefinitions = array();
	
	/**
	 * @see	wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = '';
	
	/**
	 * definiton id for filtering
	 * @var	integer
	 */
	public $definitionID = 0;
	
	/**
	 * @see	wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('mod.general.canUseModeration');
	
	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wcf\data\moderation\queue\ViewableModerationQueueList';
	
	/**
	 * @see	wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array('assignedUsername', 'id', 'lastChangeTime', 'reportingUsername', 'time');
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		$this->availableDefinitions = ModerationQueueManager::getInstance()->getDefinitions();
		if (isset($_REQUEST['definitionID'])) {
			$this->definitionID = intval($_REQUEST['definitionID']);
			if ($this->definitionID && !isset($this->availableDefinitions[$this->definitionID])) {
				throw new IllegalLinkException();
			}
		}
	}
	
	/**
	 * @see	wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		// filter by object type id
		$objectTypeIDs = ModerationQueueManager::getInstance()->getObjectTypeIDs( ($this->definitionID ? array($this->definitionID) : array_keys($this->availableDefinitions)) );
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
			'availableDefinitions' => $this->availableDefinitions,
			'definitionID' => $this->definitionID
		));
	}
}
