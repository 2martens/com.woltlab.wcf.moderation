<?php
namespace wcf\system\moderation\queue;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ModerationQueueAction;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\exception\SystemException;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Provides methods to manage moderated content and reports.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.moderation
 * @subpackage	system.moderation.queue
 * @category 	Community Framework
 */
class ModerationQueueManager extends SingletonFactory {
	/**
	 * list of definition names by definition id
	 * @var	array<string>
	 */
	protected $definitions = array();
	
	/**
	 * list of object type names categorized by type
	 * @var	array<array>
	 */
	protected $objectTypeNames = array();
	
	/**
	 * list of object types
	 * @var	array<wcf\data\object\type\ObjectType>
	 */
	protected $objectTypes = array();
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		$definitions = ObjectTypeCache::getInstance()->getDefinitionsByCategory('moderation');
		if ($definitions === null) {
			throw new SystemException("Could not find object type definitions for category name 'moderation'");
		}
		
		foreach ($definitions as $definition) {
			$this->definitions[$definition->definitionID] = $definition->definitionName;
			$this->objectTypeNames[$definition->definitionName] = array();
			
			$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes($definition->definitionName);
			foreach ($objectTypes as $objectType) {
				$this->objectTypeNames[$definition->definitionName][$objectType->objectType] = $objectType->objectTypeID;
				$this->objectTypes[$objectType->objectTypeID] = $objectType;
			}
		}
	}
	
	/**
	 * Returns true, if the given combination of definition and object type is valid.
	 * 
	 * @param	string		$definitionName
	 * @param	string		$objectType
	 * @return	boolean
	 */
	public function isValid($definitionName, $objectType) {
		if (!isset($this->objectTypeNames[$definitionName])) {
			return false;
		}
		else if (!isset($this->objectTypeNames[$definitionName][$objectType])) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Returns the object type processor.
	 * 
	 * @param	string		$definitionName
	 * @param	string		$objectType
	 * @return	object
	 */
	public function getProcessor($definitionName, $objectType) {
		$objectTypeID = $this->getObjectTypeID($definitionName, $objectType);
		if ($objectTypeID !== null) {
			return $this->objectTypes[$objectTypeID]->getProcessor();
		}
		
		return null;
	}
	
	/**
	 * Returns object type id.
	 * 
	 * @param	string		$definitionName
	 * @param	string		$objectType
	 * @return	integer
	 */
	public function getObjectTypeID($definitionName, $objectType) {
		if ($this->isValid($definitionName, $objectType)) {
			return $this->objectTypeNames[$definitionName][$objectType];
		}
		
		return null;
	}
	
	/**
	 * Returns a list of moderation types.
	 * 
	 * @return	array<string>
	 */
	public function getModerationTypes() {
		return array_keys($this->objectTypeNames);
	}
	
	/**
	 * Returns a list of available definition ids.
	 * 
	 * @return	array<integer>
	 */
	public function getDefinitionIDs() {
		return array_keys($this->definitions);
	}
	
	/**
	 * Returns a list of object type ids for given definiton ids.
	 * 
	 * @param	array<integer>		$definitionIDs
	 * @return	array<integer>
	 */
	public function getObjectTypeIDs(array $definitionIDs) {
		$objectTypeIDs = array();
		foreach ($definitionIDs as $definitionID) {
			if (isset($this->definitions[$definitionID])) {
				foreach ($this->objectTypeNames[$this->definitions[$definitionID]] as $objectTypeID) {
					$objectTypeIDs[] = $objectTypeID;
				}
			}
		}
		
		return $objectTypeIDs;
	}
}
