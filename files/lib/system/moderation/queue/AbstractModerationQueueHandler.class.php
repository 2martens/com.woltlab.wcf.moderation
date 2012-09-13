<?php
namespace wcf\system\moderation\queue;
use wcf\system\SingletonFactory;

/**
 * Default implementation for moderation queue handlers.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
abstract class AbstractModerationQueueHandler extends SingletonFactory implements IModerationQueueHandler {
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::getReportedObject()
	 */
	public function getReportedObject($objectID) {
		return null;
	}
}
