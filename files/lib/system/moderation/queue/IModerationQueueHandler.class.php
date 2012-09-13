<?php
namespace wcf\system\moderation\queue;

/**
 * Default interface for moderation queue handlers.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
interface IModerationQueueHandler {
	/**
	 * Returns a list of user ids affected by this item.
	 * 
	 * @param	integer		$objectID
	 * @return	array<integer>
	 */
	public function getAffectedUsers($objectID);
	
	/**
	 * Returns the reported object.
	 * 
	 * @param	integer		$objectID
	 * @return	wcf\data\IUserContent
	 */
	public function getReportedObject($objectID);
	
	/**
	 * Returns true, if given object id is valid.
	 * 
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function isValid($objectID);
}
