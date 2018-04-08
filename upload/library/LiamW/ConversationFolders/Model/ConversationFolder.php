<?php

class LiamW_ConversationFolders_Model_ConversationFolder extends XenForo_Model
{
	public function getAllConversationFolders()
	{
		return $this->fetchAllKeyed('
			SELECT conversation_folder.*
			FROM xf_liam_conversation_folder AS conversation_folder
			WHERE user_id=?
		', 'conversation_folder_id', XenForo_Visitor::getUserId());
	}

	public function getConversationFolderById($conversationFolderId)
	{
		return $this->_getDb()->fetchRow('
			SELECT conversation_folder.*
			FROM xf_liam_conversation_folder AS conversation_folder
			WHERE conversation_folder_id=?
		', $conversationFolderId);
	}

	public function rebuildFolderCounts()
	{
		$this->_getDb()
			->query("UPDATE xf_liam_conversation_folder SET conversation_count=(SELECT COUNT(conversation_folder_id) FROM xf_liam_conversation_folder_relations WHERE xf_liam_conversation_folder_relations.conversation_folder_id=xf_liam_conversation_folder.conversation_folder_id)");
	}

	public function getConversationFoldersWithAutoFileForUser($userId)
	{
		return $this->fetchAllKeyed("
			SELECT conversation_folder.*
			FROM xf_liam_conversation_folder AS conversation_folder
			WHERE user_id=? AND auto_file_regex <> ''
			ORDER BY auto_file_weight ASC
		", 'conversation_folder_id', $userId);
	}

	public function moveConversation($conversationId, $conversationFolderId, $userId = null)
	{
		if (!$conversationFolderId)
		{
			// This method deletes the row, which is ideal as it will converse storage. Not an issue for small sites, but could be for larger ones. (The row also needs to be deleted if the show all option is disabled).
			$this->removeConversationFromFolder($conversationId, $userId);

			return;
		}

		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$this->_getDb()
			->query("INSERT INTO xf_liam_conversation_folder_relations(conversation_id, conversation_folder_id, user_id) VALUES(?,?,?) ON DUPLICATE KEY UPDATE conversation_folder_id=VALUES(conversation_folder_id)",
				array(
					$conversationId,
					$conversationFolderId,
					$userId
				));

		$this->rebuildFolderCounts();
	}

	public function addConversationToFolder($conversationId, $conversationFolderId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$this->_getDb()->insert('xf_liam_conversation_folder_relations', array(
			'conversation_id' => $conversationId,
			'conversation_folder_id' => $conversationFolderId,
			'user_id' => $userId
		));

		$this->rebuildFolderCounts();
	}

	public function removeConversationFromFolder($conversationId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = XenForo_Visitor::getUserId();
		}

		$this->_getDb()
			->query('DELETE FROM xf_liam_conversation_folder_relations WHERE conversation_id=? AND user_id=?', array(
				$conversationId,
				$userId
			));

		$this->rebuildFolderCounts();
	}

	public function removeConversationFromAllFolders($conversationId)
	{
		$this->_getDb()->delete('xf_liam_conversation_folder_relations',
			'conversation_id = ' . $this->_getDb()->quote($conversationId));

		$this->rebuildFolderCounts();
	}

	public function removeAllConversationsFromFolder($conversationFolderId)
	{
		$this->_getDb()->query('DELETE FROM xf_liam_conversation_folder_relations WHERE conversation_folder_id=?',
			$conversationFolderId);
	}

	public function canUseConversationFolder($conversationFolder, &$errorPhraseKey = '', array $viewingUser = null)
	{
		$this->standardizeViewingUserReference($viewingUser);

		return ($conversationFolder['user_id'] == $viewingUser['user_id']);
	}

	public function autoFileConversation(array $conversation, array $recipients)
	{
		$conversationTitle = $conversation['title'];

		foreach ($recipients as $user)
		{
			$user['permissions'] = XenForo_Permission::unserializePermissions($user['global_permission_cache']);

			if (!XenForo_Permission::hasPermission($user['permissions'], 'general', 'conversationFolders_afile'))
			{
				continue;
			}

			$substringMatch = XenForo_Application::getOptions()->liam_conversationFolders_auto_file_substring;

			foreach ($this->getConversationFoldersWithAutoFileForUser($user['user_id']) as $conversationFolder)
			{
				if (($substringMatch && stripos($conversationTitle,
							$conversationFolder['auto_file_regex']) !== false) || (!$substringMatch && preg_match($conversationFolder['auto_file_regex'],
							$conversationTitle))
				)
				{
					$this->addConversationToFolder($conversation['conversation_id'],
						$conversationFolder['conversation_folder_id'], $user['user_id']);

					break;
				}
			}
		}
	}

	public function resetAutoFileSystem()
	{
		$this->_getDb()->query("UPDATE xf_liam_conversation_folder SET auto_file_regex=''");
	}
}