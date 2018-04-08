<?php

namespace LiamW\ConversationFolders\Extend\Model;

class Conversation extends XFCP_Conversation
{
	const FETCH_FOLDER = 0x10;

	public function getConversationForUser($conversationId, $viewingUser, array $fetchOptions = array())
	{
		if (!isset($fetchOptions['join']))
		{
			$fetchOptions['join'] = 0;
		}

		$fetchOptions['join'] |= self::FETCH_FOLDER;

		return parent::getConversationForUser($conversationId, $viewingUser,
			$fetchOptions);
	}

	public function prepareConversationConditions(array $conditions, array &$fetchOptions)
	{
		$sqlConditions = array();
		$db = $this->_getDb();

		if (!empty($conditions['conversation_folder_id']))
		{
			$sqlConditions[] = 'conversation_folder_relation.conversation_folder_id = ' . $db->quote($conditions['conversation_folder_id']);

			if (!isset($fetchOptions['join']))
			{
				$fetchOptions['join'] = 0;
			}
			$fetchOptions['join'] |= self::FETCH_FOLDER;
		}

		return $this->getConditionsForClause($sqlConditions) . ' AND ' . parent::prepareConversationConditions($conditions,
			$fetchOptions);
	}

	public function prepareConversationFetchOptions(array $fetchOptions)
	{
		$selectFields = '';
		$joinTables = '';
		$db = $this->_getDb();

		if (isset($fetchOptions['join']) && $fetchOptions['join'] & self::FETCH_FOLDER)
		{
			$userId = \XenForo_Visitor::getUserId();

			$selectFields .= ',
					conversation_folder_relation.conversation_folder_id';
			$joinTables .= '
					LEFT JOIN xf_liam_conversation_folder_relations AS conversation_folder_relation ON
						(conversation_master.conversation_id = conversation_folder_relation.conversation_id) AND (conversation_folder_relation.user_id = ' . $userId . ')';
		}

		$existingFetchOptions = parent::prepareConversationFetchOptions($fetchOptions);

		$existingFetchOptions['selectFields'] .= $selectFields;
		$existingFetchOptions['joinTables'] .= $joinTables;

		return $existingFetchOptions;
	}

	public function deleteConversationForUser($conversationId, $userId, $deleteType)
	{
		parent::deleteConversationForUser($conversationId, $userId, $deleteType);

		$this->_getConversationFolderModel()->removeConversationFromFolder($conversationId, $userId);
	}

	/**
	 * @return \LiamW\ConversationFolders\Model\ConversationFolder
	 */
	protected function _getConversationFolderModel()
	{
		return $this->getModelFromCache('LiamW\ConversationFolders\Model\ConversationFolder');
	}
}

if (false)
{
	class XFCP_Conversation extends \XenForo_Model_Conversation
	{
	}
}