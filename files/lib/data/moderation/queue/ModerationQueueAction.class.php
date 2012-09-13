<?php
namespace wcf\data\moderation\queue;
use wcf\data\AbstractDatabaseObjectAction;
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
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	protected $allowGuestAccess = array('prepareReport', 'report');
	
	/**
	 * Validates parameters to prepare a report.
	 */
	public function validatePrepareReport() {
		$this->parameters['objectType'] = (isset($this->parameters['objectType'])) ? StringUtil::trim($this->parameters['objectType']) : '';
		if (empty($this->parameters['objectType']) || !ModerationQueueManager::getInstance()->isValid(ModerationQueue::TYPE_REPORT, $this->parameters['objectType'])) {
			throw new UserInputException('objectType');
		}
		
		$this->parameters['objectID'] = (isset($this->parameters['objectID'])) ? intval($this->parameters['objectID']) : 0;
		if (!$this->parameters['objectID']) {
			throw new UserInputException('objectID');
		}
		
		// validate the combination of object type and object id
		if (!ModerationQueueManager::getInstance()->isValid(ModerationQueue::TYPE_REPORT, $this->parameters['objectType'], $this->parameters['objectID'])) {
			throw new UserInputException('objectID');
		}
	}
	
	/**
	 * Prepares a report.
	 */
	public function prepareReport() {
		// content was already reported
		$alreadyReported = (ModerationQueueManager::getInstance()->isAlreadyReported($this->parameters['objectType'], $this->parameters['objectID'])) ? 1 : 0;
		
		WCF::getTPL()->assign(array(
			'alreadyReported' => $alreadyReported,
			'object' => ModerationQueueManager::getInstance()->getReportedObject($this->parameters['objectType'], $this->parameters['objectID'])
		));
		
		return array(
			'alreadyReported' => $alreadyReported,
			'template' => WCF::getTPL()->fetch('moderationReport')
		);
	}
	
	/**
	 * Validates parameters for reporting.
	 */
	public function validateReport() {
		$this->parameters['message'] = (isset($this->parameters['message'])) ? StringUtil::trim($this->parameters['message']) : '';
		if (empty($this->parameters['message'])) {
			throw new UserInputException('message');
		}
		
		$this->validatePrepareReport();
	}
	
	/**
	 * Reports an item.
	 */
	public function report() {
		// if the specified content was already reported, e.g. a different user reported this
		// item meanwhile, silently ignore it. Just display a success and the user is happy :)
		if (!ModerationQueueManager::getInstance()->isAlreadyReported($this->parameters['objectType'], $this->parameters['objectID'])) {
			ModerationQueueManager::getInstance()->addReport(
				$this->parameters['objectType'],
				$this->parameters['objectID'],
				$this->parameters['message']
			);
		}
		
		return array(
			'reported' => 1
		);
	}
}
