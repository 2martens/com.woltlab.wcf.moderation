<?php
namespace wcf\data\moderation\queue;
use wcf\data\DatabaseObject;

/**
 * Represents a moderation queue entry.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	data.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueue extends DatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'moderation_queue';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'queueID';
	
	// states of column 'status'
	const STATUS_OUTSTANDING = 0;
	const STATUS_PROCESSING = 1;
	const STATUS_DONE = 2;
	
	const TYPE_MODERATED_CONTENT = 0;
	const TYPE_REPORT = 1;
}
