<?php
namespace wcf\data\moderation\queue;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Executes moderation queue-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	data.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wcf\data\moderation\queue\ModerationQueueEditor';
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::update()
	 */
	public function update() {
		if (!isset($this->parameters['data']['lastChangeTime'])) {
			$this->parameters['data']['lastChangeTime'] = TIME_NOW;
		}
		
		parent::update();
	}
	
	/**
	 * Marks a list of objects as done.
	 */
	public function markAsDone() {
		if (empty($this->objects)) {
			$this->readObjects();
		}
		
		$queueIDs = array();
		foreach ($this->objects as $queue) {
			$queueIDs[] = $queue->queueID;
		}
		
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("queueID IN (?)", array($queueIDs));
		
		$sql = "UPDATE	wcf".WCF_N."_moderation_queue
			SET	status = ".ModerationQueue::STATUS_DONE."
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		// reset number of active moderation queue items
		ModerationQueueManager::getInstance()->resetModerationCount();
	}
}
