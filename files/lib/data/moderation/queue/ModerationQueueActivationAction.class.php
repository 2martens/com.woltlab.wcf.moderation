<?php
namespace wcf\data\moderation\queue;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Executes actions for reports.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	data.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueActivationAction extends ModerationQueueAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$allowGuestAccess
	 */
	protected $allowGuestAccess = array('enableContent', 'removeContent');
	
	/**
	 * moderation queue editor object
	 * @var	wcf\data\moderation\queue\ModerationQueueEditor
	 */
	public $queue = null;
	
	/**
	 * Validates parameters to enable content.
	 */
	public function validateEnableContent() {
		if (empty($this->objects)) {
			$this->readObjects();
			if (empty($this->objects)) {
				throw new UserInputException('objectIDs');
			}
		}
		
		$this->queue = current($this->objects);
		if (!$this->queue->canEdit()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * Enables content.
	 */
	public function enableContent() {
		// enable content
		ModerationQueueActivationManager::getInstance()->enableContent($this->queue->getDecoratedObject());
		
		$this->queue->markAsDone();
	}
	
	/**
	 * Validates parameters to delete reported content.
	 */
	public function validateRemoveContent() {
		$this->validateEnableContent();
		
		$this->parameters['message'] = (isset($this->parameters['message']) ? StringUtil::trim($this->parameters['message']) : '');
	}
	
	/**
	 * Deletes reported content.
	 */
	public function removeContent() {
		// mark content as deleted
		ModerationQueueActivationManager::getInstance()->removeContent($this->queue->getDecoratedObject(), $this->parameters['message']);
		
		$this->queue->markAsDone();
	}
}
