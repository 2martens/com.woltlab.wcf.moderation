DROP TABLE IF EXISTS wcf1_moderation_queue;
CREATE TABLE wcf1_moderation_queue (
	queueID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	objectTypeID INT(10) NOT NULL,
	objectID INT(10) NOT NULL,
	userID INT(10) NULL,
	time INT(10) NOT NULL DEFAULT 0,
	
	-- internal
	assignedUserID INT(10) NULL,
	status TINYINT(1) NOT NULL DEFAULT 0,
	comment TEXT,
	lastChangeTime INT(10) NOT NULL DEFAULT 0,
	
	-- additional data, e.g. message if reporting content
	additionalData TEXT,
	
	UNIQUE KEY reportedObject (objectTypeID, objectID, userID)
);

ALTER TABLE wcf1_moderation_queue ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;
ALTER TABLE wcf1_moderation_queue ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE wcf1_moderation_queue ADD FOREIGN KEY (assignedUserID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;