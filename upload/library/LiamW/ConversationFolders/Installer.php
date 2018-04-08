<?php

class LiamW_ConversationFolders_Installer
{
	protected static $_tables = array(
		'xf_liam_conversation_folder' => "
			CREATE TABLE xf_liam_conversation_folder (
				conversation_folder_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				user_id INT(10) UNSIGNED NOT NULL,
				title VARCHAR(50) NOT NULL,
				description TEXT NOT NULL,
				auto_file_regex VARCHAR(255) NOT NULL DEFAULT '',
				auto_file_weight INT(10) UNSIGNED NOT NULL DEFAULT 0,
				conversation_count INT(10) UNSIGNED NOT NULL DEFAULT 0
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci
		",
		'xf_liam_conversation_folder_relations' => "
			CREATE TABLE xf_liam_conversation_folder_relations (
				conversation_id INT(10) UNSIGNED NOT NULL,
				user_id INT(10) UNSIGNED NOT NULL,
				conversation_folder_id INT(10) UNSIGNED NOT NULL,
				PRIMARY KEY (conversation_id, user_id),
				INDEX conversation_id(conversation_id),
				INDEX conversation_folder_id(conversation_folder_id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci
		"
	);

	protected static $_coreAlters = array();

	protected static function _canBeInstalled(&$error)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			$error = 'XenForo 1.2.0+ is Required!';

			return false;
		}

		$hashErrors = XenForo_Helper_Hash::compareHashes(LiamW_ConversationFolders_FileSums::getHashes());

		if ($hashErrors)
		{
			$error = "The following files could not be found or contain unexpected content: <ul>";

			foreach ($hashErrors AS $file => $fileError)
			{
				$error .= "<li>$file - " . ($fileError == 'mismatch' ? 'File contains unexpected content' : 'File not found') . "</li>";
			}

			$error .= "</ul>";

			return false;
		}

		return true;
	}

	public static function install($installedAddon)
	{
		if (!self::_canBeInstalled($error))
		{
			throw new XenForo_Exception($error, true);
		}

		if ($installedAddon)
		{
			$version = $installedAddon['version_id'];

			if ($version <= 10002)
			{
				self::_runQuery("
					ALTER TABLE xf_liam_conversation_folder_relations
						ADD INDEX conversation_id(conversation_id),
						ADD INDEX conversation_folder_id(conversation_folder_id)
				");
			}

			if ($version <= 10004)
			{
				self::_runQuery("
					ALTER TABLE xf_liam_conversation_folder
						ADD auto_file_regex VARCHAR(255) NOT NULL DEFAULT '',
						ADD auto_file_weight INT(10) UNSIGNED NOT NULL DEFAULT 0
				");
			}

			if ($version <= 1030170)
			{
				self::_runQuery("ALTER TABLE xf_liam_conversation_folder ENGINE=InnoDb DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");
				self::_runQuery("ALTER TABLE xf_liam_conversation_folder_relations ENGINE=InnoDb DEFAULT CHARSET=utf8 COLLATE utf8_general_ci");

				self::_runQuery("ALTER TABLE xf_liam_conversation_folder CHANGE title title VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE description description TEXT CHARSET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE auto_file_regex auto_file_regex VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL");
			}
		}

		self::_installTables();
		self::_installCoreAlters();
	}

	public static function uninstall()
	{
		self::_uninstallTables();
		self::_uninstallCoreAlters();
	}

	protected static function _installTables(\Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_tables AS $tableName => $installSql)
		{
			self::_runQuery($installSql, $db);
		}
	}

	protected static function _uninstallTables(\Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_tables AS $tableName => $installSql)
		{
			self::_runQuery("DROP TABLE $tableName", $db);
		}
	}

	protected static function _installCoreAlters(\Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_coreAlters AS $tableName => $coreAlters)
		{
			foreach ($coreAlters AS $columnName => $installSql)
			{
				self::_runQuery($installSql, $db);
			}
		}
	}

	protected static function _uninstallCoreAlters(\Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_coreAlters AS $tableName => $coreAlters)
		{
			foreach ($coreAlters AS $columnName => $installSql)
			{
				self::_runQuery("ALTER TABLE $tableName DROP $columnName", $db);
			}
		}
	}

	protected static function _runQuery($sql, \Zend_Db_Adapter_Abstract $db = null)
	{
		if ($db == null)
		{
			$db = XenForo_Application::getDb();
		}

		try
		{
			$db->query($sql);
		} catch (\Zend_Db_Exception $e)
		{

		}
	}
}