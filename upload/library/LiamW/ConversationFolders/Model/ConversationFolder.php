<?php

namespace LiamW\ConversationFolders\Model;

class ConversationFolder extends \XenForo_Model
{
	public function getAllConversationFolders()
	{
		return $this->fetchAllKeyed('
			SELECT conversation_folder.*
			FROM xf_liam_conversation_folder AS conversation_folder
			WHERE user_id=?
		', 'conversation_folder_id', \XenForo_Visitor::getUserId());
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

	public function moveConversation($conversationId, $conversationFolderId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = \XenForo_Visitor::getUserId();
		}

		$this->_getDb()
			->query("INSERT INTO xf_liam_conversation_folder_relations(conversation_id, conversation_folder_id, user_id) VALUES(?,?,?) ON DUPLICATE KEY UPDATE conversation_folder_id=VALUES(conversation_folder_id)",
				array(
					$conversationId,
					$conversationFolderId,
					$userId
				));

		$this->rebuildFolderCounts();

		return true;
	}

	public function addConversationToFolder($conversationId, $conversationFolderId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = \XenForo_Visitor::getUserId();
		}

		$this->_getDb()->insert('xf_liam_conversation_folder_relations', array(
			'conversation_id' => $conversationId,
			'conversation_folder_id' => $conversationFolderId,
			'user_id' => $userId
		));

		$this->_getDb()
			->query("UPDATE xf_liam_conversation_folder SET conversation_count=conversation_count+1 WHERE conversation_folder_id=?",
				$conversationFolderId);

		return true;
	}

	public function removeConversationFromFolder($conversationId, $userId = null)
	{
		if ($userId === null)
		{
			$userId = \XenForo_Visitor::getUserId();
		}

		$conversationFolderId = $this->_getDb()
			->fetchOne("SELECT conversation_folder_id FROM xf_liam_conversation_folder_relations WHERE conversation_id=? AND user_id=?",
				array(
					$conversationId,
					$userId
				));

		$deleteClause = "conversation_id=$conversationId AND user_id={$userId}";
		$this->_getDb()->delete('xf_liam_conversation_folder_relations', $deleteClause);

		$this->_getDb()
			->query("UPDATE xf_liam_conversation_folder SET conversation_count=conversation_count-1 WHERE conversation_folder_id=?",
				$conversationFolderId);
	}

	public function removeConversationFromAllFolders($conversationId)
	{
		$this->_getDb()->delete('xf_liam_conversation_folder_relations',
			'conversation_id = ' . $this->_getDb()->quote($conversationId));

		$this->rebuildFolderCounts();
	}

	public function removeAllConversationsFromFolder($conversationFolderId)
	{
		$this->_getDb()->delete('xf_liam_conversation_folder_relations',
			'conversation_folder_id = ' . $this->_getDb()->quote($conversationFolderId));
	}
}