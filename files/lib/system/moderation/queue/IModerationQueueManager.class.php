<?php
namespace wcf\system\moderation\queue;

/**
 * Default interface for moderation queue managers.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
interface IModerationQueueManager {
	/**
	 * Returns true, if given object type is valid, optionally checking object id.
	 * 
	 * @param	string		$objectType
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function isValid($objectType, $objectID = null);
	
	/**
	 * Returns object type id for given object type.
	 *
	 * @param	string		$objectType
	 * @return	integer
	 */
	public function getObjectTypeID($objectType);
	
	/**
	 * Returns object type processor by object type.
	 * 
	 * @param	string		$objectType
	 * @return	object
	 */
	public function getProcessor($objectType);
}
