<?php
namespace wcf\form;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ModerationQueueAction;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\moderation\queue\ModerationQueueReportManager;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the moderation report form.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	form
 * @category 	Community Framework
 */
class ModerationReportForm extends AbstractForm {
	/**
	 * assigned user id
	 * @var	integer
	 */
	public $assignedUserID = 0;
	
	/**
	 * comment
	 * @var	string
	 */
	public $comment = '';
	
	/**
	 * @see	wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 * moderation queue object
	 * @var	wcf\data\moderation\queue\ViewableModerationQueue
	 */
	public $queue = null;
	
	/**
	 * queue id
	 * @var	integer
	 */
	public $queueID = 0;
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->queueID = intval($_REQUEST['id']);
		$this->queue = ViewableModerationQueue::getViewableModerationQueue($this->queueID);
		if (!$this->queue === null) {
			throw new IllegalLinkException();
		}
		
		if (!$this->queue->canEdit()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['comment'])) $this->comment = StringUtil::trim($_POST['comment']);
		
		// verify assigned user id
		if (isset($_POST['assignedUserID'])) {
			$this->assignedUserID = intval($_POST['assignedUserID']);
			if ($this->assignedUserID) {
				if ($this->assignedUserID != WCF::getUser()->userID && $this->assignedUserID != $this->queue->assignedUserID) {
					// user id is either faked or changed during viewing, use database value instead
					$this->assignedUserID = $this->queue->assignedUserID;
				}
			}
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$this->assignedUserID = $this->queue->assignedUserID;
			$this->comment = $this->queue->comment;
		}
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'assignedUserID' => $this->assignedUserID,
			'comment' => $this->comment,
			'queue' => $this->queue,
			'reportedContent' => ModerationQueueReportManager::getInstance()->getReportedContent($this->queue)
		));
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$data = array(
			'assignedUserID' => ($this->assignedUserID ? $this->assignedUserID : null),
			'comment' => $this->comment
		);
		if ($this->assignedUserID) {
			// queue item is being processed
			if ($this->assignedUserID != $this->queue->assignedUserID) {
				$data['status'] = ModerationQueue::STATUS_PROCESSING;
			}
		}
		else {
			// queue is no longer processed, mark as outstanding
			if ($this->queue->assignedUserID) {
				$data['status'] = ModerationQueue::STATUS_OUTSTANDING;
			}
		}
		
		$this->objectAction = new ModerationQueueAction(array($this->queue->getDecoratedObject()), 'update', array('data' => $data));
		$this->objectAction->executeAction();
		
		// call saved event
		$this->saved();
		
		// reload queue to update assignment
		if ($this->assignedUserID != $this->queue->assignedUserID) {
			$this->queue = ViewableModerationQueue::getViewableModerationQueue($this->queue->queueID);
		}
	}
}
